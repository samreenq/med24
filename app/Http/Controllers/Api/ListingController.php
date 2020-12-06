<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Gym;
use App\User;
use App\Settings;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class ListingController extends Controller
{
    public function nearby(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('long');
        $distance = $request->get('distance') ? $request->get('distance') : 10000;

        if(!$lat || !$lng) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid Parameters'
            ], 401);
        }

        $nearby_listings = Gym::nearby($lat, $lng, $distance);

        $listings = [];

        $price_text = Settings::get_value('additional_charge_after_mins');
        $additional_price_text = Settings::get_value('additional_charge_loop_mins');

        foreach ($nearby_listings as $nearby_listing) {
            $ratings = DB::table('user_ratings')->where('gym_id', $nearby_listing->id)->avg('ratings');


            $listing['id']                      = $nearby_listing->id;
            $listing['name']                    = $nearby_listing->name;
            $listing['price']                   = $nearby_listing->get_price();
            $listing['additional_price']        = $nearby_listing->get_additional_price();
            $listing['price_text']              = ($price_text == 60) ? '/ 1st hour' : '1st ' . $price_text . ' mins';
            $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
            $listing['logo']                    = $nearby_listing->logo;
            $listing['banner']                  = $nearby_listing->banner;
            $listing['location']                = $nearby_listing->city->name . ', ' . $nearby_listing->town;
            $listing['gym_status']              = $nearby_listing->get_status();
            $listing['ratings']                 = ($ratings) ? $ratings - fmod($ratings, 0.5) : 0;

            $listings[] = $listing;
            unset($listing);
        }

        return response()->json([
            'status' => 1,
            'data' => $listings
        ], 200);
    }

    public function recommended(Request $request)
    {
        $city = $request->get('city');
        $country = $request->get('country');

        $recommended_listings = Gym::get_all_recommended($city, $country);

        $listings = [];
        $price_text = Settings::get_value('additional_charge_after_mins');
        $additional_price_text = Settings::get_value('additional_charge_loop_mins');

        foreach ($recommended_listings as $recommended_listing) {
            $ratings = DB::table('user_ratings')->where('gym_id', $recommended_listing->id)->avg('ratings');

            $listing['id']               = $recommended_listing->id;
            $listing['name']             = $recommended_listing->name;
            $listing['price']            = $recommended_listing->get_price();
            $listing['additional_price'] = $recommended_listing->get_additional_price();
            $listing['price_text']              = ($price_text == 60) ? '/ 1st hour' : '1st ' . $price_text . ' mins';
            $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
            $listing['logo']             = $recommended_listing->logo;
            $listing['banner']           = $recommended_listing->banner;
            $listing['location']         = $recommended_listing->city->name . ', ' . $recommended_listing->town;
            $listing['gym_status']       = $recommended_listing->get_status();
            $listing['ratings']          = ($ratings) ? $ratings - fmod($ratings, 0.5) : 0;

            $listings[] = $listing;
            unset($listing);
        }

        return response()->json([
            'status' => 1,
            'data' => $listings
        ], 200);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');


        if(!$query) {
            return response()->json([
                'message' => 'No Record Found',
                'status' => 0
            ], 401);
        }

        if(strlen($query) >= 3) {
            $search_results = Gym::search_listings($query);

            $listings = [];
            $price_text = Settings::get_value('additional_charge_after_mins');
            $additional_price_text = Settings::get_value('additional_charge_loop_mins');

            foreach ($search_results as $search_result) {
                $ratings = DB::table('user_ratings')->where('gym_id', $search_result->id)->avg('ratings');

                $listing['id']               = $search_result->id;
                $listing['name']             = $search_result->name;
                $listing['price']            = $search_result->get_price();
                $listing['additional_price'] = $search_result->get_additional_price();
                $listing['price_text']              = ($price_text == 60) ? '/ 1st hour' : '1st ' . $price_text . ' mins';
                $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
                $listing['location']         = $search_result->city->name . ', ' . $search_result->town;
                $listing['logo']             = $search_result->logo;
                $listing['banner']           = $search_result->banner;
                $listing['gym_status']       = $search_result->get_status();
                $listing['ratings']          = ($ratings) ? $ratings - fmod($ratings, 0.5) : 0;

                $listings[] = $listing;
                unset($listing);
            }

        } else {
            return response()->json([
                'message' => 'Query must be more than or equal to 3 characters',
                'status' => 0
            ], 401);
        }
        return response()->json([
            'status' => 1,
            'data' => $listings
        ], 200);
    }

    public function detail(Request $request, $listing_id)
    {
        $gym = Gym::detail($listing_id);
        $user_id = $request->get('user_id');
        $favourite = DB::table('user_favourites')->where('gym_id', $listing_id)->where('user_id', $user_id)->first();

        if(!$gym) {
            return response()->json([
                'message' => 'No Record Found'
            ], 401);
        }

        $price_text = Settings::get_value('additional_charge_after_mins');
        $additional_price_text = Settings::get_value('additional_charge_loop_mins');

        $listing['id']                = $gym->id;
        $listing['name']              = $gym->name;
        $listing['price']             = $gym->get_price();
        $listing['additional_price']  = $gym->get_additional_price();
        $listing['price_text']              = ($price_text == 60) ? '/ 1st hour' : '1st ' . $price_text . ' mins';
        $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
        $listing['latitude']          = $gym->latitude;
        $listing['longitude']         = $gym->longitude;
        $listing['description']       = $gym->description;
        $listing['logo']              = $gym->logo;
        $listing['banner']            = $gym->banner;
        $listing['gym_status']        = $gym->get_status();
        $listing['location']          = $gym->city->name . ', ' . $gym->town;
        $listing['address']           = $gym->location;
        $listing['ratings']           = ($gym->ratings) ? $gym->ratings - fmod($gym->ratings, 0.5) : 0;
        $listing['favourite']         = ($favourite) ? 1 : 0;

        foreach ($gym->amenities as $key => $amenity) {
            $listing['amenities'][$key]['name'] = $amenity->name;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach($days as $day) {
            $i = 0;

            $gym_classes = $gym->classes->where('day', $day);

            if(count($gym_classes) > 0) {
                foreach ($gym_classes as $key => $class) {

                    $listing['gym_classes'][$day][$i]['class_type']   = $class->class_type;
                    $listing['gym_classes'][$day][$i]['day']          = $class->day;
                    $listing['gym_classes'][$day][$i]['start_time']   = $class->start_time;
                    $listing['gym_classes'][$day][$i]['end_time']     = $class->end_time;

                    $i++;
                }
            } else {
                $listing['gym_classes'][$day] = [];
            }
        }

        foreach ($gym->timings as $key => $timings) {

            $listing['gym_timings'][$key]['day']            = $timings->day;
            $listing['gym_timings'][$key]['open_time']      = $timings->open_time;
            $listing['gym_timings'][$key]['close_time']     = $timings->close_time;
            $listing['gym_timings'][$key]['is_24hour']      = $timings->is_24hour;
            $listing['gym_timings'][$key]['24_hour_text']   = ($timings->is_24hour) ? 'Open 24 Hours' : '';
            $listing['gym_timings'][$key]['type']           = $timings->type;
        }
        
        foreach ($gym->images as $key => $image) {
            $listing['gym_images'][$key]['image']            = $image->image;
        }

        return response()->json([
            'status' => 1,
            'data' => $listing
        ], 200);
    }

    public function locations(Request $request)
    {
        $lat = $request->lat;
        $long = $request->long;
        $locations = Gym::locations();

        $listings = [];

        $price_text = Settings::get_value('additional_charge_after_mins');
        $additional_price_text = Settings::get_value('additional_charge_loop_mins');

        foreach ($locations as $location) {

            $ratings = DB::table('user_ratings')->where('gym_id', $location->id)->avg('ratings');

            $listing['id']               = $location->id;
            $listing['name']             = $location->name;
            $listing['price']            = $location->get_price();
            $listing['additional_price'] = $location->get_additional_price();
            $listing['price_text']              = ($price_text == 60) ? '/ 1st hour' : '1st ' . $price_text . ' mins';
            $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
            $listing['gym_location']     = $location->city->name . ', ' . $location->town;
            $listing['latitude']         = $location->latitude;
            $listing['longitude']        = $location->longitude;
            $listing['distance']         = ($lat && $long) ? $location->getDistance($lat, $long, $location->id) : 0;
            $listing['logo']             = $location->logo;
            $listing['banner']           = $location->banner;
            $listing['gym_status']       = $location->get_status();
            $listing['ratings']          = ($ratings) ? $ratings - fmod($ratings, 0.5) : 0;

            $listings[] = $listing;
            unset($listing);
        }

        if(count($listings) <= 0) {
            return response()->json([
                'message' => 'No Record Found'
            ], 401);
        }
        return response()->json([
            'status' => 1,
            'data' => $listings
        ], 200);
    }

    public function rateGym(Request $request)
    {
        $gym_id = $request->get('gym_id');
        $ratings = $request->get('ratings');
        $user = $request->user();

        if(!$gym_id || !$ratings || !$user) {
            return response()->json([
                'message' => 'Invalid Parameters'
            ], 401);
        }

        $listing = Gym::where('status', 1)->find($gym_id);

        if(!$listing) {
            return response()->json([
                'message' => 'No Record Found'
            ], 401);
        }

        $rate = $listing->ratings()->detach($user->id, ['ratings' => $ratings]);

        $rate = $listing->ratings()->attach($user->id, ['ratings' => $ratings]);

        return response()->json(['status' => 1, 'message' => 'Ratings Added Successfully']);
    }
}
