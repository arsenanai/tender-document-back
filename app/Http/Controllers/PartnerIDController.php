<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\{Partner, Subpartner, PartnerID};
use App\Rules\PartnerIDRule;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;

class PartnerIDController extends Controller
{
    private $byRules = [
        'lotNumber' => 'required',
        'procurementNumber' => 'required',
        'comments' => 'string|nullable',
        'subpartner_id' => 'required|exists:subpartners,id',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new AnyResource(PartnerID::paginate(env('PAGINATION_SIZE', 20)));
    }

    public function store(Request $request)
    {
        $request->validate($this->byRules);
        $object = PartnerID::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.created.successfully",
            "data" => $object->toArray()
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $object = PartnerID::find($id);
        if (is_null($object)) {
            return $this->sendError('item.not.found');
        }
        $object->fullEntry = $object->getFullEntry();
        return response()->json([
            "success" => true,
            "message" => "item.retrieved.successfully",
            "data" => $object
        ]);
    }

    public function update(Request $request, PartnerID $partnerId)
    {
        $request->validate($this->byRules);
        $partnerId->update($request->all());
        $partnerId->fullEntry = $partnerId->getFullEntry();
        return response()->json([
            "success" => true,
            "message" => "item.updated.successfully",
            "data" => $partnerId->toArray()
        ], Response::HTTP_ACCEPTED);
    }

    public function destroy(PartnerID $partnerId)
    {
        $partnerId->delete();
        return response()->json([
            "success" => true,
            "message" => "item.deleted.successfully",
            "data" => $partnerId
        ], Response::HTTP_ACCEPTED);
    }

    public function check(Request $request) {
        $request->validate([
            'entry' => ['required', 'string', new PartnerIDRule],
        ]);
        try {
            $parts = explode('-', $request->input('entry'));
            $date = $parts[0];
            $partner = Partner::findOrFail((int)$parts[1]);
            $subpartner = Subpartner::with('partner')->findOrFail((int)$parts[2]);
            $partnerID = PartnerID::with('subpartner')->findOrFail((int)$parts[3]);
            if (
                $subpartner->is($partnerID->subpartner)
                && $partner->is($subpartner->partner)
                && $partnerID->created_at->format('ymd') === $date
            ) {
                return response()->json(
                    [
                        'answer' => 'correct',
                        'details' => [
                            'partner' => $partner,
                            'subpartner' => $subpartner,
                        ],
                    ]
                );
            } else {
                $r = [
                    'answer' => 'incorrect',
                    'reason' => 'mismatch',
                ];
                if (env('APP_DEBUG') == 'true') {
                    $r['details'] = 'p: ' . $partner->id . ' vs ' . $subpartner->partner->id
                        . ', sp: ' . $subpartner->id . ' vs ' . $partnerID->subpartner->id
                        . ', id: ' . $partnerID->created_at->format('ymd') . ' vs ' . $date;
                }
                return response()->json($r);
            }
        } catch (\Throwable $t) {
            return response()->json([
                'answer' => 'incorrect',
                'reason' => 'exception',
                'details' => $t->getMessage(),
            ]);
        }
        
    }
}
