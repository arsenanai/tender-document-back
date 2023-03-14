<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use App\Models\Number;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NumbersController extends Controller
{
    private $byRules = [
        'partner_id' => 'required|exists:partners,id',
        'lotNumber' => 'required|unique:numbers',
        'procurementNumber' => 'required|unique:numbers',
    ];

    public function index(Request $request)
    {
        $r = Number::with('partner');
        try{
            if ($request->has('search')) {
                $s = $request->input('search');
                $r->where('lotNumber', 'like', "%$s%")
                    ->orWhere('procurementNumber', 'like', "%$s%");
                if (!$request->has('parent')) {
                    $r->orWhereHas('partner', function($query) use($s){
                        $query->where('name', 'like', "%$s%");
                    });
                }
            }
        } catch(\Throwable $t) {

        }
        if ($request->has('parent')) {
            $p = $request->input('parent');
            $r->where('partner_id', $p);
        }
        return new AnyResource($r->orderBy('id', 'desc')->paginate(config('cnf.PAGINATION_SIZE')));
    }

    public function store(Request $request)
    {
        $request->validate($this->byRules);
        $object = Number::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.created.successfully",
            "data" => $object->toArray()
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $object = Number::find($id);
        if (is_null($object)) {
            return $this->sendError('item.not.found');
        }
        return response()->json([
            "success" => true,
            "message" => "item.retrieved.successfully",
            "data" => $object
        ]);
    }

    public function update(Request $request, Number $number)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'lotNumber' => [
                'required',
                Rule::unique('numbers')->ignore($number->id),
            ],
            'procurementNumber' => [
                'required',
                Rule::unique('numbers')->ignore($number->id),
            ]
        ]);
        $number->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "item.updated.successfully",
            "data" => $number->toArray()
        ], Response::HTTP_ACCEPTED);
    }

    public function destroy(Number $number)
    {
        $number->delete();
        return response()->json([
            'success' => true,
            'message' => 'item.deleted.successfully',
            'data' => $number
        ], Response::HTTP_ACCEPTED);
    }
}
