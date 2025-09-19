<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Integration;
use App\Models\Lead;
use App\Models\Link;
use App\Models\Offer;
use App\Models\OfferLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $leads = Lead::latest()->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $leads->items(),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'total' => $leads->total(),
                'per_page' => $leads->perPage(),
            ]
        ]);
    }

    public function postback(Request $request, $resource)
    {
        Log::info($resource);
        Log::info($request->all());

        $apikey = $request->apikey;
        $integration = Integration::where('name', $resource)->where('apikey', $apikey)->first();

        if(!$integration){
            return response()->json(['status' => 'error', 'message' => 'Wrong ApiKey!']);
        }

        $data = explode(':', $request->utm_campaign);
        //if(count($data) < 3){
        //    return response()->json(['status' => 'error']);
        //}


        $offer_id = isset($data[1]) ? $data[1] : null;
        $link_id = isset($data[2]) ? $data[2] : null;
        $user_id = (isset($data[0]) && $data[0] != '') ? $data[0] : null;
        $sub1 = null;
        $sub2 = null;
        $sub3 = null;
        $sub4 = null;
        $sub5 = null;

        if($link_id || $offer_id){
            $link = Link::where('user_id', $user_id)->where('id', $link_id)->first();
            if($link){
                $offer = Offer::findOrFail($link->offer_id);
            }else{
                $offer = Offer::findOrFail($offer_id);
            }
        }


        $country_req = $request->citizenship == 'РФ' ? 'RU' : $request->citizenship;

        $money=0;

        if(!isset($offer)){
            $offer = Offer::first();
        }


        if(isset($offer) && $country_req){
            $offer_id = $offer->id;
            $country = Country::where('iso_name', $country_req)->first();
            if($country){
                $price = $offer->prices()->where('country_id', $country->id)->first();             
            }
        }
        if(isset($offer) && !$price){
            $price = $offer->prices()->where('country_id', 'RU')->first();
            if(!isset($price)){
                $price = $offer->prices()->first();
            }
        }

        if(isset($price)){
            $money = $price->price;
        }

        if(isset($link)){
            $sub1 = $link->sub1;
            $sub2 = $link->sub2;
            $sub3 = $link->sub3;
            $sub4 = $link->sub4;
            $sub5 = $link->sub5;
        }
        
        try {
            $name = $request->lastname ? $request->lastname : 'клиент';
            $name .= $request->firstname ? ' '.$request->firstname : '';

            $lead = Lead::create([
                'name' => $name,
                'firstname' =>  $request->firstname ? $request->firstname : '',
                'lastname' =>  $request->lastname ? $request->lastname : '',
                'gender' =>  $request->gender ? $request->gender : '',
                'birthday' =>  $request->birthday ? $request->birthday : '',
                'address' =>  $request->address ? $request->address : '',
                'citizenship' => $request->citizenship ? $request->citizenship : '',
                'email' => $request->email ? $request->email : '',
                'phone' => $request->phone ? $request->phone : '',
                'user_id' =>$user_id,
                'offer_id' => $offer_id,
                'sub1' =>$sub1,
                'sub2' =>$sub2,
                'sub3' =>$sub3,
                'sub4' =>$sub4,
                'sub5' =>$sub5,
                'utm_source' =>$request->utm_source,
                'utm_medium' =>$request->utm_medium,
                'utm_campaign' =>$request->utm_campaign,
                'utm_term' =>$request->utm_term,
                'utm_content' =>$request->utm_content,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'price' => $money
            ]);

            return response()->json([
                'data' => $lead,
                'message' => 'Lead created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create lead',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
            'offer_link_id' => 'nullable|exists:offer_links,id',
            'country_iso' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'currency' => 'nullable|string|size:3',
            'utm_source' => 'nullable|string|max:100',
            'utm_medium' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
            'utm_term' => 'nullable|string|max:100',
            'utm_content' => 'nullable|string|max:100',
            'sub1' => 'nullable|string|max:100',
            'sub2' => 'nullable|string|max:100',
            'sub3' => 'nullable|string|max:100',
            'sub4' => 'nullable|string|max:100',
            'sub5' => 'nullable|string|max:100',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $offer = Offer::findOrFail($request->offer_id);
        $price=0;
        if($request->country_iso){
            $country = Country::where('iso_name', $request->country_iso)->first();
            if($country){
                $price = $offer->prices()->where('country_id', $country->id)->first();
            }
        }
        if(!$price){
            $price = $offer->prices()->whereNull('country_id')->first();
        }

        try {
            $lead = Lead::create(array_merge($validator->validated(), [
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'price' => $price->price
            ]));

            return response()->json([
                'data' => $lead,
                'message' => 'Lead created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create lead',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Lead $lead)
    {
        $lead->load(['offer', 'user', 'link']);

        return response()->json([
            'data' => $lead
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:new,processing,completed,rejected,canceled',
            'comment' => 'nullable|string',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $lead->update($validator->validated());

        return response()->json([
            'data' => $lead,
            'message' => 'Lead updated successfully'
        ]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully'
        ]);
    }

    public function stats(Request $request)
    {
        $stats = Lead::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "new" THEN 1 ELSE 0 END) as new,
            SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = "canceled" THEN 1 ELSE 0 END) as canceled
        ')->first();

        return response()->json([
            'data' => $stats
        ]);
    }
}