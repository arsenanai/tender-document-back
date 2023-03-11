<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    private $byRules = [
        'name' => 'string|required',
        'lotNumber' => 'required|unique:partners',
        'procurementNumber' => 'required|unique:partners',
    ];

    public function index(Request $request)
    {
        $r = Partner::where('id', '>', -1);
        try{
            if ($request->has('search')) {
                $s = $request->input('search');
                $r->where('id', $s)
                    ->orWhere('name', 'like', "%$s%")
                    ->orWhere('lotNumber', 'like', "%$s%")
                    ->orWhere('procurementNumber', 'like', "%$s%")
                    ;
            }
        } catch(\Throwable $t) {

        }
        return new AnyResource($r->paginate(config('cnf.PAGINATION_SIZE')));
    }

    public function store(Request $request)
    {
        $request->validate($this->byRules);
        $object = Partner::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.created.successfully",
            "data" => $object->toArray()
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $object = Partner::find($id);
        if (is_null($object)) {
            return $this->sendError('item.not.found');
        }
        return response()->json([
            "success" => true,
            "message" => "item.retrieved.successfully",
            "data" => $object
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'string|required',
            'lotNumber' => [
            'required',
                Rule::unique('partners')->ignore($partner->id),
            ],
            'procurementNumber' => [
                'required',
                Rule::unique('partners')->ignore($partner->id),
            ]
        ]);
        $partner->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.updated.successfully",
            "data" => $partner->toArray()
        ], Response::HTTP_ACCEPTED);
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return response()->json([
            'success' => true,
            'message' => 'item.deleted.successfully',
            'data' => $partner
        ], Response::HTTP_ACCEPTED);
    }
}
