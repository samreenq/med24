<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Settings;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class SettingsController extends Controller
{
    public function getAll(Request $request)
    {
        $query = Settings::get();

        if(!$query) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        $settings = [];

        foreach ($query as $row) {
            
            $setting['name']     = $row->name;
            $setting['value']   = $row->value;

            $settings[] = $setting;
            unset($setting);
        }

        return response()->json([
            'status' => 1,
            'data' => $settings
        ], 200); 
    }

    public function getSetting(Request $request, $parameter)
    {
        $setting = Settings::get_value($parameter);

        if(!$setting) {
            return response()->json([
                'status' => 0,
                'message' => 'No record found'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $setting
        ], 200); 
    }
}