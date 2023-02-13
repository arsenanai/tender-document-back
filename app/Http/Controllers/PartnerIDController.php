<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnyResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\{Partner, Subpartner, PartnerID};
use App\Rules\PartnerIDRule;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts = PartnerID::create($request->all());
        
        return $posts;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function check(Request $request) {
        $request->validate([
            'entry' => ['required', 'string', new PartnerIDRule],
        ]);
        try {
            $parts = explode('-', $request->input('entry'));
            $date = $parts[0];
            $partner = Partner::where('twoDigitId', $parts[1])->firstOrFail();
            $subpartner = Subpartner::where('twoDigitId', $parts[2])->firstOrFail();
            $partnerID = PartnerID::where('threeDigitId', $parts[3])->firstOrFail();
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
