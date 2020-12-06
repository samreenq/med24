<?php
namespace App\Http\Controllers\Frontend;

use App\FamilyMember;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Http\Resources\FamilyMemberInfo;
use App\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use View;

Class FamilyMemberController extends WebController
{
    public $_data = [];

    public function __construct()
    {
    }

    public function addFamily()
    {
        $this->loggedInUser();
        $relations = Relation::where('status',1)->get();

        $relations_arr = [];
        foreach ($relations as $relation){
            $relations_arr[] = $relation;
        }

        $this->_data['relations'] = $relations_arr;
        return view('site.user.add-family',$this->_data);
    }

    public function addFamilyMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'relation' => 'required|string',
             ]);

        if ($validator->fails()) {
            return redirect('add-family')->withInput()->withErrors($validator->errors());
        }

        if($request->id){
            $family_member = FamilyMember::where('id',$request->id)
            ->where('patient_id',Auth::user()->id)
            ->first();
            }

        if (!isset($family_member))
        {
            $family_member = new FamilyMember();
        }

        $request->patient_id = Auth::guard('user')->user()->id;
        $save_record = $family_member->store($request);
        if($save_record){
            return redirect('list-family-member')->with('success','Family added successfully');
        }
         return redirect('add-family')->with('error','Something went wrong');
    }

    public function listing(Request $request)
    {
        $this->loggedInUser();
        $user_id = Auth::guard('user')->user()->id;

        $this->_data['family_members'] = FamilyMember::where('patient_id',$user_id)->get();

        return view('site.user.listing-family',$this->_data);
    }

    public function ajaxListing(Request $request)
    {
        $aColumns = array( 'first_name', 'last_name', 'relation', 'gender' );

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        $user_id = Auth::guard('user')->user()->id;
        $query = FamilyMember::where('patient_id',$user_id);

       // $sWhere = "";
        if ( $_GET['sSearch'] != "" )
        {
            $keyword = $_GET['sSearch'];
            $search_keys = $aColumns;
            $query->where(function ($query) use($keyword, $search_keys) {
                $query->where($search_keys[0], 'like', '%' . $keyword . '%')
                    ->orWhere($search_keys[1], 'like', '%' . $keyword . '%')
                    ->orWhere($search_keys[2], 'like', '%' . $keyword . '%')
                    ->orWhere($search_keys[3], 'like', '%' . $keyword . '%');
            });

        }

       //echo $query->toSql(); exit;
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
        {
            $query->offset($_GET['iDisplayStart'])
                ->limit($_GET['iDisplayLength']);
        }
       // echo $query->toSql();
        $family_members = $query->get();

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => 10,
            "iTotalDisplayRecords" => count($family_members),
            "aaData" => array()
        );

        $records = translateArray($family_members->toArray());

        foreach($records as $record)
        {
           $row = array(
               $record['first_name'],
               $record['last_name'],
               $record['relation'],
               $record['gender'],
           );
            $output['aaData'][] = $row;
        }

        echo json_encode($output);  exit;


    }

}
