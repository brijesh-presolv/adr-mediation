<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;

class UploadController extends Controller 
{

    public function storeMultiFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'files.*'     => 'required',
            'files.*' => 'mimes:pdf',
        ]);

        if ($validator->fails()) {

            $result['success'] = false;
            $result['message'] = "Validation failed";
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }


        $inv_id = "";
        if ($request->has('docs_party_ids')) {
            $inv_id = $request->docs_party_ids;
        } else {
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
        }
        $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        if (isset($request->log_id) && isset($request->allcids)) {
            if ($request->log_id == "null" && $request->allcids != "") {
                $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                $log = BulkLog::create([
                    "selected_ids" => $params['allcids'],
                    "uploaded_by" => Auth::user()->id,
                    "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                    "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                $log_id = $log->id;
                if ($request->shareMediator == 1) {
                    Common_function::MedNotification($_POST['allcids'], "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, null);
                } else {
                    Common_function::MedNotification($_POST['allcids'], "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, null);
                }
            }
        } else {
            if ($request->shareMediator == 1) {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, $inv_id);
            }
        }

        if ($request->TotalFiles > 0) {

            $insert = array();
            $insert_manage = "";

            $previous_file_count = DB::table('manage_files')->where("case_id", "=", $request->caseId)->count();
            if($previous_file_count > 0){
                $f_count = $previous_file_count + 1;
            } else {
                $f_count = 1;
            }

            for ($x = 0; $x < $request->TotalFiles; $x++) {
                   
                if ($request->hasFile('files' . $x)) {
                    $file = $request->file('files' . $x);
                    $filename = "supportingdoc".($f_count)."_M" .sprintf('%06d', $request->caseId). "." . $file->extension();

                    if(strpos($file->getClientOriginalName(), $request->caseId) !== false){
                        $savePath = 'mediation_documents/mediation/' . $request->caseId . '/supportingDocument';
                        $finalFilePath = $savePath . '/' . $filename;
                        Storage::disk('s3')->put($finalFilePath, file_get_contents($file));
                        $insert[$x]['file_name'] = $filename;
                        $insert[$x]['access'] = $inv_id;
                        $insert[$x]['mediator_access'] = isset($request->shareMediator) ? $request->shareMediator : 1;
                        $insert[$x]['uploaded_by'] = Auth::user()->id;
                        $insert[$x]['case_id'] = $request->caseId;
                    } 
                }

                $f_count++;
            }
            if(!empty($insert)){
                $insert_manage = DB::table('manage_files')->insert($insert);
            }
            
            if (isset($insert_manage) && $insert_manage != "") {

                if(isset($request->allcids)) {
                    $is_bulk = 1;
                } else {
                    $is_bulk = 0;
                }
                $this->send_upload_file_party($request->caseId, $insert, $is_bulk);

                if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {

                    $success_log = BulkLog::find($request->log_id);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }

                    //return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);

                    $data['log_id'] = $_POST['log_id'];
                    $data['caseId'] = $request->caseId;

                    $result['success'] = true;
                    $result['message'] = "User registered successfully.";
                    $result['data'] = $data;
                    return response()->json($result, 201);

                } else if (isset($log_id)) {
                    $success_log = BulkLog::find($log_id);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    //return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->caseId]);

                    $data['log_id'] = $log_id;
                    $data['caseid'] = $request->caseId;

                    $result['success'] = true;
                    $result['message'] = "User registered successfully.";
                    $result['data'] = $data;
                    return response()->json($result, 200);
                } else {
                    //return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->caseId]);
                    
                    $data['caseid'] = $request->caseId;

                    $result['success'] = true;
                    $result['message'] = "User registered successfully.";
                    $result['data'] = $data;
                    return response()->json($result, 200);
                }
            } else {

                
                if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {
                    $faild_log = BulkLog::find($_POST['log_id']);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    //return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);

                    $data['log_id'] = $_POST['log_id'];
                    $data['caseid'] = $request->caseId;

                    $result['success'] = false;
                    $result['message'] = " Upoading failed.";
                    $result['error'] = "Docs not uploaded.";
                    $result['data'] = $data;
                    return response()->json($result, 200);

                } else if (isset($log_id)) {
                    $faild_log = BulkLog::find($log_id);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    //return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->caseId]);

                    $data['log_id'] = $log_id;
                    $data['caseid'] = $request->caseId;

                    $result['success'] = false;
                    $result['message'] = " Upoading failed.";
                    $result['error'] = "Docs not uploaded.";
                    $result['data'] = $data;
                } else {
                    //return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->caseId]);

                    $data['caseid'] = $request->caseId;

                    $result['success'] = false;
                    $result['message'] = " Upoading failed.";
                    $result['error'] = "Docs not uploaded.";
                    $result['data'] = $data;
                }
            }
        } else {
            if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {
                $faild_log = BulkLog::find($_POST['log_id']);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->caseId;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
            } else if (isset($log_id)) {
                $faild_log = BulkLog::find($log_id);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->caseId;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                //return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->caseId]);

                    $data['log_id'] = $log_id;
                    $data['caseid'] = $request->caseId;

                    $result['success'] = false;
                    $result['message'] = " Upoading failed.";
                    $result['error'] = "Docs not uploaded.";
                    $result['data'] = $data;
            } else {
                //return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->caseId]);

                    $data['caseid'] = $request->caseId;

                    $result['success'] = false;
                    $result['message'] = " Upoading failed.";
                    $result['error'] = "Docs not uploaded.";
                    $result['data'] = $data;
            }
        }
    }

}
