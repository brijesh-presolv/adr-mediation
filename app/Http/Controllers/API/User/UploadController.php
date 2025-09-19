<?php

namespace App\Http\Controllers\API\User;

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
use App\Models\Mediators_mediation_cases_status;
use App\Http\Helpers\Common_function;
use App\Models\SupportingDocument;
use App\Models\ConsentDisclosures;
use App\Models\InvitationFiles;
use App\Http\Helpers\SendGrid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UploadController extends Controller 
{

    public function documentUpload(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'caseId' => 'required|integer',
                'fileupload' => 'required|file|mimes:pdf,zip,rar|max:20480'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $caseId = $request->input('caseId');
            $selectDocument = $request->file('fileupload');
            $errormsg = '';

            $med = MedCase::find($caseId);

            if (!$med) {

                $result['success'] = false;
                $result['message'] = "Case details not found. Please try again.";
                $result['error']   = "File upload failed.";
                return response()->json($result, 400);
            }

            $ext = pathinfo($selectDocument->getClientOriginalName(), PATHINFO_EXTENSION);
            $originalFileName = $selectDocument->getClientOriginalName();

            if ($ext != 'pdf' && $ext != 'zip' && $ext != 'rar') {

                $errormsg .= 'Please upload pdf, rar and zip file';

                $result['success'] = false;
                $result['message'] = $errormsg;
                $result['error'] = "Files not uploaded";
                return response()->json($result, 400);

            } else {

                $filename = $originalFileName . '_supporting_document_' . $med->id . date("YmdHis") . '.' . $selectDocument->getClientOriginalExtension();
                $savePath = 'mediation_documents/mediation/' . $med->id . '/user/supportingDocument';
                $finalFilePath = $savePath . '/' . $filename;
                Storage::disk('s3')->put($finalFilePath, file_get_contents($selectDocument));
                $med->documentPath = $filename;
                $med->save();

                $data['caseid']=$caseId;

                $result['success'] = true;
                $result['message'] = "Files uploaded successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);
            }

        $result['success'] = false;
        $result['message'] = "Files could not be uploaded. Please try again.";
        $result['error']   = "File upload failed";
        return response()->json($result, 400);


        } catch (\Exception $e) {

            $result['success'] = false;
            $result['message'] = "Files could not be uploaded. Please try again.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

}
