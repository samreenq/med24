<?php
namespace App\Http\Controllers\Admin\Patients;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Relation;
use Auth;

class RelationsController extends Controller
{
	public function index(Request $request)
	{
		$data['menu_active'] = 'relation';
        $data['title'] = 'Relations';
		$data['records'] = Relation::all();
		
		return view('admin.patient.familyMembers.relations.index', $data);
	}

	public function add(Request $request)
	{
		$data['menu_active'] = 'relation';
		$data['title'] = 'Add Relation';
		$data['record'] = Relation::find($request->id);

		return view('admin.patient.familyMembers.relations.add', $data);
	}

	public function save(Request $request)
	{
		$record = new Relation();
		$record->store($request);

		$status = 'error';
		$message = 'Something wen\'t wrong';

		if ($record instanceof Relation) 
		{
			$status = 'success';
			$message = 'Record has been saved successfully';
		}

		return redirect()->back()->with($status, $message)->withInput();
	}

	public function delete(Request $request)
	{
		$record = Relation::find($request->id);

		$status = 'success';
		$message = 'Record has been deleted successfully';

		if (!$record)
		{
			$status = 'error';
			$message = 'Something wen\'t wrong';
		}

		if ($status == 'success')
		{
			if (!$record->delete()) 
			{
				$status = 'error';
				$message = 'Something wen\'t wrong';		
			}
		}

		return redirect()->back()->with($status, $message);
	}
}