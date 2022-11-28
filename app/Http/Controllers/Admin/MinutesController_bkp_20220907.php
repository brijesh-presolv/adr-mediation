<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Minute;
use App\Models\Minute_record_data;
use Illuminate\Http\Request;

use Storage;

class MinutesController extends Controller
{

    public function Minutes()
    {
        return view('admin.case.minutes');
    }

    public function MinutesAdd(Request $request)
    {
        if ($request->minutesName != null && $request->minutesType != null && $request->content && $request->minRep != null) {
            $evenName = str_replace(" ", "_", $request->minutesName);

            $data = [
                "minutes_name" => $request->minutesName,
                "type" => $request->minutesType,
                "event_name" => $evenName,
                "discription" => $request->content,
                "minutes_reports" => $request->minRep,
            ];

            $save = Minute::create($data);
            if (isset($save)) {
                return response()->json(["code" => 200, "response" => "success"]);
            } else {
                return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
            }
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Please Fill the Required filed"]);
        }
    }

    public function MinutesList()
    {
        $data = Minute::where(['is_deleted' => 0, 'minutes_reports' => 1])->get();

        $result = array();
        foreach ($data as $key => $value) {
            $result[] = [
                "key" => $key + 1,
                "data" => $value,
            ];
        }

        return response()->json(["data" => $result]);
    }

    public function ReportsList()
    {
        $data = Minute::where(['is_deleted' => 0, 'minutes_reports' => 2])->get();

        $result = array();
        foreach ($data as $key => $value) {
            $result[] = [
                "key" => $key + 1,
                "data" => $value,
            ];
        }

        return response()->json(["data" => $result]);
    }

    public function MinutesDelete(Request $request)
    {
        // dd($request->all());
        $data = Minute::find($request->id);
        $data->is_deleted = 1;
        if ($data->save()) {
            return response()->json(["code" => 200, "response" => "success"]);
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
        }
    }

    public function MinutesGetDataForEdit(Request $request)
    {
        // dd($request->all());
        $data = Minute::find($request->id);
        return $data;
    }

    public function MinutesForEditData(Request $request)
    {
        // dd($request->all());
        $data = Minute::find($request->minutesId);
        if (isset($data)) {
            $data->minutes_name = $request->editMinutesName;
            $data->type = $request->editMinutesType;
            $data->event_name = str_replace(" ", "_", $request->editMinutesName);
            $data->discription = $request->editContent;
            $data->minutes_reports = $request->editMinRep;
            if ($data->save()) {
                return response()->json(["code" => 200, "response" => "success"]);
            } else {
                return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
            }
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
        }
    }



    // Minutes insert : START //
    public function minuteOngoingAdd(Request $request)
    {
        $event_name = Minute::find($request->status);
       
        $data = [
            "case_id" => $request->case_id,
            "type" => $event_name->type,
            "event_name" => $event_name->event_name
        ];
        
        
        $save = Minute_record_data::create($data);
        //echo "<pre>";print_R($save);exit;
        
        if (isset($save)) {
            return response()->json(["code" => 200, "response" => "success", "type" => $event_name->type, 'last_insert_id' => $save->id, 'case_id' => $request->case_id]);
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
        }


    }



    public function minuteOngoingEdit(Request $request)
    {
        dd($request->all());
        $data = Minute_record_data::find($request->last_insert_id);

        $data->note_description = isset($request->note_value) ? $request->note_value : "" ;
        $data->no_of_days =  $request->days_value;
        $data->file_name =  $request->file_value;

        

         //echo "<pre>";print_R($request);exit;


        // File upload on s3 buket //
        //$selectDocument = $request->file_data;
        $ext = pathinfo($request->file_value, PATHINFO_EXTENSION);

        $filename = 'minute_document' . $request->case_id . time() . '.' . $ext;
        // dd($filename);
        $savePath = 'mediation_documents/mediation/' . $request->case_id . '/minutes/minuteDocument';
        $finalFilePath = $savePath . '/' . $filename;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $is_storage = Storage::disk('s3')->put($finalFilePath, file_get_contents($request->file_data));
        // File upload on s3 buket //

        var_dump($is_storage);
        exit;        
        if ($data->save()) {
            return response()->json(["code" => 200, "response" => "success"]);
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
        }


    }
    // Minutes insert : END //



    // Reports insert : START //
    // public function reportOngoingAdd(Request $request)
    // {
    //     $event_name = Minute::find($request->status);
       
    //     $data = [
    //         "case_id" => $request->case_id,
    //         "event_name" => $event_name->event_name
    //     ];

        
    //     $save = Minute_record_data::create($data);
    //     if (isset($save)) {
    //         return response()->json(["code" => 200, "response" => "success", 'type' => $request->case_id]);
    //     } else {
    //         return response()->json(["code" => 200, "response" => "error", "msg" => "Try Again"]);
    //     }


    // }
    // Reports insert : END //
}
