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
        $request->validate([
            'lotNumber' => 'required',
            'procurementNumber' => 'required',
            'comments' => 'string|nullable',
            'subpartner_id' => 'required|exists:subpartners,id',
        ]);
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
        return response()->json([
            "success" => true,
            "message" => "item.retrieved.successfully",
            "data" => $object
        ]);
    }

    public function update(Request $request, PartnerID $partnerId)
    {
    $input = $request->all();
    $validator = Validator::make($input, [
    'name' => 'required',
    'detail' => 'required'
    ]);
    if($validator->fails()){
    return $this->sendError('Validation Error.', $validator->errors());       
    }
    $partnerId->name = $input['name'];
    $partnerId->detail = $input['detail'];
    $partnerId->save();
    return response()->json([
    "success" => true,
    "message" => "PartnerID updated successfully.",
    "data" => $partnerId
    ]);
    }
    public function destroy(PartnerID $partnerId)
    {
    $partnerId->delete();
    return response()->json([
    "success" => true,
    "message" => "PartnerID deleted successfully.",
    "data" => $partnerId
    ]);
    }

    public function check(Request $request) {
        $request->validate([
            'entry' => ['required', 'string', new PartnerIDRule],
        ]);
        try {
            $parts = explode('-', $request->input('entry'));
            $date = $parts[0];
            $partner = Partner::find($parts[1])->firstOrFail();
            $subpartner = Subpartner::find($parts[2])->firstOrFail();
            $partnerID = PartnerID::find($parts[3])->firstOrFail();
            if (
                $subpartner->is($partnerID->subpartner)
                && $partner->is($subpartner->partner)
                && $partnerID->created_at->format('yymmdd') === $date
            ) {
                return response()->json(
                    [
                        'answer' => 'correct',
                        'partner' => $partner,
                        'subpartner' => $subpartner,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'answer' => 'incorrect',
                        'reason' => 'mismatch'
                    ]
                );
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
