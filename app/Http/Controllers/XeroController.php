<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Webfox\Xero\OauthCredentialManager;
use Auth;
use Carbon\Carbon;
use Artisan;
use XeroAPI\XeroPHP\Api\OAuth2Api;
use XeroAPI\XeroPHP\Api\AccountingApi;

// use XeroAPI\XeroPHP\Models\Accounting\User;
class XeroController extends Controller
{

    public static function dashboard_data(Request $request, OauthCredentialManager $xeroCredentials){
        try {
            // Check if we've got any stored credentials
            if ($xeroCredentials) {
                /*
                * We have stored credentials so we can resolve the AccountingApi,
                * If we were sure we already had some stored credentials then we could just resolve this through the controller
                * But since we use this route for the initial authentication we cannot be sure!
                */
                $xero             = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
                $organisationName = $xero->getOrganisations($xeroCredentials->getTenantId())->getOrganisations()[0]->getName();
                $user             = $xeroCredentials->getUser();
                $username         = "{$user['given_name']} {$user['family_name']} ({$user['username']})";
            }
        } catch (\throwable $e) {
            // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
            $error = $e->getMessage();
        }
        // Start: getInvoices
            $if_modified_since = new \DateTime("2023-05-22T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
            $if_modified_since = null;
            // $where = 'Type=="ACCREC"'; // string
            $where = null;
            $order = null; // string
            $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
            $invoice_numbers = null; // string[] |  Filter by a comma-separated list of Invoice Numbers.
            $contact_ids = null; // string[] | Filter by a comma-separated list of ContactIDs.
            $statuses = array("DRAFT", "SUBMITTED","PAID","AUTHORISED");
            $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
            $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
            $created_by_my_app = null; // bool | When set to true you'll only retrieve Invoices created by your app
            $unitdp = null; // int | e.g. unitdp=4 – You can opt in to use four decimal places for unit amounts

            $data['draft_count'] = $data['draft_amount'] = $data['overdue_count'] = $data['overdue_amount'] = $data['aw_count'] = $data['aw_amount'] = $data['lastweek_count'] = $data['lastweek_amount'] = $data['nextweek_count'] = 0;
            $data['nextweek_amount'] = $data['currentweek_count'] = $data['currentweek_amount'] = $data['oldweek_count'] = $data['oldweek_amount'] = $data['futureweek_count'] = $data['futureweek_amount'] = 0;
            $my_data['draft_count'] = 0;
            $my_data['draft_amount'] = $my_data['overdue_count'] = $my_data['overdue_amount'] = $my_data['aw_count'] = $my_data['aw_amount'] = $my_data['lastweek_count'] = $my_data['lastweek_amount'] = $my_data['nextweek_count'] = 0;
            $my_data['nextweek_amount'] = $my_data['currentweek_count'] = $my_data['currentweek_amount'] = $my_data['oldweek_count'] = $my_data['oldweek_amount'] = $my_data['futureweek_count'] = $my_data['futureweek_amount'] = 0;

            if(isset($organisationName)){
                $invoice_apiResponse = $xero->getInvoices($xeroCredentials->getTenantId(), $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
            }
            $weeks = XeroController::getWeeks();
            $invoice_data = [];
            $item = 0;
            foreach ($weeks as $key => $week) {
                $invoice_data[$key]['name'] = $week;
                $invoice_data[$key]['y'] = 0;
                $invoice_data[$key]['item'] = $item++;
            }

            $bills_data = [];
            foreach ($weeks as $key => $week) {
                $bills_data[$key]['name'] = $week;
                $bills_data[$key]['y'] = 0;
                $bills_data[$key]['item'] = $item++;
            }

            //   dd($invoice_data);
            if(isset($invoice_apiResponse)) {
                foreach ($invoice_apiResponse->getInvoices() as $key => $value) {

                    if (isset($value['date'])) {
                        $date = str_replace('/Date(', '', $value['date']);
                        $parts = explode('+', $date);
                        $value['date'] = $date = date("Y-m-d", $parts[0] / 1000);
                    }

                    if (isset($value['due_date'])) {
                        $date1 = str_replace('/Date(', '', $value['due_date']);
                        $parts1 = explode('+', $date1);
                        $value['due_date'] = $due_date = date("Y-m-d", $parts1[0] / 1000);
                    }

                    if(substr($value['invoice_number'], 0, 3)=='INV') {

                        if($value['status']!="DRAFT" && $value['status']!="PAID") {
                            foreach($invoice_data as $key => $week) {
                                $arun=explode(',', $key);
                                if(isset($arun[1]) && $arun[1]=='older') {
                                    $invoice_data[$key]['name'] = 'older';
                                    if($arun[0]> $due_date  && $value['amount_due']!=null) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif(isset($arun[1]) && $arun[1]=='future') {
                                    $invoice_data[$key]['name'] = 'future';
                                    if($arun[0]<= $due_date) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif($arun[1]!='future' || $arun[1]!='older') {
                                    if($arun[0]<= $due_date && $arun[1]>= $due_date) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                }

                            }
                        }

                        if($value['status']=="DRAFT") {
                            $data['draft_count'] += 1;
                            $data['draft_amount'] += $value['amount_due'];
                        } elseif($value['amount_due']!=null && $due_date<date("Y-m-d")) {
                            $data['overdue_count'] += 1;
                            $data['overdue_amount'] += $value['amount_due'];
                            $data['aw_count'] += 1;
                            $data['aw_amount'] += $value['amount_due'];

                        } elseif($value['amount_due']!=null && ($due_date>=date("Y-m-d"))) {
                            $data['aw_count'] += 1;
                            $data['aw_amount'] += $value['amount_due'];
                        }
                    } else { //Bill to pay
                        if($value['status']!="DRAFT" && $value['status']!="PAID") {
                            foreach($bills_data as $key => $week) {
                                $arun=explode(',', $key);
                                if(isset($arun[1]) && $arun[1]=='older') {
                                    $bills_data[$key]['name'] = 'older';
                                    if($arun[0]> $due_date) {
                                        $bills_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif(isset($arun[1]) && $arun[1]=='future') {
                                    // dd($arun[1]);
                                    $bills_data[$key]['name'] = 'future';
                                    if($arun[0]<= $due_date) {
                                        $bills_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif($arun[1]!='future' || $arun[1]!='older') {
                                    if($arun[0]<= $due_date && $arun[1]>= $due_date) {
                                        $bills_data[$key]['y'] +=$value['amount_due'];
                                    }
                                }

                            }
                        }

                        if($value['status']=="DRAFT") {
                            $my_data['draft_count'] += 1;
                            $my_data['draft_amount'] += $value['amount_due'];
                        } elseif($value['amount_due']!=null && $due_date<date("Y-m-d")) {
                            $my_data['overdue_count'] += 1;
                            $my_data['overdue_amount'] += $value['amount_due'];
                            $my_data['aw_count'] += 1;
                            $my_data['aw_amount'] += $value['amount_due'];

                        } elseif($value['amount_due']!=null && ($due_date>=date("Y-m-d"))) {
                            $my_data['aw_count'] += 1;
                            $my_data['aw_amount'] += $value['amount_due'];
                        }
                    }
                }
                //Creating data according to chart
                $item = 4;
                $invoices_array = [];
                foreach ($invoice_data as $key => $week) {
                    if($week['name']=='future') {
                        $invoices_array[5]['name'] = ucfirst($week['name']);
                        $invoices_array[5]['y'] = $week['y'];
                    } elseif($week['name']=='older') {
                        $invoices_array[0]['name'] = ucfirst($week['name']);
                        $invoices_array[0]['y'] = $week['y'];
                    } else {
                        $invoices_array[$item]['name'] = $week['name'];
                        $invoices_array[$item]['y'] = $week['y'];
                        $item--;
                    }
                }

                $item1 = 4;
                $bills_array = [];
                foreach ($bills_data as $key => $week) {
                    if($week['name']=='future') {
                        $bills_array[5]['name'] = ucfirst($week['name']);
                        $bills_array[5]['y'] = $week['y'];
                    } elseif($week['name']=='older') {
                        $bills_array[0]['name'] = ucfirst($week['name']);
                        $bills_array[0]['y'] = $week['y'];
                    } else {
                        $bills_array[$item1]['name'] = $week['name'];
                        $bills_array[$item1]['y'] = $week['y'];
                        $item1--;
                    }

                }
                krsort($invoices_array);
                krsort($bills_array);

                if (count($invoice_apiResponse->getInvoices()) > 0) {
                    $message = 'Total invoices found: ' . count($invoice_apiResponse->getInvoices());
                } else {
                    $message = "No invoices found matching filter criteria";
                }
            }

            if(isset($invoices_array)){
                $dashboard_data[0] = $invoices_array ??null;
                $dashboard_data[1]  = $data ??null;
            }
        // End: getInvoices


        // Start: total_cash_inout
            $month = XeroController::getMonth();
            $total_cash_inout = [];
            foreach($month as $key => $data) {
                $fromDate = $data['start_date'];
                $toDate = $data['end_date'];

                if(isset($organisationName)) {
                    $total_cash_inout_data = $xero->getReportBankSummary($xeroCredentials->getTenantId(), $fromDate, $toDate);
                }

                $month = date('F', strtotime($toDate));
                if(isset($total_cash_inout_data)) {
                    foreach($total_cash_inout_data['reports'] as $result) {
                        foreach($result['rows'] as $key1 => $result1) {
                            if($result1['rows']) {
                                foreach($result1['rows'] as $key2 => $result2) {
                                    if($result2['row_type']=='SummaryRow') {
                                        $item2 =0;
                                        foreach($result2['cells'] as $key => $result3) {
                                            if($key=='2') {
                                                $total_cash_inout[$month]['In'] = $result3['value'];
                                                $item2++;
                                            } elseif($key=='3') {
                                                $total_cash_inout[$month]['Out'] = $result3['value'];
                                                $item2++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(isset($total_cash_inout_data)) {
                $total_cash = [];
                $element = 5;
                $element1 = 5;
                $element2 = 5;

                foreach($total_cash_inout as $key=> $value) {
                    $total_cash['name'][$element] = $key;
                    foreach($value as $key1=> $value1) {
                        if($key1=='In') {
                            $total_cash['y']['In'][$element1] = $value1;
                            $element1--;
                        } else {
                            $total_cash['y']['Out'][$element2] = $value1;
                            $element2--;
                        }
                    }
                    $element--;
                }
                krsort($total_cash['name']);
            }
            // dd($total_cash);
            if(isset($invoices_array)){
                $dashboard_data[2]  = $total_cash ??null;
            }

        // End: total_cash_inout

        // Start: ReportBalanceSheet
            // $date = new \DateTime("2023-05-01");
            // $periods = 3;
            // $timeframe = "MONTH";
            // $trackingOptionID1 = "00000000-0000-0000-0000-000000000000";
            // $trackingOptionID2 = "00000000-0000-0000-0000-000000000000";
            // $standardLayout = true;
            // $paymentsOnly = false;
            // $balance = [];
            // $item2 =0;
                if(isset($organisationName)) {
                    $getReportBalanceSheet_data = $xero->getReportBalanceSheet($xeroCredentials->getTenantId());
                }
                if(isset($getReportBalanceSheet_data)) {
                        foreach($getReportBalanceSheet_data['reports'] as $result){
                            foreach($result['rows'] as $key => $result1) {
                                if($result1['rows']){
                                    foreach($result1['rows'] as $key => $result2) {
                                        if($result2['cells'][0]['value']=='Business Bank Account')
                                        {
                                            $item2 =0;
                                            foreach($result2['cells'] as $key => $result3) {
                                                if($result3['value']!=null) {
                                                    $balance[$item2] = $result3['value'];
                                                    $item2++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }

            if(isset($invoices_array)){
                $dashboard_data[3]  = $balance ?? null;
                $dashboard_data[4] = $organisationName ?? null;
                $dashboard_data[5] = $username ?? null;
            }
        // End: ReportBalanceSheet

        return $dashboard_data ?? null;
    }

    public function index(Request $request)
    {
        // TODO - assume failing authorization
        return response()->json([]);




        // dd($xeroCredentials);
        //Start: Xero Connection
            try {
                // Check if we've got any stored credentials
                if ($xeroCredentials) {

                    /*
                    * We have stored credentials so we can resolve the AccountingApi,
                    * If we were sure we already had some stored credentials then we could just resolve this through the controller
                    * But since we use this route for the initial authentication we cannot be sure!
                    */
                    $xero             = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
                    // try {
                        $organisationName = $xero->getOrganisations($xeroCredentials->getTenantId())->getOrganisations()[0]->getName();

                    // }
                    // catch (\throwable $e) {
                    //     // dd($e);
                    //     // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                    //     return redirect('/client_error_page');
                    // }
                    $user             = $xeroCredentials->getUser();
                    $username         = "{$user['given_name']} {$user['family_name']} ({$user['username']})";
                }
            } catch (\throwable $e) {
                // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                $error = $e->getMessage();
            }
        //End: Xero Connection

            $month = $this->getMonth();
        // End: Start and end date of last 6 month

        // Start: total_cash_inout
            $total_cash_inout = [];
            foreach($month as $key => $data) {
                // echo $data['end_date'];
                $fromDate = $data['start_date'];
                $toDate = $data['end_date'];

                // try {
                //     $total_cash_inout_data = $xero->getReportBankSummary($xeroCredentials->getTenantId(), $fromDate, $toDate);
                // } catch (\Exception $e) {
                //     // echo 'Exception when calling AccountingApi->getReportBankSummary: ', $e->getMessage(), PHP_EOL;
                // }

                if(isset($organisationName)) {
                    try {
                        $total_cash_inout_data = $xero->getReportBankSummary($xeroCredentials->getTenantId(), $fromDate, $toDate);
                    } catch (\throwable $e) {
                        // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                        return redirect('/client_error_page');
                    }
                }
                // echo '<pre>';
                // print_r($xero->getReportBankSummary($xeroCredentials->getTenantId(), $fromDate, $toDate));
                // echo '<pre>';
                // die;
                //  dd($xero->getReportBankSummary($xeroCredentials->getTenantId(), $fromDate, $toDate));
                $month = date('F', strtotime($toDate));
                //  dd($month);
                if(isset($total_cash_inout_data)) {
                    foreach($total_cash_inout_data['reports'] as $result) {
                        foreach($result['rows'] as $key1 => $result1) {
                            if($result1['rows']) {
                                // dd($result1['rows']);
                                foreach($result1['rows'] as $key2 => $result2) {
                                    if($result2['row_type']=='SummaryRow') {
                                        // dd($result2);
                                        $item2 =0;
                                        foreach($result2['cells'] as $key => $result3) {
                                            if($key=='2') {
                                                $total_cash_inout[$month]['In'] = $result3['value'];
                                                $item2++;
                                            } elseif($key=='3') {
                                                $total_cash_inout[$month]['Out'] = $result3['value'];
                                                $item2++;
                                            }
                                            // else{
                                            //     $total_cash_inout[$month][$item2] = $result3['value'];
                                            //     $item2++;
                                            // }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(isset($total_cash_inout_data)) {
                $total_cash = [];
                $element = 5;
                $element1 = 5;
                $element2 = 5;
                // foreach($total_cash_inout as $key=> $value){
                //     // dd($value);
                //     foreach($value as $key1=> $value1) {
                //         dd($value1);
                //         $total_cash['name'][$element] = $key.'_'.$key1;
                //         $total_cash['y'][$element] = $value1;
                //         $element++;
                //     }
                // }
                foreach($total_cash_inout as $key=> $value) {
                    // dd($value);
                    $total_cash['name'][$element] = $key;
                    foreach($value as $key1=> $value1) {
                        if($key1=='In') {
                            $total_cash['y']['In'][$element1] = $value1;
                            $element1--;
                        } else {
                            $total_cash['y']['Out'][$element2] = $value1;
                            $element2--;
                        }
                    }
                    $element--;
                }
                krsort($total_cash['name']);
            }

            if(isset($invoices_array)){
                $data[0] = $invoices_array;
                $data[1]  = $data;
            }
        // End: total_cash_inout

        // Start: ReportBalanceSheet
            // $date = new \DateTime("2023-05-01");
            // $periods = 3;
            // $timeframe = "MONTH";
            // $trackingOptionID1 = "00000000-0000-0000-0000-000000000000";
            // $trackingOptionID2 = "00000000-0000-0000-0000-000000000000";
            // $standardLayout = true;
            // $paymentsOnly = false;
            // $balance = [];
            // $item2 =0;

            if(isset($organisationName)) {
                try {
                    $getReportBalanceSheet_data = $xero->getReportBalanceSheet($xeroCredentials->getTenantId());
                } catch (\throwable $e) {
                    // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                    return redirect('/client_error_page');
                }
            }
            // //  dd($results);
                if(isset($getReportBalanceSheet_data)) {
                        foreach($getReportBalanceSheet_data['reports'] as $result){
                            foreach($result['rows'] as $key => $result1) {
                                if($result1['rows']){
                                    foreach($result1['rows'] as $key => $result2) {
                                        if($result2['cells'][0]['value']=='Business Bank Account')
                                        {
                                            $item2 =0;
                                            foreach($result2['cells'] as $key => $result3) {
                                                if($result3['value']!=null) {
                                                    $balance[$item2] = $result3['value'];
                                                    $item2++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }
            // dd($balance);

            // echo '<pre>';
            // print_r($result);
            // echo '<pre>';
            // die;
        // End: ReportBalanceSheet

        // Start: ReportTrialBalance
            $date = new \DateTime("2019-10-31");
            $paymentsOnly = true;

            if(isset($organisationName)) {
                try {
                    $account_watchlist_data =  $xero->getReportTrialBalance($xeroCredentials->getTenantId());
                } catch (\throwable $e) {
                    // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                    return redirect('/client_error_page');
                }
            }
            // try {
            //     $account_watchlist_data =  $xero->getReportTrialBalance($xeroCredentials->getTenantId());
            // } catch (\Exception $e) {
            //     echo 'Exception when calling AccountingApi->getReportTrialBalance: ', $e->getMessage(), PHP_EOL;
            // }
            // echo '<pre>';
            // print_r($result);
            // echo '<pre>';
            // $hotel_name = array_column($result, 'value');
            // print_r($hotel_name);
            // die;
            //   dd($results['reports']);
            $account_watchlist = [];
            $item =0;
            if(isset($account_watchlist_data)) {
                    foreach($account_watchlist_data['reports'] as $result){
                        foreach($result['rows'] as $key => $result1) {
                            if($result1['rows']){
                                foreach($result1['rows'] as $key => $result2) {
                                    if($result2['cells'][0]['value']=='Advertising (400)' || $result2['cells'][0]['value']=='Entertainment (420)' || $result2['cells'][0]['value']=='Inventory (630)' || $result2['cells'][0]['value']=='Sales (200)')
                                    {
                                        $item1 =0;
                                        foreach($result2['cells'] as $key => $result3) {
                                            if($result3['value']!=null) {
                                                $account_watchlist[$item][$item1] = $result3['value'];
                                                $item1++;
                                            }
                                        }
                                        $item++;
                                    }
                                }
                            }
                        }
                    }
            }
            // sort($account_watchlist);
            //   dd($account_watchlist);
        // End: ReportTrialBalance


        // Start: getInvoices
            $if_modified_since = new \DateTime("2023-05-22T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
            $if_modified_since = null;
            // $where = 'Type=="ACCREC"'; // string
            $where = null;
            $order = null; // string
            $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
            $invoice_numbers = null; // string[] |  Filter by a comma-separated list of Invoice Numbers.
            $contact_ids = null; // string[] | Filter by a comma-separated list of ContactIDs.
            $statuses = array("DRAFT", "SUBMITTED","PAID","AUTHORISED");
            $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
            $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
            $created_by_my_app = null; // bool | When set to true you'll only retrieve Invoices created by your app
            $unitdp = null; // int | e.g. unitdp=4 – You can opt in to use four decimal places for unit amounts

            $data['draft_count'] = $data['draft_amount'] = $data['overdue_count'] = $data['overdue_amount'] = $data['aw_count'] = $data['aw_amount'] = $data['lastweek_count'] = $data['lastweek_amount'] = $data['nextweek_count'] = 0;
            $data['nextweek_amount'] = $data['currentweek_count'] = $data['currentweek_amount'] = $data['oldweek_count'] = $data['oldweek_amount'] = $data['futureweek_count'] = $data['futureweek_amount'] = 0;
            $my_data['draft_count'] = 0;
            $my_data['draft_amount'] = $my_data['overdue_count'] = $my_data['overdue_amount'] = $my_data['aw_count'] = $my_data['aw_amount'] = $my_data['lastweek_count'] = $my_data['lastweek_amount'] = $my_data['nextweek_count'] = 0;
            $my_data['nextweek_amount'] = $my_data['currentweek_count'] = $my_data['currentweek_amount'] = $my_data['oldweek_count'] = $my_data['oldweek_amount'] = $my_data['futureweek_count'] = $my_data['futureweek_amount'] = 0;

            if(isset($organisationName)){
                try {
                    $invoice_apiResponse = $xero->getInvoices($xeroCredentials->getTenantId(), $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
                } catch (\throwable $e) {
                    // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
                    return redirect('/client_error_page');
                }
            }

            $weeks = $this->getWeeks();
            $invoice_data = [];
            $item = 0;

            foreach ($weeks as $key => $week) {
                $invoice_data[$key]['name'] = $week;
                $invoice_data[$key]['y'] = 0;
                $invoice_data[$key]['item'] = $item++;
            }
            //dd($invoice_data);
            $bills_data = [];
            foreach ($weeks as $key => $week) {
                $bills_data[$key]['name'] = $week;
                $bills_data[$key]['y'] = 0;
                $bills_data[$key]['item'] = $item++;
            }

            // dd($invoice_apiResponse->getInvoices());
            if(isset($invoice_apiResponse)) {
                foreach ($invoice_apiResponse->getInvoices() as $key => $value) {

                    if (isset($value['date'])) {
                        $date = str_replace('/Date(', '', $value['date']);
                        $parts = explode('+', $date);
                        $value['date'] = $date = date("Y-m-d", $parts[0] / 1000);
                    }

                    if (isset($value['due_date'])) {
                        $date1 = str_replace('/Date(', '', $value['due_date']);
                        $parts1 = explode('+', $date1);
                        $value['due_date'] = $due_date = date("Y-m-d", $parts1[0] / 1000);
                    }

                    if(substr($value['invoice_number'], 0, 3)=='INV') {

                        if($value['status']!="DRAFT" && $value['status']!="PAID") {
                            foreach($invoice_data as $key => $week) {
                                $arun=explode(',', $key);
                                if(isset($arun[1]) && $arun[1]=='older') {
                                    $invoice_data[$key]['name'] = 'older';
                                    if($arun[0]> $due_date  && $value['amount_due']!=null) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif(isset($arun[1]) && $arun[1]=='future') {
                                    $invoice_data[$key]['name'] = 'future';
                                    if($arun[0]<= $due_date) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif($arun[1]!='future' || $arun[1]!='older') {
                                    if($arun[0]<= $due_date && $arun[1]>= $due_date) {
                                        $invoice_data[$key]['y'] += $value['amount_due'];
                                    }
                                }

                            }
                        }

                        if($value['status']=="DRAFT") {
                            $data['draft_count'] += 1;
                            $data['draft_amount'] += $value['amount_due'];
                        } elseif($value['amount_due']!=null && $due_date<date("Y-m-d")) {
                            $data['overdue_count'] += 1;
                            $data['overdue_amount'] += $value['amount_due'];
                            $data['aw_count'] += 1;
                            $data['aw_amount'] += $value['amount_due'];

                        } elseif($value['amount_due']!=null && ($due_date>=date("Y-m-d"))) {
                            $data['aw_count'] += 1;
                            $data['aw_amount'] += $value['amount_due'];
                        }
                    } else { //Bill to pay
                        //dd($value['status']);
                        if($value['status']!="DRAFT" && $value['status']!="PAID") {
                            foreach($bills_data as $key => $week) {
                                $arun=explode(',', $key);
                                if(isset($arun[1]) && $arun[1]=='older') {
                                    $bills_data[$key]['name'] = 'older';
                                    if($arun[0]> $due_date) {
                                        $bills_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif(isset($arun[1]) && $arun[1]=='future') {
                                    $bills_data[$key]['name'] = 'future';
                                    if($arun[0]<= $due_date) {
                                        $bills_data[$key]['y'] += $value['amount_due'];
                                    }
                                } elseif($arun[1]!='future' || $arun[1]!='older') {
                                    if($arun[0]<= $due_date && $arun[1]>= $due_date) {
                                        $bills_data[$key]['y'] +=$value['amount_due'];
                                    }
                                }

                            }
                        }

                        if($value['status']=="DRAFT") {
                            $my_data['draft_count'] += 1;
                            $my_data['draft_amount'] += $value['amount_due'];
                        } elseif($value['amount_due']!=null && $due_date<date("Y-m-d")) {
                            $my_data['overdue_count'] += 1;
                            $my_data['overdue_amount'] += $value['amount_due'];
                            $my_data['aw_count'] += 1;
                            $my_data['aw_amount'] += $value['amount_due'];

                        } elseif($value['amount_due']!=null && ($due_date>=date("Y-m-d"))) {
                            $my_data['aw_count'] += 1;
                            $my_data['aw_amount'] += $value['amount_due'];
                        }
                    }
                }
                //dd($invoice_data);
                //Creating data according to chart
                $item = 4;
                $invoices_array = [];
                foreach ($invoice_data as $key => $week) {
                    if($week['name']=='future') {
                        $invoices_array[5]['name'] = ucfirst($week['name']);
                        $invoices_array[5]['y'] = $week['y'];
                    } elseif($week['name']=='older') {
                        $invoices_array[0]['name'] = ucfirst($week['name']);
                        $invoices_array[0]['y'] = $week['y'];
                    } else {
                        $invoices_array[$item]['name'] = $week['name'];
                        $invoices_array[$item]['y'] = $week['y'];
                        $item--;
                    }
                }

                $item1 = 4;
                $bills_array = [];
                foreach ($bills_data as $key => $week) {
                    if($week['name']=='future') {
                        $bills_array[5]['name'] = ucfirst($week['name']);
                        $bills_array[5]['y'] = $week['y'];
                    } elseif($week['name']=='older') {
                        $bills_array[0]['name'] = ucfirst($week['name']);
                        $bills_array[0]['y'] = $week['y'];
                    } else {
                        $bills_array[$item1]['name'] = $week['name'];
                        $bills_array[$item1]['y'] = $week['y'];
                        $item1--;
                    }

                }

                if (count($invoice_apiResponse->getInvoices()) > 0) {
                    $message = 'Total invoices found: ' . count($invoice_apiResponse->getInvoices());
                } else {
                    $message = "No invoices found matching filter criteria";
                }
            }
        // End: getInvoices

        return response()->json([
            'connected'        => $xeroCredentials->exists() ?? null,
            'error'            => $error ?? null,
            'organisationName' => $organisationName ?? null,
            'username'         => $username ?? null,
            'message'          => $message ?? null,
            'data'             => $data ?? null,
            'my_data'          => $my_data ?? null,
            'invoices_array'   => $invoices_array ?? null,
            'bills_array'      => $bills_array ?? null,
            'account_watchlist'=> $account_watchlist ?? null,
            'balance'          => $balance ?? null,
            'total_cash'       => $total_cash ?? null,
        ], 200);

    }

    public static function getWeeks() //TO get weeks with start and enddate
    {



        // Create an empty array for storing weeks
        $weeks = [];
        // echo (Carbon::now());
        // echo (Carbon::now()->subWeeks(0));
        // echo (Carbon::now()->subWeeks(0)->startOfWeek());
        // echo (Carbon::THURSDAY);

        // echo( Carbon::now()->addWeeks(1)->startOfWeek());
        // echo( Carbon::now()->addWeeks(1)->endOfWeek());

        // Define the number of weeks to generate (past 3 months ≈ 12 weeks)
        $numWeeks = 4;

        // Define the starting weekday (0 = Sunday, 6 = Saturday)
        $startOfWeek = Carbon::THURSDAY; // Start the week on Thursday

        // Loop to generate week ranges for the past 12 weeks
        for ($i = 0; $i < $numWeeks; $i++) {
            // Calculate the start and end of the week
            $from = Carbon::now()->subWeeks($i)->startOfWeek(); // Start of the week (Thursday)
            $to = Carbon::now()->subWeeks($i)->endOfWeek(); // End of the week (Wednesday)

            // Format the date range
            $ymd_week_range = $from->format('Y-m-d') . ',' . $to->format('Y-m-d');

            // Format the week description (e.g., 'Feb 2 - Jan 25')
            $weeks[$ymd_week_range] = $from->format('M j') . ' - ' . $to->format('M j');

        }

        // Add "older" and "future" ranges
        $older = Carbon::now()->subWeeks($numWeeks )->endOfWeek()->format('Y-m-d') . ',' . 'older';
        $future = Carbon::now()->addWeeks(1)->startOfWeek()->format('Y-m-d') . ',' . 'future';

        // Adding "older" and "future" ranges to the weeks array
        $weeks[$older] = 'Older';
        $weeks[$future] = 'Future';

        // Print the weeks array (optional)
        //dd($weeks);
        return $weeks;
    }

    //TO get months with start and enddate
    public static function getMonth() {
        $month = [];
        $dateTime =  Carbon::now()->startOfMonth();
        for ($i = 6; $i >= 1; $i--) {
            $date = $dateTime->format('Y/m/d');
            $month[$i]['start_date']=  $dateTime->format('Y/m/d');

            $month[$i]['end_date']=  date('Y/m/t', strtotime($date));
            // echo '<br>';
            $dateTime->modify('-1 month');
        }
        return $month;
    }

}
