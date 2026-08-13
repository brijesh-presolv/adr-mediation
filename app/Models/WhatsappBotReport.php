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
                    CaseId,
                    Respondent_Name 
                    Respondent_Email, 
                    Claimant_Name, 
                    Claimant_Email,
                    SUM(CASE WHEN event = 'WHATSAPP_BOT_PAY' THEN 1 ELSE 0 END) AS Whatsapp_PayNow_Count,
                    SUM(CASE WHEN event = 'Whatsapp_Bot_Why' THEN 1 ELSE 0 END) AS Whatsapp_Why_Count,
                    SUM(CASE WHEN event = 'Bot_Explore_Alternatives' THEN 1 ELSE 0 END) AS Whatsapp_Explore_Alternatives_Count,
                    SUM(CASE WHEN event = 'SUBMIT_REPLY' THEN 1 ELSE 0 END) AS Web_Submit_Reply_Count,
                    SUM(CASE WHEN event = 'WHATSAPP_BOT_RESTR_LINK' THEN 1 ELSE 0 END) AS Whatsapp_Restructure_Link_Count,
                    SUM(CASE WHEN event = 'RESTR_OFFER_SUBMIT' THEN 1 ELSE 0 END) AS Web_Restructure_Offer_Submit_Count,
                    SUM(CASE WHEN event = 'PRESS_PAY_NOW' THEN 1 ELSE 0 END) AS Web_Pay_Now_count,
                    GROUP_CONCAT(DISTINCT restructure_option) AS Restructure_Option,
                    GROUP_CONCAT(DISTINCT reply) AS Reply
                FROM whatsapp_bot_report
                WHERE caseid BETWEEN ? AND ?
                GROUP BY caseid, respondent_name, respondent_email, claimant_name, claimant_email";
        
                $result = DB::select($query, [$from, $to]);

        return $result;
    }

    public function getBotMisReport($caseid){

        $result = DB::table('whatsapp_bot_report')
                    ->select(
                        'caseid as CaseId',
                        'respondent_name as Respondent_Name', 
                        'respondent_email as Respondent_Email', 
                        'claimant_name as Claimant_Name', 
                        'claimant_email as Claimant_Email',
                        DB::raw('SUM(CASE WHEN event = "WHATSAPP_BOT_PAY" THEN 1 ELSE 0 END) AS Whatsapp_PayNow_Count'),
                        DB::raw('SUM(CASE WHEN event = "Whatsapp_Bot_Why" THEN 1 ELSE 0 END) AS Whatsapp_Why_Count'),
                        DB::raw('SUM(CASE WHEN event = "Bot_Explore_Alternatives" THEN 1 ELSE 0 END) AS Whatsapp_Explore_Alternatives_Count'),
                        DB::raw('SUM(CASE WHEN event = "SUBMIT_REPLY" THEN 1 ELSE 0 END) AS Web_Submit_Reply_Count'),
                        DB::raw('SUM(CASE WHEN event = "WHATSAPP_BOT_RESTR_LINK" THEN 1 ELSE 0 END) AS Whatsapp_Restructure_Link_Count'),
                        DB::raw('SUM(CASE WHEN event = "RESTR_OFFER_SUBMIT" THEN 1 ELSE 0 END) AS Web_Restructure_Offer_Submit_Count'),
                        DB::raw('SUM(CASE WHEN event = "PRESS_PAY_NOW" THEN 1 ELSE 0 END) AS Web_Pay_Now_count'),
                        DB::raw('GROUP_CONCAT(DISTINCT restructure_option) AS Restructure_Option'),
                        DB::raw('GROUP_CONCAT(DISTINCT reply) AS Reply')
                    )
                    ->where('caseid', $caseid)
                    ->groupBy('caseid', 'respondent_name', 'claimant_name', 'respondent_email', 'claimant_email')
                    ->get();
       return $result;
 }

}