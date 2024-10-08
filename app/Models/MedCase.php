<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedCase extends Model
{
    use HasFactory;

    protected $table = 'mediation_case';
    protected $fillable = ['userid', 'disputeCategory', 'natureOfAgreement', 'agreementDate', 
    'noOfParties', 'amount', 'proposedSolution', 'issue', 'confirm_status', 'case_status', 'documentPath', 'withdraw', 'otherRespondentDetails', 'request_letter', 'batch_id', 'ref_id', 'bulk_flag', 'discussion', 'poc_name', 'poc_email', 'poc_contact', 'itm_lang', 'payToken', 'PayLink', 'PayLinkExpire', 
    'restructure_offer_1', 'restructure_offer_2', 'restructure_offer_3', 'stop_itm_ip', 'stop_itm_rp', 'stop_itm_med', 
    'stop_close_ip', 'stop_close_rp', 'stop_close_med', 'sub_user_id'];


    public function user_involed()
    {
        return $this->hasMany(InvoledUser::class, 'userPlanId', 'id');
    }

    public function invitation_file()
    {
        return $this->hasMany(InvitationFiles::class, 'case_id', 'id');
    }

    public function consent_disclosures()
    {
        return $this->hasMany(ConsentDisclosures::class, 'mediation_case_id', 'id');
    }

    public function supporting_docs()
    {
        return $this->hasMany(SupportingDocument::class, 'case_id', 'id');
    }

    public function document_settlements()
    {
        return $this->hasMany(DocumentSettlement::class, 'mediation_case_id', 'id');
    }

    static function getCase($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id = "", $bulk)
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%")
                        ->orWhere('batch.batch_name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('mediation_case.ref_id', 'LIKE', "%{$searchValue}%")
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        // $subQuery = DB::table('user_involved_in_agreement')
        // ->join('mediation_case', DB::raw('mediation_case.id'), '=', DB::raw('user_involved_in_agreement.userPlanid'))
        // ->skip(0)
        // ->take(1);
        $sql->select("mediation_case.id", "mediation_case.batch_id", "mediation_case.ref_id", "mediation_case.created_at", "mediation_case.confirm_status", "mediation_case.bulk_flag", "mediation_case.sub_user_id", DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator_username"), "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status", "mediators_mediation_cases_status.created_at as admin_approve", "consent_disclosures.updated_at as update", "consent_disclosures.created_at as create", "batch.batch_name")
            ->leftJoin("mediators_mediation_cases_status", function ($join) {
                $join->on("mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id");
                $join->where("mediators_mediation_cases_status.id", "=", DB::raw("(select max(`mediators_mediation_cases_status2`.`id`) from mediators_mediation_cases_status as mediators_mediation_cases_status2 Where `mediators_mediation_cases_status2`.`mediation_case_id`=`mediation_case`.`id`)"));
            })
            ->leftJoin("consent_disclosures", "consent_disclosures.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->leftJoin("batch", "batch.id", "=", "mediation_case.batch_id")
            // ->leftJoin("user_involved_in_agreement", "user_involved_in_agreement.userPlanid", "=", "mediation_case.id")
            ->where("mediation_case.confirm_status", "=", $role)
            ->where("mediation_case.bulk_flag", "=", $bulk);


        if ($columnName == "case.id" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.id', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.created_at', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'desc') {
            $sql->orderBy('mediation_case.created_at', 'DESC');
        } else if ($columnName == "party" && $columnSortOrder == 'acs') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'ASC');
            });
        } else if ($columnName == "party" && $columnSortOrder == 'desc') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'DESC');
            });
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'asc') {
            $sql->orderBy('users.first_name', 'ASC');
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'desc') {
            $sql->orderBy('users.first_name', 'DESC');
        } else {
            $sql->orderBy('mediation_case.id', 'DESC');
        }

        $cases = $sql->skip($row)
            ->take($rowperpage)->get();
        return $cases;
    }

    static function getCaseCount($searchValue, $role, $batch_id = "", $bulk)
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        $cases = $sql->leftJoin("mediators_mediation_cases_status", function ($join) {
            $join->on("mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id");
            $join->where("mediators_mediation_cases_status.id", "=", DB::raw("(select max(`mediators_mediation_cases_status2`.`id`) from mediators_mediation_cases_status as mediators_mediation_cases_status2 Where `mediators_mediation_cases_status2`.`mediation_case_id`=`mediation_case`.`id`)"));
        })
            ->leftJoin("consent_disclosures", "consent_disclosures.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")

            ->where("mediation_case.confirm_status", "=", $role)
            ->where("mediation_case.bulk_flag", "=", $bulk)
            ->orderBy('mediation_case.id', 'DESC')
            ->count();
        return $cases;
    }

    static function newrequestDataMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $loginUser)
    {
        $sql = MedCase::with('user_involed');

        // if ($searchValue != '') {
        //     $uidSearch = ltrim($searchValue, "M0");
        //     if(empty(date_parse($searchValue)['errors'])) {
        //         $searchValue = new DateTime($searchValue);
        //         $searchValue = $searchValue->format('Y-m-d');
        //     }
        //     $sql->where(function ($query) use ($searchValue, $uidSearch) {
        //         // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
        //         $query->orWhere('mediation_case.id', 'LIKE', "{$uidSearch}%")
        //             ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
        //     });
        // }

        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->whereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }

        //->select('mediation_case.*')
        $cases = $sql->join('mediators_mediation_cases_status', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 0])
            ->where("mediation_case.confirm_status", "!=", 2)->orderBy('mediation_case.id', 'DESC')
            ->skip($row)
            ->take($rowperpage)->get();

        return $cases;
    }

    static function newrequestDataMediatorCount($searchValue, $loginUser)
    {
        $sql = MedCase::with('user_involed');

        // if ($searchValue != '') {
        //     $uidSearch = ltrim($searchValue, "M0");
        //     if(empty(date_parse($searchValue)['errors'])) {
        //         $searchValue = new DateTime($searchValue);
        //         $searchValue = $searchValue->format('Y-m-d');
        //     }
        //     $sql->where(function ($query) use ($searchValue, $uidSearch) {
        //         // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
        //         $query->orWhere('mediation_case.id', 'LIKE', "{$uidSearch}%")
        //             ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
        //     });
        // }

        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->whereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        //->select('mediation_case.*')
        $cases = $sql->join('mediators_mediation_cases_status', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 0])
            ->where("mediation_case.confirm_status", "!=", 2)->orderBy('mediation_case.id', 'DESC')
            ->count();

        return $cases;
    }

    static function getOngoingMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage)
    {
        // $cases = DB::table('mediators_mediation_cases_status')
        // ->select("mediation_case.*")
        // ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
        // ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
        // ->where('confirm_status', "=", 1)
        // ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')->get();

        $sql = DB::table('mediators_mediation_cases_status');
        if ($searchValue != '') {
            $uidSearch = ltrim($searchValue, "M0");
            if(empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
            }
            $sql->where(function ($query) use ($searchValue, $uidSearch) {
                // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                $query->orWhere('mediation_case.id', 'LIKE', "%{$uidSearch}%")
                    ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
            });
        }
        $cases = $sql->select("mediation_case.*")
            ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
            ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where('confirm_status', "=", 1)
            ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')
            ->skip($row)
            ->take($rowperpage)->get();

        return $cases;
    }

    static function getOngoingMediatorCount($searchValue)
    {
        // $cases = DB::table('mediators_mediation_cases_status')
        // ->select("mediation_case.*")
        // ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
        // ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
        // ->where('confirm_status', "=", 1)
        // ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')->get();

        $sql = DB::table('mediators_mediation_cases_status');
        if ($searchValue != '') {
            $uidSearch = ltrim($searchValue, "M0");
            if(empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
            }
            $sql->where(function ($query) use ($searchValue, $uidSearch) {
                // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                $query->orWhere('mediation_case.id', 'LIKE', "%{$uidSearch}%")
                    ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
            });
        }
        $cases = $sql
            ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
            ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where('confirm_status', "=", 1)
            ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')
            ->count();

        return $cases;
    }

    static function getClosedMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role)
    {
        // $cases = DB::table('mediators_mediation_cases_status')
        // ->select("mediation_case.*")
        // ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
        // ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
        // ->where('confirm_status', "=", 1)
        // ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')->get();

        // $sql = DB::table('mediation_case');
        $sql = MedCase::with('user_involed');

        // if ($searchValue != '') {
        //     $uidSearch = ltrim($searchValue, "M0");
        //     if(empty(date_parse($searchValue)['errors'])) {
        //         $searchValue = new DateTime($searchValue);
        //         $searchValue = $searchValue->format('Y-m-d');
        //     }
        //     $sql->where(function ($query) use ($searchValue, $uidSearch) {
        //         // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
        //         $query->orWhere('mediation_case.id', 'LIKE', "{$uidSearch}%")
        //             ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
        //     });
        // }

        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors'])&& date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->whereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }

        $cases = $sql->select("mediation_case.*", "users.username as mediator_username", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediation_case.confirm_status", "=", $role)
            ->where('mediator_id', "=", Auth::user()->id)->orderBy('mediation_case.id', 'DESC')
            ->skip($row)
            ->take($rowperpage)->get();

        return $cases;
    }

    static function getClosedMediatorCount($searchValue, $role)
    {
        // $cases = DB::table('mediators_mediation_cases_status')
        // ->select("mediation_case.*")
        // ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
        // ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
        // ->where('confirm_status', "=", 1)
        // ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')->get();

        // $sql = DB::table('mediation_case');
        $sql = MedCase::with('user_involed');

        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->whereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        $cases = $sql->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediation_case.confirm_status", "=", $role)
            ->where('mediator_id', "=", Auth::user()->id)->orderBy('mediation_case.id', 'DESC')
            ->count();

        return $cases;
    }

    static function getCaseOngoingUser($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id)
    {
        
          if ($batch_id != "") {
              $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
          } else {
            $sql = MedCase::with('user_involed');
         }
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                    //->orWhere('batch.batch_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('mediation_case.ref_id', 'LIKE', "%{$searchValue}%")
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        
       
            $sql->select('user_involved_in_agreement.userid', 'user_involved_in_agreement.userPlanId', 'mediation_case.sub_user_id', 'mediation_case.id as caseid','mediation_case.withdraw', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create", "mediation_case.batch_id as batch_id", "mediation_case.ref_id as ref_id")
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => $role])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            //->leftJoin("batch", "batch.id", "=", "mediation_case.batch_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId');
            // ->orderby('mediation_case.id', 'DESC')
            // ->get();
        
        

        
        if ($columnName == "case.caseid" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.id', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.created_at', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'desc') {
            $sql->orderBy('mediation_case.created_at', 'DESC');
        } else if ($columnName == "party" && $columnSortOrder == 'acs') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'ASC');
            });
        } else if ($columnName == "party" && $columnSortOrder == 'desc') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'DESC');
            });
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'asc') {
            $sql->orderBy('users.first_name', 'ASC');
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'desc') {
            $sql->orderBy('users.first_name', 'DESC');
        } else {
            $sql->orderBy('mediation_case.id', 'DESC');
        }

        $cases = $sql->skip($row)
            ->take($rowperpage)->get();
        return $cases;
    }


    static function getCaseOngoingUser_sub($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id, $parent)
    {
        
          if ($batch_id != "") {
              $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
          } else {
            $sql = MedCase::with('user_involed');
         }
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                    //->orWhere('batch.batch_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('mediation_case.ref_id', 'LIKE', "%{$searchValue}%")
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        
       
            $sql->select('user_involved_in_agreement.userid', 'user_involved_in_agreement.userPlanId', 'mediation_case.id as caseid', 'mediation_case.sub_user_id', 'mediation_case.withdraw', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create", "mediation_case.batch_id as batch_id", "mediation_case.ref_id as ref_id")
            ->where(['user_involved_in_agreement.userId' => $parent, 'mediation_case.confirm_status' => $role, 'mediation_case.sub_user_id' => Auth::user()->id])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            //->leftJoin("batch", "batch.id", "=", "mediation_case.batch_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId');
            // ->orderby('mediation_case.id', 'DESC')
            // ->get();
        
        

        
        if ($columnName == "case.caseid" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.id', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.created_at', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'desc') {
            $sql->orderBy('mediation_case.created_at', 'DESC');
        } else if ($columnName == "party" && $columnSortOrder == 'acs') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'ASC');
            });
        } else if ($columnName == "party" && $columnSortOrder == 'desc') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'DESC');
            });
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'asc') {
            $sql->orderBy('users.first_name', 'ASC');
        } else if ($columnName == "case.mediator_username" && $columnSortOrder == 'desc') {
            $sql->orderBy('users.first_name', 'DESC');
        } else {
            $sql->orderBy('mediation_case.id', 'DESC');
        }

        $cases = $sql->skip($row)
            ->take($rowperpage)->get();
        return $cases;
    }
    
    static function getCaseCountOngoingUser($searchValue, $role, $batch_id = "")
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        $cases = $sql->select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.sub_user_id', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create")
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => $role])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->count();
        
        return $cases;
    }




    static function getCaseCountOngoingUser_sub($searchValue, $role, $batch_id = "", $parent)
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        $cases = $sql->select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.sub_user_id', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create")
            ->where(['user_involved_in_agreement.userId' => $parent, 'mediation_case.confirm_status' => $role, 'mediation_case.sub_user_id' => Auth::user()->id])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->count();
        
        return $cases;
    }



    static function getCaseNewReqUser($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $parent)
    {
        $sql = MedCase::with('user_involed');
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        $sql->where(['mediation_case.sub_user_id' => Auth::user()->id, 'mediation_case.confirm_status' => 0])
            ->orWhere('user_involved_in_agreement.userId', $parent)
            
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId');
            // ->orderby('mediation_case.id', 'DESC')
            // ->get();


           
            
        
        if ($columnName == "case.caseid" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.id', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.created_at', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'desc') {
            $sql->orderBy('mediation_case.created_at', 'DESC');
        } else if ($columnName == "party" && $columnSortOrder == 'acs') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'ASC');
            });
        } else if ($columnName == "party" && $columnSortOrder == 'desc') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'DESC');
            });
        } else {
            $sql->orderBy('mediation_case.id', 'DESC');
        }

        $cases = $sql->skip($row)
            ->take($rowperpage)->get();

           // dd($cases);
        return $cases;
    }
    
    static function getCaseCountNewReqUser($searchValue)
    {
        $sql = MedCase::with('user_involed');
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        $cases = $sql->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 0])
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->count();

            
        
        return $cases;
    }


    // Added for user batch dropdown //
    static function getCaseOngoingUserBatch($id){
        $sql = MedCase::with('user_involed');
        $sql->select('user_involved_in_agreement.*', 'mediation_case.id as caseid','mediation_case.withdraw', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create", "mediation_case.batch_id as batch_id", "mediation_case.ref_id as ref_id")
            ->where(['user_involved_in_agreement.userid' => $id, 'mediation_case.confirm_status' => 1])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            //->leftJoin("batch", "batch.id", "=", "mediation_case.batch_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId');
            // ->orderby('mediation_case.id', 'DESC')
            // ->get();

        $cases = $sql->get();
        return $cases;
    }
    // Added for user batch dropdown //




    // sub user new request listing //
    static function getCaseCountNewReqUser_sub($searchValue, $parent)
    {
        $sql = MedCase::with('user_involed');
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }
        
        $cases = $sql->where(['mediation_case.confirm_status' => 0, 'mediation_case.sub_user_id' => Auth::user()->id,])
           // ->leftJoin('users', 'users.id', '=', 'mediation_case.sub_user_id')
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->where('user_involved_in_agreement.userId', $parent)
            ->count();
        
        return $cases;
    }



    static function getCaseNewReqUser_sub($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $parent)
    {
        $sql = MedCase::with('user_involed');
        
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors']) && date_parse($searchValue)['month']) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.created_at', 'LIKE', "%{$searchValue}%");
                });
            } else if (is_numeric($searchValue)) {
                $sql->where(function ($query) use ($searchValue) {
                    $query
                        ->orWhere('mediation_case.id', 'LIKE', "%{$searchValue}%");
                });
            } else {
                $sql->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                        
                        ->orWhereHas('user_involed', function ($t) use ($searchValue) {
                            $t->where('name', 'LIKE', "%{$searchValue}%");
                        });
                        // ->orWhere(DB::raw("(concat('M', LPAD(mediation_case.id, 6, 0)))"), 'LIKE', "%{$searchValue}%");
                });
            }
        }

       
        $sql->where(['mediation_case.confirm_status' => 0, 'mediation_case.sub_user_id' => Auth::user()->id, ])
            //->leftJoin('users', 'users.id', '=', 'mediation_case.sub_user_id')
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->where('user_involved_in_agreement.userId', $parent);
           // ->leftJoin('user_hierarchy_master', 'users.id', '=', 'user_hierarchy_master.parent_userid');
    
            // ->orderby('mediation_case.id', 'DESC')
            // ->get();

            //var_dump($sql->toSql());
        
        if ($columnName == "case.caseid" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.id', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'asc') {
            $sql->orderBy('mediation_case.created_at', 'ASC');
        } else if ($columnName == "date" && $columnSortOrder == 'desc') {
            $sql->orderBy('mediation_case.created_at', 'DESC');
        } else if ($columnName == "party" && $columnSortOrder == 'acs') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'ASC');
            });
        } else if ($columnName == "party" && $columnSortOrder == 'desc') {
            $sql->with('user_involed', function ($t) {
                $t->orderBy('name', 'DESC');
            });
        } else {
            $sql->orderBy('mediation_case.id', 'DESC');
        }

        $cases = $sql->skip($row)
            ->take($rowperpage)->get();
        return $cases;
    }
    // sub user new request listing //
}
