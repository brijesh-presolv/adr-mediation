<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class System extends Model
{
    use HasFactory;

     protected $table='system';
    public $timestamps = false;
    protected $guarded = [];


    static function getallsetting($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage)
    {
        $sql = DB::table('system');
        if ($searchValue != '') {
            $sql->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
                
                // ->orWhere('username', 'LIKE', "%{$searchValue}%")
                // ->orWhere('mobileNo', 'LIKE', "%{$searchValue}%")
               // ->orWhere(DB::raw('concat(name," ",last_name)'), 'LIKE', "%{$searchValue}%");
            });
        }
        $details = $sql->where('status', '=', "1")->skip($row)->take($rowperpage)->get();


        // search query 
        if ($columnName && $columnSortOrder) {

            if ($columnName == 'name') {
                $details = $sql->orderby('user.name', $columnSortOrder);
            } 
            // else if ($columnName == 'username') {

            //     $details = $sql->orderby('user.username', $columnSortOrder);
            // } 
            // else if ($columnName == 'email') {

            //     $details = $sql->orderby('user.email', $columnSortOrder);
            // }
            // else if($columnName == 'mobile'){

            //     $details = $sql->orderby('user.mobileNo', $columnSortOrder);
            // }
            // else if ($columnName == 'address') {

            //     $details = $sql->orderby('user.address1', $columnSortOrder);
            // }
        }

        $details = $sql->get();
        $result = array();

        foreach ($details as $k => $data) {
            $result[] = $data;
        }

        return $result;
    }
    static function getallsettingTotal($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage)
    {
        $sql = DB::table('system');
        if ($searchValue != '') {
            $sql->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
                $query->where('status', '=', "1");
            });
        }
        $details = $sql->where('status', '=', "1")->count();

        return $details;
    }

    static function getdatabyid($id) {
        
           $qq="select * from system where id=?";
            return $case = DB::select($qq, [$id]);
      }

      static function changeSystemStatus() {
        
        $qq="update system set status=0 where name='ODR'";
         return $case = DB::select($qq);
   }

   


}
