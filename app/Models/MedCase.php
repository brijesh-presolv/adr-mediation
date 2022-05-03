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
    protected $fillable = ['userid', 'disputeCategory', 'natureOfAgreement', 'agreementDate', 'noOfParties', 'amount', 'proposedSolution', 'issue', 'confirm_status', 'case_status', 'documentPath', 'withdraw', 'otherRespondentDetails', 'request_letter', 'batch_id', 'ref_id', 'bulk_flag'];


    public function user_involed()
    {
        return $this->hasMany(InvoledUser::class, 'userPlanId', 'id');
    }

    static function getCase($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id = "")
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors'])) {
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
                });
            }
        }
        // $subQuery = DB::table('user_involved_in_agreement')
        // ->join('mediation_case', DB::raw('mediation_case.id'), '=', DB::raw('user_involved_in_agreement.userPlanid'))
        // ->skip(0)
        // ->take(1);
        $sql->select("mediation_case.*", DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator_username"), "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status", "consent_disclosures.updated_at as update", "consent_disclosures.created_at as create")
            ->leftJoin("mediators_mediation_cases_status", function ($join) {
                $join->on("mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id");
                $join->where("mediators_mediation_cases_status.id", "=", DB::raw("(select max(`mediators_mediation_cases_status2`.`id`) from mediators_mediation_cases_status as mediators_mediation_cases_status2 Where `mediators_mediation_cases_status2`.`mediation_case_id`=`mediation_case`.`id`)"));
            })
            ->leftJoin("consent_disclosures", "consent_disclosures.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            // ->leftJoin("user_involved_in_agreement", "user_involved_in_agreement.userPlanid", "=", "mediation_case.id")
            ->where("mediation_case.confirm_status", "=", $role);


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

    static function getCaseCount($searchValue, $role, $batch_id = "")
    {
        if ($batch_id != "") {
            $sql = MedCase::with('user_involed')->where("mediation_case.batch_id", $batch_id);
        } else {
            $sql = MedCase::with('user_involed');
        }
        if ($searchValue != '') {
            $searchValue = ltrim($searchValue, "M0");
            if (empty(date_parse($searchValue)['errors'])) {
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
            if (empty(date_parse($searchValue)['errors'])) {
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
            if (empty(date_parse($searchValue)['errors'])) {
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
            if(empty(date_parse($searchValue)['errors'])) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
            }
            $sql->where(function ($query) use ($searchValue, $uidSearch) {
                // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                $query->orWhere('mediation_case.id', 'LIKE', "{$uidSearch}%")
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
            if(empty(date_parse($searchValue)['errors'])) {
                $searchValue = new DateTime($searchValue);
                $searchValue = $searchValue->format('Y-m-d');
            }
            $sql->where(function ($query) use ($searchValue, $uidSearch) {
                // $query->where(DB::raw('concat(users.first_name," ",users.last_name)'), 'LIKE', "%{$searchValue}%")
                $query->orWhere('mediation_case.id', 'LIKE', "{$uidSearch}%")
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
            if (empty(date_parse($searchValue)['errors'])) {
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
            if (empty(date_parse($searchValue)['errors'])) {
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
}
