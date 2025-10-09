<?php

namespace App\Http\Controllers\API\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common_function;
use App\Models\User;
use App\Models\MedCase;
use App\Models\Mediation_Details;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\ConsentDisclosures;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use App\Models\SupportingDocument;
use App\Models\InvitationFiles;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Http\Traits\UploadTrait;
use App\Models\BulkLog;
use App\Models\Mediators_mediation_cases_status;
use App\Models\Notification;
use App\Models\WaTemplate;
use DB;
use Illuminate\Support\Facades\File;
use PDF;
use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Zoom;

use Carbon\Carbon;

class DashboardController extends Controller
{

    use UploadTrait;

    
    public function getNotifications()
    {
        $userId = 264;
        $view = Notification::where('view_mediator', 0)->where('mediator_id', $userId)->get();

        foreach ($view as $item) {
            $item->view_mediator = 1;
            $item->save();
        }
        $data = Notification::mediatornotificationDataAPI($userId);
        
        $result['success'] = true;
        $result['message'] = "User notifications fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }
   
}
