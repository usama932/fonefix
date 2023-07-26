<?php

namespace App\Http\Controllers\Api;

use App\Models\Status;
use App\Models\Brand;
use DB;
use Auth;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponser;

class StatusController extends ApiController
{
    use ApiResponser;

    public function addStatus(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'complete' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $data = new Status();
            $data->name = $request->name;
            $data->color = $request->color;
            $data->email_subject = $request->email_subject;
            $data->sms_template = $request->sms_template;
            $data->sms_type = $request->sms_type;
            $data->sms_peid = $request->sms_peid;
            $data->sms_template_id = $request->sms_template_id;
            $data->whatsapp_template = $request->whatsapp_template;
            $data->email_body = $request->email_body;
            $data->sort_order = $request->sort_order;
            $data->complete = $request->complete;
            if (auth()->user()->role == 2) {
                $data->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $data->user_id = auth()->user()->parent_id;
            }
            $data->save();
            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Status has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }


    }

    public function updateStatus(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'name' => 'required|max:55',
                    'complete' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }

            DB::beginTransaction();
            $data = Status::findOrFail($request->id);
            $data->name = $request->name;
            $data->color = $request->color;
            $data->email_subject = $request->email_subject;
            $data->sms_template = $request->sms_template;
            $data->sms_type = $request->sms_type;
            $data->sms_peid = $request->sms_peid;
            $data->sms_template_id = $request->sms_template_id;
            $data->whatsapp_template = $request->whatsapp_template;
            $data->email_body = $request->email_body;
            $data->sort_order = $request->sort_order;
            $data->complete = $request->complete;
            if (auth()->user()->role == 2) {
                $data->user_id = Auth::id();
            }
            if (auth()->user()->role == 3) {
                $data->user_id = auth()->user()->parent_id;
            }
            $data->save();

            DB::commit();
            return response([
                "data" => $data,
                'message' => "Great! Status has been saved successfully!",
                'error' => false
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }


    }

    public function deleteStatus(Request $request)
    {

        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'id' => 'required',
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            DB::beginTransaction();
            $id = $request->id;
            $data = Status::find($id);
            if (!$data) {
                return response([
                    'message' => "Status not Found",
                    'error' => true
                ], 200);
            }

            $data->delete();
            DB::commit();
            return response([
                'message' => "Status Deleted Successfully",
                'error' => false
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function export()
    {
        try {
            if (auth()->user()->role == 2) {
                $user_id = auth()->user()->id;
            } elseif (auth()->user()->role == 3) {
                $user_id = auth()->user()->parent_id;
            } elseif (auth()->user()->role == 1) {
                $user_id = auth()->user()->id;
            }
            $data = Status::where("user_id",$user_id)->get();
            (new FastExcel($data))->export("api-statuses$user_id.csv", function ($pass) {
                if($pass->complete == 1){
                    $complete = 'Yes';
                }else{
                    $complete = 'No';
                }
                return [
                    'Name' => $pass->name,
                    'Color' => $pass->color,
                    'Complete' => $complete,
                    'SMS Type' => $pass->sms_type,
                    'SMS PEID' => $pass->sms_peid,
                    'SMS Template ID' => $pass->sms_template_id,
                    'SMS Template' => $pass->sms_template,
                    'Whatsapp Template' => $pass->whatsapp_template,
                    'Email Subject' => $pass->email_subject,
                    'Email Body' => $pass->email_body,
                ];

            });
            $url = url("public/api-statuses$user_id.csv");
            return response()->json(['status' => true, 'data' => $url, 'message' => "Statuses Export successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }

    }

    public function importSave(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,txt,xlsx',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return $this->errorResponse($error, 200);
            }


            $file = $request->file('file');
            if ($request->hasFile('file')) {
                if ($request->file('file')->isValid()) {
                    $destinationPath = "uploads/users/";
                    $extension = $file->getClientOriginalExtension('file');
                    $fileName = $file->getClientOriginalName('file'); // renameing image
                    $request->file('file')->move($destinationPath, $fileName);
                    $readFile = $destinationPath . $fileName;
//                $organization = auth()->user()->id;
//                $request->session()->put('organization', $organization);
                    $wfts = (new FastExcel)->import($readFile, function ($line) {
                        if (auth()->user()->role == 2) {
                            $user_id = auth()->user()->id;
                        } elseif (auth()->user()->role == 3) {
                            $user_id = auth()->user()->parent_id;
                        } else {
                            $user_id = auth()->user()->id;
                        }
                        $user = Status::where([["name",$line['Name']],["user_id",$user_id]])->first();
                        if (!$user){
                            $user = new Status();
                        }
                        if ($line['Complete'] == 'Yes'){
                            $user->complete = 1;
                        }else{
                            $user->complete = 0;
                        }
                        $user->name = $line['Name'];
                        $user->color = $line['Color'];
                        $user->sms_type = $line['SMS Type'];
                        $user->sms_peid = $line['SMS PEID'];
                        $user->sms_template_id = $line['SMS Template ID'];
                        $user->sms_template = $line['SMS Template'];
                        $user->whatsapp_template = $line['Whatsapp Template'];
                        $user->email_subject = $line['Email Subject'];
                        $user->email_body = $line['Email Body'];
                        $user->user_id = $user_id;
                        return $user->save();
                    });

//                Excel::import(new WftsImport, $readFile);
                }
            }
            // return $data;
            // return $data;
            return response()->json(['status' => true, 'data' => [], 'message' => "Statuses Import successfully", 'error' => false], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => true], 200);
        }
    }


    public function getStatus(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Status::orderBy('statuses.id', "Desc")
                    ->select(
                        'statuses.*'
//                        'statuses.name as Status_name'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Status::orderBy('id', "Desc")
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Status::orderBy('statuses.id', "Desc")->where("statuses.user_id", $user->id)
                    ->select(
                        'statuses.*'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Status::orderBy('id', "Desc")->where("user_id", $user->id)
                    ->get()->count();
            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Status::orderBy('statuses.id', "Desc")->where("statuses.user_id", $user->parent_id)
                    ->select(
                        'statuses.*'
                    )
                    ->skip($request->offset)->take(30)
                    ->get();


                $data_count = Status::orderBy('id', "Desc")->where("user_id", $user->parent_id)
                    ->get()->count();
            }
            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'count' => $data_count,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }
    }

    public function searchStatus(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->is_admin == 1 && $user->role == 1) {
                $data = Status::orderBy('statuses.id', "Desc")
                    ->where([['statuses.name', 'like', "%{$request->keyword}%"]])
                    ->select(
                        'statuses.*'
                    )
                    ->get();

            } else if ($user->is_admin == 1 && $user->role == 2) {

                $data = Status::orderBy('statuses.id', "Desc")->where("statuses.user_id", $user->id)
                    ->where([['statuses.name', 'like', "%{$request->keyword}%"], ["statuses.user_id", $user->id]])
                    ->select(
                        'statuses.*'
                    )
                    ->get();


            } else if ($user->is_admin == 1 && $user->role == 3) {

                $data = Job::orderBy('statuses.id', "Desc")->where("statuses.user_id", $user->parent_id)
                    ->where([['statuses.name', 'like', "%{$request->keyword}%"], ["statuses.user_id", $user->parent_id]])
                    ->select(
                        'statuses.*'
                    )
                    ->get();

            }
            if ($data->isNotEmpty()) {
                return response([
                    'data' => $data,
                    'message' => "Records",
                    'error' => false
                ], 200);
            } else {
                return response([
                    'message' => "Records Not Found",
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response([

                'message' => $e,
                'error' => true
            ], 200);
        }
    }

    public function getStatusDetailInfo(Request $request)
    {
        try {
            $input = $request->all();
            $validatedData = Validator::make(
                $input,
                array(
                    'Status_id' => 'required'
                ));

            if ($validatedData->fails()) {
                $error = $validatedData->errors()->first();
                return $this->errorResponse($error, 200);
            }
            return response([
                "data" => $this->getStatusDetail($request->Status_id),
                'message' => "shop enquires",
                'error' => false
            ], 200);

        } catch (Exception $e) {

        }
    }




}
