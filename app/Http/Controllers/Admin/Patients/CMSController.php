<?php
namespace App\Http\Controllers\Admin\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Privacy;
use App\Terms;
use App\Faqs;

class CMSController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }
	
	function privacy(){
        $data['menu_active'] = 'patient cms';
        $data['title'] = 'Privacy & Policy';
        $data['content'] = Privacy::where('type', 'patient')
                                  ->first();

        return view('admin.patient.cms.privacy', $data);
    }

    function editPrivacy(Request $request){
        $privacy = Privacy::where(['type' => 'patient'])->first();

        if(!$privacy){
            $privacy = new Privacy;
        }

        $request->request->add([
            'type' => 'patient',
        ]);

        $privacy = $privacy->store($request);

        if($privacy instanceof Privacy){
            return redirect()->route('admin.patient.cms.privacy')->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($privacy->errors());
    }

    function terms(){
        $data['menu_active'] = 'patient cms';
        $data['title'] = 'Terms & Conditions';
        $data['content'] = Terms::where('type', 'patient')
                                ->first();

        return view('admin.patient.cms.terms', $data);
    }

    function editTerms(Request $request){
        $terms = Terms::where(['type' => 'patient'])->first();

        if(!$terms){
            $terms = new Terms;
        }

        $request->request->add([
            'type' => 'patient',
        ]);

        $terms = $terms->store($request);

        if($terms instanceof Terms){
            return redirect()->route('admin.patient.cms.terms')->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($terms->errors());
    }

    function faqs(Request $request){
        $data['menu_active'] = 'cms';
        $data['title'] = 'Faqs';
        $faqs = Faqs::where('type', 'patient')
                                ->get(); 

        $data['faqs'] = isset($faqs) ? json_decode($faqs) : '';

        return view('admin.patient.cms.faqs', $data);
    }

    function editFaq(Request $request){
        $faqs = Faqs::where('type', 'patient')
                   ->where('id', $request->id)    
                   ->first();

        $message = "Records found";
        $data = $faqs;
        $status = 1;

        if(!$faqs){
            $message = "Something wen\'t wrong !!";
            $data = "";
            $status = 0;
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], 200);
    }

    function saveFaq(Request $request){
        $request->validate([
            'heading' => 'required|required_with:questions',
            'questions.*' => 'required|required_with:heading',
            'answers.*' => 'required|required_with:heading',
        ]);
        
        $faq = Faqs::where('type', 'patient')
                    ->where('id', $request->id) 
                    ->first();

        if(!$faq){
            $faq = new Faqs();

            $faq->created_by = \Auth::user()->id;
        }
        
        $data = array(
            'heading' => $request->heading,
            'questions' => $request->questions,
            'answers' => $request->answers,
        );

        $faq->updated_by = \Auth::user()->id;
        $faq->type = 'patient';
        $faq->faq = json_encode($data);

        $message = 'Faq\'s has been saved successfully.';
        $status = 1;

        if(!$faq->save()){
            $message = 'Something wen\t wrong !!';
            $status = 0;
        }

        return response()->json([
            'message' => $message,
            'status' => $status,
        ], 200);
    }

    function deleteFaq(Request $request){
        $request->validate([
            'id' => 'required|numeric',
        ]);

        $faqs = Faqs::where([
                        'type' => 'patient',
                        'id' => $request->id
                    ])
                    ->first();

        if(!$faqs){
            return redirect()->back()->with('error', "Something wen't wrong");
        }

        if(!$faqs->delete()){
            return redirect()->back()->with('error', "Something wen't wrong");
        }

        return redirect()->back()->with('success', 'Faq has been deleted successfully.');
    }
}