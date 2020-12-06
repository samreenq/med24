<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Vouchers extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'vouchers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $validationRules = [
        'name' => 'required|string|unique:offers',
        'slug' => 'required|string|unique:offers',
        'discount' => 'required|integer',
        'discount_unit' => 'required',
        'start_datetime' => 'required',
        'end_datetime' => 'required',
        'status' => 'required',
    ];

    protected $fillable = [
        'name', 'discount', 'discount_unit', 'start_datetime', 'end_datetime', 'status', 'count', 'usage_count', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'discount', 'discount_unit', 'start_datetime', 'end_datetime', 'status', 'count', 'usage_count', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Voucher has been {$eventName}";
    }

    public function gym()
    {
        return $this->belongsToMany('App\Gym', 'voucher_gyms', 'voucher_id', 'gym_id');
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'voucher_user', 'voucher_id', 'user_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public static function getVouchers($gym_id = null, $user_id = null)
    {
        $vouchers = Self::orderBy('id', 'desc')->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->where('status', 1);

        if($gym_id) {
            $vouchers = $vouchers->whereHas('gym', function ($q) use($gym_id) {
                $q->where('gym_id', $gym_id);
            });
        }

        if($user_id) {
            $vouchers = $vouchers->whereHas('user', function ($q) use($user_id) {
                $q->where('user_id', $user_id);
            });
        }

        return $vouchers->get();
    }

    public static function check_voucher($code, $gym_id, $user_id = '', $step = 1)
    {
    	$voucher = Self::with('gym', 'user')->where('name', $code)->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->where('status', 1)->first();

    	if($voucher) {
            if ($gym_id && count($voucher->gym) > 0) {
                if (count($voucher->gym->where('id', $gym_id)) <= 0) {
                    return false;
                }
            }

            if ($user_id && count($voucher->user) > 0) {
                if (count($voucher->user->where('id', $user_id)) <= 0) {
                    return false;
                }
            }

            if($step == 1) {
                if ($voucher->count > 0) {
                    if ($voucher->count <= $voucher->usage_count) {
                        return false;
                    }
                }
            }

            return $voucher;
        }
        return false;
    }

    public static function redeem_voucher($code)
    {
        $voucher = Self::where('name', $code)->where('status', 1)->first();

        if($voucher) {
            $voucher->usage_count += 1;
            $voucher->save();
            return true;
        }
        return false;
    }

    public function get_discount($price)
    {
        $discount = $this->discount;
        $discount_unit = $this->discount_unit;
        if($discount_unit == '%') {
            $discount = $price / 100 * $discount;
        }

        return $discount;
    }

    public function store($request, $user = [])
    {
        if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:offers,name,'.$this->id;
            $this->validationRules['slug'] = 'required|string|unique:offers,slug,'.$this->id;
        }

        if($request->get('gym_id')) {
            $this->validationRules['gym_id.*'] = 'exists:gym,id';
        }

        if($request->get('user_id')) {
            $this->validationRules['user_id.*'] = 'exists:users,id';
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        if($user) {
            $request->request->add(['created_by' => $user->id]);
            $request->request->add(['updated_by' => $user->id]);
        } else {
            $request->request->add(['created_by' => \Auth::user()->id]);
            $request->request->add(['updated_by' => \Auth::user()->id]);
        }

        $data = $request->all();
        $this->fill($data);
        $this->save();

        if(isset($data['gym_id']) && $data['gym_id']) {
            $this->gym()->sync($data['gym_id']);
        } else {
            $this->gym()->sync([]);
        }

        if(isset($data['user_id']) && $data['user_id']) {
            $this->user()->sync($data['user_id']);
        } else {
            $this->user()->sync([]);
        }

        return $this;
    }
}
