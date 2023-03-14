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
    ];

    public function index(Request $request)
    {
        $r = Partner::where('id', '>', -1);
        try{
            if ($request->has('search')) {
                $s = $request->input('search');
                $r->where('id', $s)
                    ->orWhere('name', 'like', "%$s%")
                    ;
            }
        } catch(\Throwable $t) {

        }
        return new AnyResource($r->orderBy('id', 'desc')->paginate(config('cnf.PAGINATION_SIZE')));
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
        $request->validate($this->byRules);
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
