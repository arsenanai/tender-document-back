<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use Illuminate\Http\Request;
use App\Models\{Partner, Subpartner, PartnerID};
use App\Rules\PartnerIDRule;
use Illuminate\Http\Response;

class PartnerIDController extends Controller
{

    private $byRules = [
        'comments' => 'string|nullable',
        'subpartner_id' => 'required|exists:subpartners,id',
        'number_id' => 'required|exists:numbers,id',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $r = PartnerID::with('number', 'subpartner.partner');
        try{
            if ($request->has('search')) {
                $s = $request->input('search');
                $pattern = "/^([0-9]{6})?[-]?([0-9]{"
                    . (int) config('cnf.PAD_PARTNER_ID')
                    . "})?[-]?([0-9]{"
                    . (int) config('cnf.PAD_SUBPARTNER_ID')
                    . "})?[-]?([0-9]{"
                    . (int) config('cnf.ID_PAD')
                    . "})?$/";
                $partnerID = new \stdClass(); $partner= null; $subpartner= null; $date= null;
                if (preg_match($pattern, $s) && $this->checkById($s, $date, $partner, $subpartner, $partnerID)) {
                    return new AnyResource(
                        $r->where('id', $partnerID->id)
                        ->paginate(config('cnf.PAGINATION_SIZE'))
                    );
                }
            }
        } catch(\Throwable $t) {
            // echo 'block 1:' . $t->__toString() . PHP_EOL;
        }
        try {
            if ($request->has('search')) {
                $s = $request->input('search');
                $r->whereHas('subpartner', function($query) use($s){
                        $query->where('name', 'like', "%$s%");
                    })
                    ->orWhereHas('number', function($query) use($s){
                        $query->where('lotNumber', 'like', "%$s%");
                    })
                    ->orWhereHas('number', function($query) use($s){
                        $query->where('procurementNumber', 'like', "%$s%");
                    })
                    ->orWhereHas('subpartner', function($query) use($s){
                        $query->whereHas('partner', function($query) use($s){
                            $query->where('name', 'like', "%$s%");
                        });
                    });
            }
        } catch(\Throwable $t) {
            // echo 'block 2:' . $t->__toString() . PHP_EOL;
        }
        $r = $r->orderBy('id', 'desc')->paginate(config('cnf.PAGINATION_SIZE'));
        return new AnyResource($r);
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
        $object = PartnerID::with('subpartner.partner', 'number')->find($id);
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
            $partnerID = new \stdClass(); $partner= new \stdClass(); $subpartner= new \stdClass(); $date= null;
            if ($this->checkById($request->input('entry'), $date, $partner, $subpartner, $partnerID)) 
            {
                $parts = explode('-', $request->input('entry'));
                $subpartner = Subpartner::findOrFail((int)$parts[2]);
                return response()->json(
                    [
                        'answer' => 'correct',
                        'details' => [
                            //'partner' => $this->partner,
                            'subpartner' => $subpartner,
                        ],
                    ]
                );
            } else {
                $r = [
                    'answer' => 'incorrect',
                    'reason' => 'mismatch',
                ];

                if (config('cnf.APP_DEBUG') == 'true') {
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
                'details' => $t->getTrace(),
            ]);
        }
        
    }

    private function checkById($id, $date, $partner, $subpartner, $partnerID) {
        $parts = explode('-', $id);
        $date = $parts[0];
        $partner = Partner::findOrFail((int)$parts[1]);
        $subpartner = Subpartner::findOrFail((int)$parts[2]);
        $partnerID = PartnerID::with('subpartner')->findOrFail((int)$parts[3]);
        return (
            $partnerID->id === (int)$parts[3]
            && $subpartner->is($partnerID->subpartner)
            && $partner->is($subpartner->partner)
            && $partnerID->created_at->format('ymd') === $date
        );
    }
}
