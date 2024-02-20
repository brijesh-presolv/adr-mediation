<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WhatsappBotReport extends Model {

    protected $table = 'whatsapp_bot_report';
    protected $fillable = ['caseid', 'respondent_name', 'respondent_email', 'claimant_name', 'claimant_email', 'event', 'reply', 'restructure_option', 'event', 'created_at'];
    
    
    public function getmisreport($from, $to){

           $query = "SELECT 
                    caseid,
                    respondent_name, 
                    respondent_email, 
                    claimant_name, 
                    claimant_email,
                    SUM(CASE WHEN event = 'WHATSAPP_BOT_PAY' THEN 1 ELSE 0 END) AS whatsapp_bot_pay_count,
                    SUM(CASE WHEN event = 'Whatsapp_Bot_Why' THEN 1 ELSE 0 END) AS whatsapp_bot_why_count,
                    SUM(CASE WHEN event = 'Bot_Explore_Alternatives' THEN 1 ELSE 0 END) AS bot_explore_alternatives_count,
                    SUM(CASE WHEN event = 'SUBMIT_REPLY' THEN 1 ELSE 0 END) AS submit_reply_count,
                    SUM(CASE WHEN event = 'WHATSAPP_BOT_RESTR_LINK' THEN 1 ELSE 0 END) AS whatsapp_bot_restr_link_count,
                    SUM(CASE WHEN event = 'RESTR_OFFER_SUBMIT' THEN 1 ELSE 0 END) AS restr_offer_submit_count,
                    SUM(CASE WHEN event = 'PRESS_PAY_NOW' THEN 1 ELSE 0 END) AS press_pay_now_count,
                    GROUP_CONCAT(DISTINCT restructure_option) AS restructure_options
                FROM whatsapp_bot_report
                WHERE caseid BETWEEN $from AND $to
                GROUP BY caseid, respondent_name, respondent_email, claimant_name, claimant_email";
        
                $result = DB::select($query);

        return $result;
    }

}