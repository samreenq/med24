<?php
namespace App\Http\Controllers\Api;
use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Offers;
use App\Banners;
use App\Settings;
use App\Feedbacks;
use App\Faq;
use App\Country;
use App\City;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class HomeController extends Controller
{
    public function countries(Request $request)
    {
        $countries = Country::select('id', 'name')->get();

        if(count($countries) <= 0) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $countries
        ], 200);
    }

    public function cities(Request $request)
    {

        $cities = City::select('id', 'name', 'country_id');

        if($request->country_id) {
            $cities = $cities->where('country_id', $request->country_id);
        }

        $cities = $cities->get();

        if(count($cities) <= 0) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $cities
        ], 200);
    }

    public function offers(Request $request)
    {
        $offers = Offers::getAllOffers();

        if(count($offers) <= 0) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $offers
        ], 200);
    }

    public function offerDetail($offerId)
    {
        $offer = Offers::getOffer($offerId);

        if(!$offer) {
            return response()->json([
                'status' => 0,
                'message' => 'No record found'
            ], 401);
        }

        $lifting_offer = [];

        $lifting_offer['id']               = $offer->id;
        $lifting_offer['name']             = $offer->name;
        $lifting_offer['discount']         = $offer->discount;
        $lifting_offer['discount_unit']    = $offer->discount_unit;
        $lifting_offer['start_datetime']   = $offer->start_datetime;
        $lifting_offer['end_datetime']     = $offer->end_datetime;
        $lifting_offer['description']      = $offer->description;
        $lifting_offer['image']            = $offer->image;
        $lifting_offer['gym']              = $offer->gym;

        return response()->json([
            'status' => 1,
            'data' => $lifting_offer
        ], 200);
    }

    public function banners(Request $request)
    {
        $banners = Banners::getBanners();

        $lifting_banners = [];

        foreach ($banners as $banner) {

            $lifting_banner['id']               = $banner->id;
            $lifting_banner['module_id']        = $banner->module_id;
            $lifting_banner['module_type']      = $banner->module_type;
            $lifting_banner['banner_img']       = $banner->banner_img;
            $lifting_banner['sequence']         = $banner->sequence;
            $lifting_banner['start_datetime']   = $banner->start_date_time;
            $lifting_banner['end_datetime']     = $banner->end_date_time;

            $lifting_banners[] = $lifting_banner;
            unset($lifting_offer);
        }

        return response()->json([
            'status' => 1,
            'data' => $banners
        ], 200);
    }

    public function toc(Request $request)
    {
        $toc = Settings::where('name', 'toc')->first();

        if(!$toc) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $toc->value
        ], 200);
    }

    public function privacyPolicy(Request $request)
    {
        $privacy = Settings::where('name', 'privacy_policy')->first();

        if(!$privacy) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $privacy->value
        ], 200);
    }

    public function faq(Request $request)
    {
        $faq = Faq::where('status', 1)->get();

        if(!$faq) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $faq
        ], 200);
    }

    public function writeFeedback(Request $request)
    {
        $feedback = new Feedbacks;

        $request->request->add(['user_id' => $request->user()->id]);

        $feedback = $feedback->store($request);

        if( $feedback instanceof \App\Feedbacks ) {
            return response()->json([
                'message' => 'Successfully submitted feedback!',
                'status' => 1
            ], 201);
        }

        return response()->json([
            'message' => 'Successfully submitted feedback!',
            'status' => 1,
            'errors' => $feedback->errors()
        ], 401);
    }

    public function addNewsletter(Request $request)
    {
        $newsletter = new Newsletter();

        $newsletter = $newsletter->store($request);

        if( $newsletter instanceof \App\Newsletter ) {
            return response()->json([
                'message' => 'Successfully added newsletter!',
                'status' => 1
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong!',
            'status' => 0,
            'errors' => $newsletter->errors()
        ], 401);
    }
}
