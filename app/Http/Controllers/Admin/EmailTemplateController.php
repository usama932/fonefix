<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Session;
use Auth;

class EmailTemplateController extends Controller
{

    public function index()
    {
        $title = "Email Templates";
        return view('admin.email.index',compact('title'));
    }
    public function getMailings(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'description',
			6 => 'action'
		);

        $totalData = EmailTemplate::count();

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){

            $users = EmailTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = EmailTemplate::count();


		}else{

			$search = $request->input('search.value');
			if ($id == null){

                $users = EmailTemplate::offset($start)
                    ->limit($limit)
                    ->get();

                $totalFiltered = EmailTemplate::with('shop')->where([
                    ['name', 'like', "%{$search}%"],

                ])->count();
            }else{
                $users = EmailTemplate::where([
                    ['name', 'like', "%{$search}%"],
                ])->offset($start)
                    ->limit($limit);
                $totalFiltered = EmailTemplate::where([
                    ['name', 'like', "%{$search}%"],
                ])->count();
            }

		}

		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('mailings.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="templates[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['name'] = $r->name;
                $nestedData['subject'] = $r->subject;
                if($r->type == 1){
                  $type =  " Invoice";
                }
                elseif($r->type == 2){
                    $type =   "Welcome Message";
                }
                elseif($r->type == 3){
                    $type =  " Enquiries";
                }
                else{
                    $type =  " Not Assign";
                }
                $nestedData['type'] = $type;
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
                $user = Auth::user();
                if($user->role == 1){
                    $edit = 1;
                    $del = 1;
                    $view = 1;
                }elseif($user->role == 2){
                    $view = 1;
                    $edit = 1;
                    $del = 1;
                }elseif($user->role == 3){
                    $view = $user->permission->brand_view;
                    $edit = $user->permission->brand_edit;
                    $del = $user->permission->brand_delete;
                }
                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" name="View Template" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>';
                if(!$view){$view_link = '';}
                $edit_link = '<a name="Edit Template" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>';
                if(!$edit){$edit_link = '';}

                $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" name=\"Delete Template\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                if(!$del){$delete_link = '';}
                $nestedData['action'] = "
                                <div>
                                <td>
                                    $view_link
                                    $edit_link
                                    $delete_link
                                </td>
                                </div>
                            ";
				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);

		echo json_encode($json_data);
    }

    public function MailingDetail(Request $request)
    {
        $template = EmailTemplate::findOrFail($request->id);


		return view('admin.email.detail', ['title' => 'Email Template Detail', 'template' => $template]);
    }

    public function create()
    {
        $title = "Create Template";
        return view('admin.email.create',compact('title'));
    }


    public function store(Request $request)
    {
         //dd($request->all());
         $this->validate($request, [
		    'name' => 'required',
            'sms_template' => 'required',
            'type' => 'required',
            'subject' => 'required'
	    ]);
        $input = $request->all();
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }
        $invoice = EmailTemplate::create([
            'name' => $request->name,
            'description' => $request->sms_template,
            'subject' => $request->subject,
            'type' => $request->type,
            'user_id' => auth()->user()->id,
            'shared' => $shared
        ]);
        Session::flash('success_message', 'Great! Email Tempalate has been saved successfully!');

	    return redirect()->back();
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $title = 'Edit Email Template';
        $template =  EmailTemplate::find($id);
        //dd($template);
        return view('admin.email.edit',compact('template','title'));
    }


    public function update(Request $request, $id)
    {
          //dd($request->all());
        $this->validate($request, [
		    'name' => 'required',
            'sms_template' => 'required',
            'type' => 'required',
            'subject' => 'required'
	    ]);
        $input = $request->all();
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }
        $invoice = EmailTemplate::where('id',$id)->update([
            'name' => $request->name,
            'description' => $request->sms_template,
            'subject' => $request->subject,
            'type' => $request->type,
            'user_id' => auth()->user()->id,
            'shared' => $shared
        ]);
        Session::flash('success_message', 'Great! Email Tempalate has been saved successfully!');

        return redirect()->route('mailings.index');
    }


    public function destroy($id)
    {
        $template = EmailTemplate::find($id);
        $template->delete();
        Session::flash('success_message', 'template successfully deleted!');
        return redirect()->route('mailings.index');
    }
    public function deleteSelectedsms(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'templates' => 'required',

		]);
		foreach ($input['templates'] as $index => $id) {

			$template = EmailTemplate::find($id);
				$template->delete();

		}
		Session::flash('success_message', 'Template successfully deleted!');
		return redirect()->back();

	}
}
