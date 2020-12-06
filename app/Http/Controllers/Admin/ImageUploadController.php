<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    public function image(Request $request, $fileName)
    {
        if( $request->hasFile($fileName) ) {
            $path = $request->has('path') ? $request->get('path') : 'uploads';
            
            if( !is_dir($path) ) {
                mkdir($path, 0644, true);    
            }
            
            $validator = Validator::make($request->all(), [
                $fileName => 'mimes:jpeg,bmp,png,gif'
            ]);
            
            if ($validator->fails()) {
                $data['data']   = $validator;
                $data['status'] = 0;
            } else {
                
                $ext        = $request->file($fileName)->getClientOriginalExtension();
                $fileName   = md5(time() . uniqid()) . '.' . $ext;
                $request->file('image')->move($path, $fileName);
                
                $data['status']     = 1;
                $data['image_url']  = url($path . '/' . $fileName);
                $data['image_path'] = $path . '/' . $fileName;
            }
        } else {
            $data['data']   = ['error' => 'Please select a file to upload'];
            $data['status'] = 0;
        }
        return response()->json($data);
    }

    // public function fileDestroy(Request $request)
    // {
    //     $filename =  $request->get('filename');
    //     $path=public_path().'/uploads/'.$filename;
    //     if (file_exists($path)) {
    //         unlink($path);
    //     }
    //     return $filename;
    // }
}