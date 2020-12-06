<?php

namespace App\Http\Controllers\Api;

use App\Offers;
use App\Speciality;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\OffersResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class OfferController extends ApiController
{
	public function getAllOffers(Request $request)
	{
		$offers_by_categories=Offers::with('specialities')->where('status',1)->orderByRaw('RAND()')->skip($request->offset)->take($request->limit)->get();
		$offers_by_categories=OffersResource::collection($offers_by_categories);
		$data['offers_by_categories'] = $offers_by_categories;
		
		$trending_offers=Offers::where('is_featured',1)->where('status',1)->orderBy('id', 'DESC')->skip($request->offset)->take($request->limit)->get();
		$trending_offers=OffersResource::collection($trending_offers);
		$data['trending_offers'] = $trending_offers;
		
		$new_offers=Offers::where('status',1)->orderBy('id', 'DESC')->skip($request->offset)->take($request->limit)->get();
		$new_offers=OffersResource::collection($new_offers);
		$data['new_offers'] = $new_offers;
		
		return $this->apiDataResponse($data);
	}
	
	public function getAllOffersByCategories()
	{
		$specialities = [];
		$offers_by_categories=Offers::with('specialities')->where('status',1)->orderBy('id', 'DESC')->get();		
		$offers_by_categories=OffersResource::collection($offers_by_categories);
		
		foreach($offers_by_categories as $key => $val)
		{
			$specialities[$val->specialities->name][] = $val;
		}
		
		return $this->apiDataResponse($specialities);
	}

	public function offersByCategories()
	{
		$offers_by_categories=Offers::with('specialities')->where('status',1)->orderBy('id', 'DESC')->get();		
		$offers_by_categories=OffersResource::collection($offers_by_categories);
		
		return $this->apiDataResponse($offers_by_categories);
	}
	
	public function getAllTrendingOffers()
	{
		$trending_offers=Offers::where('is_featured',1)->where('status',1)->orderBy('id', 'DESC')->get();
		$trending_offers=OffersResource::collection($trending_offers);
		
		return $this->apiDataResponse($trending_offers);
	}
	
	public function getAllNewOffers()
	{
		$new_offers=Offers::where('status',1)->orderBy('id', 'DESC')->get();
		$new_offers=OffersResource::collection($new_offers);
		
		return $this->apiDataResponse($new_offers);
	}
	
	public function getAllOffersByCategory($speciality_id = 0)
	{
		$speciality=Speciality::find($speciality_id);
		
		$offers_by_categories=Offers::where('speciality_id',$speciality_id)->where('status',1)->get();
		$offers_by_categories=OffersResource::collection($offers_by_categories);
		
		//$data[$speciality->name] = $offers_by_categories;
		
		return $this->apiDataResponse($offers_by_categories);
	}
	
	public function getOffer($id = 0)
	{
		// select('id', 'name', 'short_description', 'description', 'thumb', 'image', DB::raw("CONCAT(discount,discount_unit) AS discount"))->
		$offer=Offers::where('id',$id)->where('status',1)->get();		
		
		/*$arr = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$offer->code = '';
		for($i = 0; $i <= 8; $i++)
		{
			$offer->code .= $arr[array_rand($arr)];
		}*/
		
		$offer=OffersResource::collection($offer);
		return $this->apiDataResponse($offer);
	}
}
