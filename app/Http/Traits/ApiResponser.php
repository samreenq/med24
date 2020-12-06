<?php
namespace App\Http\Traits;
trait ApiResponser{
    protected function apiResponse($message='success',$status=1,$data=null,$status_code=200)
    {
        return response()->json(["status" => $status,  'api_status'=>$status_code, "message" => $message, "data" => $data]);
    }

    protected function apiSuccessResponse($message='success',$data=null,$status_code=200)
    {
        return response()->json(["status" => 1, 'api_status'=>$status_code, "message" => $message, "data" => $data]);
    }

    protected function apiErrorResponse($message='failed',$data=null,$status_code=200)
    {
        return response()->json(["status" => 0, 'api_status'=>$status_code,  "message" => $message, "data" => $data]);
    }

    public function apiDataResponse($data=null,$message='success',$status_code=200){
        if(isset($data) && count($data)>0){
            return response()->json(["status" => 1, 'api_status'=>$status_code,  "message" => $message, "data" => $data]);
        }else{
            return response()->json(["status" => 0,  'api_status'=>$status_code, "message" => "No data Found", "data" => null]);
        }
    }
}


