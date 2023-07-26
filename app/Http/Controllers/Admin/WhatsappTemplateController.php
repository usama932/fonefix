<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\WhatsappTemplate;
use App\Models\WTemplateUse;
use Auth;

class WhatsappTemplateController extends Controller
{

    public function index()
    {
        $title = "Whatsapp Templates";
        return view('admin.whatsapptemplate.index',compact('title'));
    }

    public function getTemplates(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'description',
			6 => 'action'
		);

        $totalData = WhatsappTemplate::count();

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

		if(empty($request->input('search.value'))){

            $users = WhatsappTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = WhatsappTemplate::count();


		}else{

			$search = $request->input('search.value');
			if ($id == null){

                $users = WhatsappTemplate::offset($start)
                    ->limit($limit)
                    ->get();

                $totalFiltered = WhatsappTemplate::with('shop')->where([
                    ['name', 'like', "%{$search}%"],

                ])->count();
            }else{
                $users = WhatsappTemplate::where([
                    ['name', 'like', "%{$search}%"],
                ])->offset($start)
                    ->limit($limit);
                $totalFiltered = WhatsappTemplate::where([
                    ['name', 'like', "%{$search}%"],
                ])->count();
            }

		}

		$data = array();

		if($users){
			foreach($users as $r){
				$edit_url = route('whatsapp.edit',$r->id);
                if($r->user_id != auth()->user()->id){
                    $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" disabled><span></span></label></td>';
                }
                else{
                    $nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="templates[]" value="'.$r->id.'"><span></span></label></td>';
                }

				$nestedData['name'] = $r->name;
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
                $nestedData['sms_type'] = $type;
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
                $used = '';
                if(auth()->user()->role == 2){
                    $used = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();view_used('.$r->id.');" title="Useds" href="javascript:void(0)">
                        <i class="icon-1x text-dark-50 flaticon-edit"></i>
                        </a>';
                }

                $view_link = '<a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" name="View Template" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>'.$used;
                if(!$view){$view_link = '';}
                $edit_link = '';
                $delete_link = '';
                if($r->user_id == auth()->user()->id){
                    $edit_link = '<a name="Edit Template" class="btn btn-sm btn-clean btn-icon"
                    href="'.$edit_url.'">
                    <i class="icon-1x text-dark-50 flaticon-edit"></i>
                    </a>';
                    if(!$edit){$edit_link = '';}

                    $delete_link = "<a class=\"btn btn-sm btn-clean btn-icon\" onclick=\"event.preventDefault();del('$r->id');\" name=\"Delete Template\" href=\"javascript:void(0)\">
                                        <i class=\"icon-1x text-dark-50 flaticon-delete\"></i>
                                    </a>";
                    if(!$del){$delete_link = '';}
                }

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

    public function templatesDetail(Request $request)
    {

        $template = WhatsappTemplate::findOrFail($request->id);


		return view('admin.whatsapptemplate.detail', ['title' => 'template Detail', 'template' => $template]);
    }
    public function create()
    {
        $title = "Create Template";
        return view('admin.whatsapptemplate.create',compact('title'));
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
		    'name' => 'required',
            'sms_template' => 'required',
            'type' => 'required'
	    ]);
        $input = $request->all();
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }

        $invoice = WhatsappTemplate::create([
            'name' => $request->name,
            'description' => $request->sms_template,
            'type' => $request->type,
            'user_id' => auth()->user()->id,
            'shared' => $shared,

        ]);
        Session::flash('success_message', 'Great! Sms Tempalate has been saved successfully!');

	    return redirect()->back();
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $title = 'Edit Whatsapp Template';
        $template =  WhatsappTemplate::find($id);
        return view('admin.whatsapptemplate.edit',compact('template','title'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
		    'name' => 'required',
            'sms_template' => 'required',
            'type' => 'required'
	    ]);
        $input = $request->all();
        $res = array_key_exists('shared', $input);
        if ($res == false) {
            $shared = 0;

        } else {
            $shared = 1;

        }
        $used = array_key_exists('used', $input);
        if ($used == false) {
            $used = 0;

        } else {
            $used = 1;

        }
        $invoice = WhatsappTemplate::where('id',$id)->update([
            'name' => $request->name,
            'description' => $request->sms_template,
            'type' => $request->type,
            'user_id' => auth()->user()->id,
            'shared' => $shared,
            'used' => $used,
        ]);
        Session::flash('success_message', 'Great! Sms Tempalate has been saved successfully!');

	    return redirect()->back();
    }


    public function destroy($id)
    {
        $template = WhatsappTemplate::find($id);
        $template->delete();
        Session::flash('success_message', 'template successfully deleted!');
        return redirect()->route('whatsapp.index');
    }
    public function deleteSelectedsms(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'templates' => 'required',

		]);
		foreach ($input['templates'] as $index => $id) {

			$template = WhatsappTemplate::find($id);
				$template->delete();

		}
		Session::flash('success_message', 'Template successfully deleted!');
		return redirect()->back();

	}
    public function getused(Request $request)
    {

        $template = WhatsappTemplate::with('used')->findOrFail($request->id);


		return view('admin.whatsapptemplate.assign_used', ['title' => 'template Use', 'template' => $template]);
    }
    public function assign_used(Request $request)
    {
        $input = $request->all();
        $used = array_key_exists('used', $input);

        if ($used == false) {
            $used = 0;

        } else {
            $used = 1;

        }
        $invoice = WTemplateUse::where('template_id',$request->id)->where('user_id',auth()->user()->id)->first();

        if(!empty($invoice)){

            $template = WTemplateUse::where('template_id',$request->id)->where('user_id',auth()->user()->id)->update([
                'user_id' => auth()->user()->id,
                'template_id'=> $request->id,
                'used' => $used
            ]);
        }
        else{
            $template = WTemplateUse::create([
                'user_id' => auth()->user()->id,
                'template_id'=> $request->id,
                'used' => $used
            ]);

        }
        Session::flash('success_message', 'Great! SmsTempalate has been Update successfully!');

        return redirect()->route('templates.index');
    }
}
