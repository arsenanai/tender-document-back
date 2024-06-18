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
        'bin' => 'string|required|unique:subpartners'
    ];

    public function index(Request $request)
    {
        $r = Subpartner::with('partner');
        try {
            if ($request->has('search')) {
                $s = $request->input('search');
                $r->where('id', $s)
                    ->orWhere('name', 'like', "%$s%");
                if (!$request->has('parent')) {
                    $r->orWhereHas('partner', function ($query) use ($s) {
                        $query->where('name', 'like', "%$s%");
                    });
                }
            }
        } catch (\Throwable $t) {
        }
        if ($request->has('parent')) {
            $p = $request->input('parent');
            $r->where('partner_id', $p);
        }
        $r = $r->orderBy('id', 'desc')->paginate(config('cnf.PAGINATION_SIZE'));
        return new AnyResource($r);
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
        $object = Subpartner::with('partner')->find($id);
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
