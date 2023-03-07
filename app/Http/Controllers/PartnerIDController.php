<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use Illuminate\Http\Request;
use App\Models\{Partner, Subpartner, PartnerID};
use App\Rules\PartnerIDRule;
use Illuminate\Http\Response;

class PartnerIDController extends Controller
{
    private $partner, $subpartner, $partnerID, $date;

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
    public function index(Request $request)
    {
        $r = PartnerID::with('subpartner.partner');
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
                if (preg_match($pattern, $s) && $this->checkById($s)) {
                    return new AnyResource(
                        $r->where('id', $this->partnerID->id)
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
                $r->where('lotNumber', 'like', "%$s%")
                    ->orWhere('procurementNumber', 'like', "%$s%")
                    ->orWhereHas('subpartner', function($query) use($s){
                        $query->where('name', 'like', "%$s%");
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
        $r = $r->paginate(config('cnf.PAGINATION_SIZE'));
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
        $object = PartnerID::with('subpartner')->find($id);
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
            if ($this->checkById($request->input('entry'))) 
            {
                return response()->json(
                    [
                        'answer' => 'correct',
                        'details' => [
                            'partner' => $this->partner,
                            'subpartner' => $this->subpartner,
                        ],
                    ]
                );
            } else {
                $r = [
                    'answer' => 'incorrect',
                    'reason' => 'mismatch',
                ];
                if (config('cnf.APP_DEBUG') == 'true') {
                    $r['details'] = 'p: ' . $this->partner->id . ' vs ' . $this->subpartner->partner->id
                        . ', sp: ' . $this->subpartner->id . ' vs ' . $this->partnerID->subpartner->id
                        . ', id: ' . $this->partnerID->created_at->format('ymd') . ' vs ' . $this->date;
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

    private function checkById($id) {
        $parts = explode('-', $id);
        $this->date = $parts[0];
        $this->partner = Partner::findOrFail((int)$parts[1]);
        $this->subpartner = Subpartner::with('partner')->findOrFail((int)$parts[2]);
        $this->partnerID = PartnerID::with('subpartner')->findOrFail((int)$parts[3]);
        return (
            /*$this->partnerID->id === (int)$parts[3]
            &&*/ $this->subpartner->is($this->partnerID->subpartner)
            && $this->partner->is($this->subpartner->partner)
            && $this->partnerID->created_at->format('ymd') === $this->date
        );
    }
}
