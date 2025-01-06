<?php

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

    /*get common pass*/
    function get_common_password()
    {
        $password = Hash::make('recruiters2023');
        return $password;
    }

    /*get unique_id*/
    function get_unique_id()
    {
        // return time().rand(100,999);
        return rand(10000,99999);
    }

    /*
    *encrypt any type of value
    */
    function get_encrypt_value($data)
    {
        return Crypt::encrypt($data);
    }

    /*
    *decrypt any type of value
    */
    function get_decrypt_value($data)
    {
        return Crypt::decrypt($data);
    }

    /*
    *html email sent
    */
    function html_sent_email($template, $array_data)
    {
        Mail::send($template, $array_data, function ($message) use ($array_data) {
            $message->to($array_data['to_email'], $array_data['to_name'])->subject($array_data['subject']);
            $message->from($array_data['from_email'], $array_data['from_name']);
            if (isset($array_data['cc_email']) && $array_data['cc_email'] != "") {
                $message->cc($array_data['cc_email']);
            }
        });
        return true;
    }

    function get_site_details()
    {
        $site_settings = array(
            'SITE_NAME' => 'Recruiterlabs Platform',
            'SITE_EMAIL' => 'sonika.bhutoria@webol.co.uk',
        );

        return $site_settings;
    }

    //  for tickets 
    function get_priority(){
        $data = array('Low','High','Medium','Critical');
        return $data;
    }

    function get_teams(){
        $data = array('Operations','Marketing','Finance');
        return $data;
    }

    function get_lastvalued_logged($table,$array)
    {
        Log::channel('custom')->debug("UserId : ".Auth::user()->id." | Table name : $table | ".$array);
    }

    function doc_type(){
        $doc_type = DocumentType::where('status',1)->get();
        return $doc_type;
    }


    // directory: client_documents 
    // Client_clientId
    // CD-time-docname = client document
    // CL-time-name = client logo
    // PD-peopleid-time-name = People documents 
    // PL-peopleid-time-name = People logo
    // HRD-time-name = HR document
    // Ex: client_documents/CLIENT_2/CL-1683282042-A.webp
?>