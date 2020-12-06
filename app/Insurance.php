<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Translatable\HasTranslations;
use Auth;
use Image;

class Insurance extends Model{
    use HasTranslations;
    
	protected $fillable = [
        'created_by', 
        'updated_by',
        'name', 
        'email', 
        'description', 
        'status', 
        'type', 
    ];

    protected static $logAttributes = [
        'created_by', 
        'updated_by',
        'name', 
        'email', 
        'description', 
        'status', 
        'type', 
    ];
    
	public $translatable = [
        'name', 
        'email', 
        'description'
    ];

    public $validationRules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'description' => 'required',
    ];

    public static function uploadFile($request, $name){
        if($request->hasFile($name)){
            $originalImage= $request->file($name);

            $thumbnailImage = Image::make($originalImage);

            $originalPath = public_path().'/uploads/';

            $file_name = time().$originalImage->getClientOriginalName();

            $thumbnailImage->fit(500, 500);

            $thumbnailImage->save($originalPath.$file_name);

            return $file_name;
        }
    }

    public function deleteImage($image_name){
        if($image_name == null)
            return false;

            $path = public_path('uploads/'.$image_name);
        
        if(file_exists($path)){
            unlink($path);

            return true;
        }

        return false;
    }

    public function store($request){
		$validationRules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'description' => 'required',
        ];

        if($validationRules){
            $validator = Validator::make($request->all(), $validationRules);

            if($validator->fails()){
                return $validator;
            }
        }

        if(!$this->id){
            $this->created_by = Auth::user()->id;
        }

        $this->updated_by = Auth::user()->id;
        $this->name = $request->name;
        $this->email = $request->email;
        $this->description = $request->description;
        $this->status = $request->status ? true : false;
        
        if($request->hasFile('profilePhoto')){
            if($this->profilePhoto != null){
                $this->deleteImage($this->profilePhoto);
            }

            $this->profilePhoto = $this->uploadFile($request, 'profilePhoto');
        }
        
        if($request->hasFile('coverPhoto')){
            if($this->coverPhoto != null){
                $this->deleteImage($this->coverPhoto);
            }

            $this->coverPhoto = $this->uploadFile($request, 'coverPhoto');
        }

        $this->save();

        return $this;
    }

    function insurances_plans(){
        return $this->hasMany(InsurancesPlans::class, 'insurance_id');
    }
}