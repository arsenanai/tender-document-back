<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use App\Models\Subpartner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubpartnerController extends Controller
{

    private $byRules = [
        'name' => 'string|required',
        'partner_id' => 'required|exists:partners,id',
    ];
    
    public function index()
    {
        return new AnyResource(Subpartner::paginate(config('cnf.PAGINATION_SIZE')));
    }

    public function store(Request $request)
    {
        $request->validate($this->byRules);
        $object = Subpartner::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.created.successfully",
            "data" => $object->toArray()
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $object = Subpartner::find($id);
        if (is_null($object)) {
            return $this->sendError('item.not.found');
        }
        return response()->json([
            "success" => true,
            "message" => "item.retrieved.successfully",
            "data" => $object
        ]);
    }

    public function update(Request $request, Subpartner $subpartner)
    {
        $request->validate($this->byRules);
        $subpartner->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.updated.successfully",
            "data" => $subpartner->toArray()
        ], Response::HTTP_ACCEPTED);
    }

    public function destroy(Subpartner $subpartner)
    {
        $subpartner->delete();
        return response()->json([
            "success" => true,
            "message" => "item.deleted.successfully",
            "data" => $subpartner
        ], Response::HTTP_ACCEPTED);
    }
}
