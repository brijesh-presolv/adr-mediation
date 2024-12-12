<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WaTemplate extends Model
{
    use HasFactory;


    protected $table = 'wa_template';


    static function getcontent($n)
    {
        $query = WaTemplate::select('content')->where('name', $n)->first();
        // print_r($query);
        // exit;
        // $query = "SELECT content FROM wa_template where name='".$n."'";
        //$result = array();

        // $details = DB::select($query);

        return  str_replace(['::', ';;'], ['‘', '’'], $query['content']);
    }


    // choose random template from table //
    static function getRandomTemplate($slug){
        
        $query = WaTemplate::select('name')
            ->where('slug',$slug)
            ->where('is_active',1)
            ->orderByRaw('RAND()')
            ->first();

        return $query['name'];
    }
    // choose random template from table //
}
