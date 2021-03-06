<?php
namespace App\Http\Controllers\Admin\Doctors;
use App\Http\Controllers\Controller;
use App\Options;
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
        $data['menu_active'] = 'cms';
        $data['title'] = 'Privacy & Policy';
        $data['content'] = Privacy::where('type', 'doctor')
                                  ->first();

        return view('admin.doctor.cms.privacy', $data);
    }

    function editPrivacy(Request $request){
        $privacy = Privacy::where(['type' => 'doctor'])->first();

        if(!$privacy){
            $privacy = new Privacy;
        }

        $request->request->add([
            'type' => 'doctor',
        ]);

        $privacy = $privacy->store($request);

        if($privacy instanceof Privacy){
            return redirect()->route('admin.doctor.cms.privacy')->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($privacy->errors());
    }

    function terms(){
        $data['menu_active'] = 'cms';
        $data['title'] = 'Terms & Conditions';
        $data['content'] = Terms::where('type', 'doctor')
                                ->first();

        return view('admin.doctor.cms.terms', $data);
    }

    function editTerms(Request $request){
        $terms = Terms::where(['type' => 'doctor'])->first();

        if(!$terms){
            $terms = new Terms;
        }

        $request->request->add([
            'type' => 'doctor',
        ]);

        $terms = $terms->store($request);

        if($terms instanceof Terms){
            return redirect()->route('admin.doctor.cms.terms')->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($terms->errors());
    }

    function faqs(Request $request){
        $data['menu_active'] = 'cms';
        $data['title'] = 'Faqs';
        $faqs = Faqs::where('type', 'doctor')
                                ->get();

        $data['faqs'] = isset($faqs) ? json_decode($faqs) : '';

        return view('admin.doctor.cms.faqs', $data);
    }

    function editFaq(Request $request){
        $faqs = Faqs::where('type', 'doctor')
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

        $faq = Faqs::where('type', 'doctor')
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
        $faq->type = 'doctor';
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
                        'type' => 'doctor',
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

    function home(){
        $data['menu_active'] = 'home';
        $data['title'] = 'Home';
       // $data['content'] = Terms::where('type', 'doctor') ->first();

        $options_model = new Options();
        $options = $options_model->where('page','home')->get();

        $return = array();
        if(count($options)>0){
            foreach($options as $record){

                $option = $record['option_name'];
                $return[$record['option_name']] = $record['option_value'];
            }
            $return = json_decode(json_encode($return));
        }

        $data['content'] = $return;

        return view('admin.doctor.cms.home', $data);
    }

    function saveHome(Request $request)
    {
        $option_model = new Options();
        $option_model->updateValue('search_title',$request->search_title);
        $option_model->updateValue('search_description',$request->search_description);
        $option_model->updateValue('search_description2',$request->search_description2);
        $option_model->updateValue('search_title3',$request->search_title3);
        $option_model->updateValue('search_description3',$request->search_description3);
        $option_model->updateValue('search_title4',$request->search_title4);
        $option_model->updateValue('search_description4',$request->search_description4);
        $option_model->updateValue('logo_title',$request->logo_title);
        $option_model->updateValue('logo_description',$request->logo_description);
        $option_model->updateValue('download_title',$request->download_title);
        $option_model->updateValue('download_description',$request->download_description);

        return redirect()->back()->with('success', 'Home content has been saved successfully.');
    }
}
