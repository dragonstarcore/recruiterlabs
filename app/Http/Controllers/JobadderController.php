<?php

namespace App\Http\Controllers;

use Config;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\JobadderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
session_start();
class JobadderController extends Controller
{
    public static function index(Request $request)
    {
        try {


                        // $user = User::where('id',Auth::user()->id)->with('jobadder_details')->first();
                        // $str = '-------Start---------';
                        //  dd(1);
                        // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                        $provider = new \RolandSaven\OAuth2\Client\Provider\JobAdder([
                            //For local
                            'clientId'          => Config::get('app.jobadder_details.clientId'),
                            'clientSecret'      => Config::get('app.jobadder_details.clientSecret'),
                            'scope'             => Config::get('app.jobadder_details.scope'),
                            'redirectUri'       => Config::get('app.jobadder_details.redirectUri'),
                            //for server
                            // 'clientId'          => 'lhytzr73qs5ublobvwumnd5vnu',
                            // 'clientSecret'      => '3xjvf426ohju3jmcfpvpde4mguum7ajdhiehsu3ndmoufnlrge54',
                            // 'scope'             => 'read offline_access',
                            // 'redirectUri'       => 'https://recruiterlabsdash.co.uk/recruiterlabs/jobadder',
                        ]);

                        //save to jobadder
                        $JobadderDetail = JobadderDetail::where('user_id',Auth::user()->id)->first();
                        
                        if(isset($JobadderDetail['code']) && $JobadderDetail['code']!=null){
                            // $str = '!1';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            $_GET['state'] = $JobadderDetail['state'];
                            $_SESSION['oauth2state'] = $JobadderDetail['state'];
                            Session::put('oauth2state', $JobadderDetail['state']);
                            // $oauth2state=Session::get('oauth2state');
                            $_GET['code'] = $JobadderDetail['code'];
                            $_GET['refresh_token'] = 'yes';
                        }else if(isset($_GET['code']) && $_GET['code'] != "" && isset($_GET['state']) && $_GET['state'] != "")
                        {
                            // $str = '!2';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            $JobadderDetail['code'] = $_GET['code'];
                            $JobadderDetail['state'] = $_GET['state'];
                            $JobadderDetail->save();
                            Session::put('oauth2state', $_GET['state']);
                        }
                        
                        if (!isset($_GET['code'])) {
                            $str = '--!no code';
                            file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            // If we don't have an authorization code then get one
                            $authUrl = $provider->getAuthorizationUrl();
                            // $_SESSION['oauth2state'] = $provider->getState();
                            // $oauth2state = Session::get('oauth2state') ? Session::get('oauth2state') : null;
                            header('Location: '.$authUrl);
                            exit;
                            
                            // Check given state against previously stored one to mitigate CSRF attack
                        } elseif (empty($_GET['state']) || ($_GET['state'] !== Session::get('oauth2state') )) {
                            // unset($_SESSION['oauth2state']);
                            Session::forget('oauth2state');
                            // $str = '--!Invalid state';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            exit('Invalid state');
                            
                        } else {
                            // dd('1');
                            // $str = '!3';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            if(isset($_GET['refresh_token']) && $_GET['refresh_token'] == 'yes')
                            {
                                // $str = '!4';
                                // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                                // Try to get an access token (using the authorization code grant)
                                $token = $provider->getAccessToken('refresh_token', [
                                    'refresh_token' => $JobadderDetail['refresh_token']
                                ]);
                                $JobadderDetail['refresh_token_response'] = json_encode($token);
                                dd(1);
                            }
                            else{
                                // $str = '!5';
                                // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                                // Try to get an access token (using the authorization code grant)
                                $token = $provider->getAccessToken('authorization_code', [
                                    'code' => $_GET['code']
                                ]);
                                $JobadderDetail['auth_response'] = json_encode($token);
                            }
                            // $str = '!6';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            // file_put_contents("token.txt",json_encode($token));
                            
                            $JobadderDetail['refresh_token'] = $token->getRefreshToken();
                            // $JobadderDetail['auth_response'] = json_encode($token);
                            $JobadderDetail->save();
                            // Optional: Now you have a token you can look up a users profile data
                            try {
                                // $str = '!7';
                                // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                                // We got an access token, let's now get the user's details
                                $account = $provider->getResourceOwner($token);
                                // Use these details to create a new profile
                                Session::put('FullName', $account->getFullName());
                                Session::put('Email', $account->getEmail());
                            } catch (Exception $e) {
                                // $str = '8';
                                // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                                // Failed to get user details
                                exit('Oh dear...');
                            }
                            // $str = '!9';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            // Use this to interact with an API on the users behalf
                            // $_SESSION['token'] = $token->getToken();
                            Session::put('token', $token->getToken());
                            $JobadderDetail['token'] = $token->getToken();
                            $JobadderDetail->save();
                            // $str = '!10';
                            // file_put_contents("jobadder.txt",PHP_EOL .$str,FILE_APPEND);
                            return response()->json(['message' => 'Authorization successful', 'redirect' => '/jobadder_data']);
                        }

        } catch (\throwable $e) {
            dd($e);
            // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
            return response()->json(['error' => 'Authorization failed', 'redirect' => '/client_error_page'], 500);
        }
    }

    public static function jobadder(Request $request)
    {
        $JobadderDetail = JobadderDetail::where('user_id',Auth::user()->id)->first();

        $client = new \GuzzleHttp\Client();

        if(isset($JobadderDetail['refresh_token']) && $JobadderDetail['refresh_token']!=null) {
            try {
                $response = $client->post('https://id.jobadder.com/connect/token', [
                    'form_params' => [
                        'client_id'          => Config::get('app.jobadder_details.clientId'),
                        'client_secret'      => Config::get('app.jobadder_details.clientSecret'),
                        'grant_type'         => "refresh_token",
                        'refresh_token'      => $JobadderDetail['refresh_token'],
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ]);
    
                $responseBody = json_decode($response->getBody(), true);
                $JobadderDetail['token'] = $responseBody["access_token"];
                $JobadderDetail['refresh_token'] = $responseBody["refresh_token"];
                $JobadderDetail->save();
            } catch (\Throwable $e) {
                $JobadderDetail['refresh_token'] = null;
                $JobadderDetail->save();
                return response()->json(['err' => "Redirecting..."], 302);
            }
        } else {
            try {
                $response = $client->post('https://id.jobadder.com/connect/token', [
                    'form_params' => [
                        'client_id'          => Config::get('app.jobadder_details.clientId'),
                        'client_secret'      => Config::get('app.jobadder_details.clientSecret'),
                        'grant_type'         => "authorization_code",
                        'redirect_uri'       => Config::get('app.jobadder_details.redirectUri'),
                        'code'               => $request->input('code'),
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ]);
    
                $responseBody = json_decode($response->getBody(), true);
                $JobadderDetail['token'] = $responseBody["access_token"];
                $JobadderDetail['refresh_token'] = $responseBody["refresh_token"];
                $JobadderDetail->save();
            } catch (\Throwable $e) {
                return response()->json(['err' => "Bad request."], 400);
            }
        }

        $data = JobadderController::jobadder_data();

        return response()->json(['ok' => "ok", 'data' => $data]);
    }

    //Main page
    public function get_jobs(Request $request)
    {
        // Cache::forget('jobadder');
        $dateOption = $request->input('date_option');
        $user = User::where('id',Auth::user()->id)->with('jobadder_details')->first();
        $new_token = Session::get('token');
        // dd($user);
        if (!isset($new_token)) {
            return response()->json(['error' => 'Token not found']);
        }
        $fullname = Session::get('FullName');
        $account_email = Session::get('Email');

        if (Cache::has('jobadder-'.Auth::user()->id)) {
            // Data exists in cache
            $jobadder= Cache::get('jobadder-'.Auth::user()->id); // Retrieve data from cache
        } else {
            // Remove a specific item from cache
            // Cache::forget('cache_key');
            // Data doesn't exist in cache, fetch it from the source
            $jobadder=JobadderController::jobadder_data();
            Cache::put('jobadder-'.Auth::user()->id, $jobadder, 86400); // Store data for a specified number of minutes
        }

        //  dd($jobadder);

        if($jobadder==null || !isset($jobadder['jobs']['items'])){
            return redirect()->route('client_error_page');
        }else if($request->has('startDate') && $request->has('date_option')){
            $dateOption = $request->input('date_option');
            $dateOption = 'week';
            // dd($request->startDate);
            if($request->startDate==null && $request->endDate==null){
                //  dd($request->startDate);
                $startDate = null;
                $endDate = null;
                if ($dateOption === 'week') {
                    // Get the start and end date of the current week
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                } elseif ($dateOption === 'month') {
                    // Get the start and end date of the current month
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                } elseif ($dateOption === 'year') {
                    // Get the start and end date of the current year
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                }
            }else{
                $startDate=$request->startDate;
                $endDate=$request->endDate;
            }
            $jobs['items'] = $this->checkDateRange($jobadder['jobs'],$startDate,$endDate);
            $interviews['items'] = $this->checkDateRange($jobadder['interviews'],$startDate,$endDate);
            $contacts['items'] = $this->checkDateRange($jobadder['contacts'],$startDate,$endDate);
            $candidates['items'] = $this->checkDateRange($jobadder['candidates'],$startDate,$endDate);
            // $jobboards['items'] = $this->checkDateRange($jobadder['jobboards'],$startDate,$endDate);
            $placements['items'] = $this->checkDateRange($jobadder['placements'],$startDate,$endDate);
            // dd($jobs);
            if($dateOption != 'custom') {
                $graph_data['jobs'] = JobadderController::dates_data($jobadder['jobs'], $dateOption) ?? null;
                $graph_data['interviews'] = JobadderController::dates_data($jobadder['interviews'], $dateOption) ?? null;
                $graph_data['contacts'] = JobadderController::dates_data($jobadder['contacts'], $dateOption) ?? null;
                $graph_data['candidates'] = JobadderController::dates_data($jobadder['candidates'], $dateOption) ?? null;
            }else{
                // dd($jobs);
                $graph_data['jobs'] = JobadderController::dates_data($jobs, 'year') ?? null;
                $graph_data['interviews'] = JobadderController::dates_data($interviews, 'year') ?? null;
                $graph_data['contacts'] = JobadderController::dates_data($contacts, 'year') ?? null;
                $graph_data['candidates'] = JobadderController::dates_data($candidates, 'year') ?? null;
            }

            // dd($graph_data['candidates']);
            return view('client.jobadder',compact('graph_data','jobs','contacts','candidates','fullname','account_email','interviews','placements','new_token','dateOption','startDate','endDate'));

        } else{
            // Get the start and end date of the current year
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            $jobs['items'] = $this->checkDateRange($jobadder['jobs'],$startDate,$endDate);
            $interviews['items'] = $this->checkDateRange($jobadder['interviews'],$startDate,$endDate);
            $contacts['items'] = $this->checkDateRange($jobadder['contacts'],$startDate,$endDate);
            $candidates['items'] = $this->checkDateRange($jobadder['candidates'],$startDate,$endDate);
            $placements['items'] = $this->checkDateRange($jobadder['placements'],$startDate,$endDate);

            $graph_data['jobs'] = JobadderController::dates_data($jobadder['jobs'], 'year') ?? null;
            $graph_data['interviews'] = JobadderController::dates_data($jobadder['interviews'], 'year') ?? null;
            $graph_data['contacts'] = JobadderController::dates_data($jobadder['contacts'], 'year') ?? null;
            $graph_data['candidates'] = JobadderController::dates_data($jobadder['candidates'], 'year') ?? null;

            // dd( $graph_data['interviews']);
            return response()->json(compact('graph_data', 'jobs', 'contacts', 'candidates', 'fullname', 'account_email', 'interviews', 'placements', 'new_token'));

        }

    }

    //To download candidate cv
    public function get_CV_Attachment(Request $request)
    {
      $new_token = $request->new_token;
      $candidate_id = $request->candidate_id;
      if(isset($candidate_id) && $candidate_id!= "")
      {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.jobadder.com/v2/candidates/".$candidate_id."/attachments",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". $new_token,
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response);
        // dd($response);
        if($response !="")
        {
          // dd('hi');
          $attachment_id = $response->items[0]->attachmentId;
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.jobadder.com/v2/candidates/".$candidate_id."/attachments/".$attachment_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Authorization: Bearer ". $new_token,
            ),
          ));
          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          $file = $response;

          $filename = "Candidate_CV_".$candidate_id.".pdf";

          header('Cache-Control: public');
          header('Content-type: application/pdf');
          header('Content-Disposition: attachment; filename="'.$filename.'"');
          header('Content-Length: '.strlen($file));
          echo $file;
        }
      }
    }

    // //To display on dashboard
    public static function dashboard_jobadder_data(Request $request)
    {
        dd(Session::all());
        // dd($request->toArray());
        $new_token = Session::get('token');
        dd($new_token);
        $user = User::where('id', Auth::user()->id)->with('jobadder_details')->first();

            if((!isset($new_token) || $new_token==null) && $user->jobadder_details['token']!=null){

                $user = User::where('id',Auth::user()->id)->with('jobadder_details')->first();
                $provider = new \RolandSaven\OAuth2\Client\Provider\JobAdder([
                    //For local
                    'clientId'          => Config::get('app.jobadder_details.clientId'),
                    'clientSecret'      => Config::get('app.jobadder_details.clientSecret'),
                    'scope'             => Config::get('app.jobadder_details.scope'),
                    'redirectUri'       => Config::get('app.jobadder_details.redirectUri'),
                ]);

                //save to jobadder
                $JobadderDetail = JobadderDetail::where('user_id',Auth::user()->id)->first();

                if(isset($JobadderDetail['code']) && $JobadderDetail['code']!=null){
                    $_GET['state'] = $JobadderDetail['state'];
                    Session::put('oauth2state', $JobadderDetail['state']);
                    $_GET['code'] = $JobadderDetail['code'];
                    $_GET['refresh_token'] = 'yes';
                }else if(isset($_GET['code']) && $_GET['code'] != "" && isset($_GET['state']) && $_GET['state'] != "")
                {
                    $JobadderDetail['code'] = $_GET['code'];
                    $JobadderDetail['state'] = $_GET['state'];
                    $JobadderDetail->save();
                    Session::put('oauth2state', $_GET['state']);
                }
                if (!isset($_GET['code'])) {
                    // If we don't have an authorization code then get one
                    $authUrl = $provider->getAuthorizationUrl();
                    Session::put('oauth2state', $provider->getState());
                    header('Location: '.$authUrl);
                    exit;

                    // Check given state against previously stored one to mitigate CSRF attack
                } elseif (empty($_GET['state']) || ($_GET['state'] !== Session::get('oauth2state'))) {
                    Session::forget('oauth2state');
                    exit('Invalid state');

                } else {

                    if(isset($_GET['refresh_token']) && $_GET['refresh_token'] == 'yes') {
                        // Try to get an access token (using the authorization code grant)
                        $token = $provider->getAccessToken('refresh_token', [
                          'refresh_token' => $JobadderDetail['refresh_token']
                        ]);
                        $JobadderDetail['refresh_token_response'] = json_encode($token);
                    } else {
                        // Try to get an access token (using the authorization code grant)
                        $token = $provider->getAccessToken('authorization_code', [
                            'code' => $_GET['code']
                        ]);
                        $JobadderDetail['auth_response'] = json_encode($token);
                    }
                      $JobadderDetail['refresh_token'] = $token->getRefreshToken();
                      $JobadderDetail->save();
                      // Optional: Now you have a token you can look up a users profile data
                      try {
                          // We got an access token, let's now get the user's details
                          $account = $provider->getResourceOwner($token);
                          // Use these details to create a new profile
                        Session::put('FullName', $account->getFullName());
                        $fullname = Session::get('FullName');
                        Session::put('Email', $account->getEmail());
                        $account_email = Session::get('Email');
                      } catch (Exception $e) {
                          // Failed to get user details
                          exit('Oh dear...');
                      }
                        // Use this to interact with an API on the users behalf
                        Session::put('token', $token->getToken());
                        $new_token = Session::get('token');
                        $JobadderDetail['token'] = $token->getToken();
                        $JobadderDetail->save();
                }
            }else if((!isset($new_token) || $new_token==null)){
                return $dashboard_data ?? null;
            }

        if(isset($new_token)) {
            $fullname = Session::get('FullName');
            $account_email = Session::get('Email');
            if (Cache::has('jobadder-'.Auth::user()->id)) {
                // Data exists in cache
                $jobadder= Cache::get('jobadder-'.Auth::user()->id); // Retrieve data from cache
            } else {
                // Data doesn't exist in cache, fetch it from the source
                $jobadder=JobadderController::jobadder_data();
                Cache::put('jobadder-'.Auth::user()->id, $jobadder, 86400); // Store data for a specified number of minutes
            }
        }
        //  dd($jobadder);
        if($jobadder==null || !isset($jobadder['jobs']['items'])){
            return $dashboard_data ?? null;
        }
        else if($request->has('startDate') && $request->has('date_option')){
                    $dateOption = $request->input('date_option');
                if($request->startDate==null && $request->endDate==null){
                    $startDate = null;
                    $endDate = null;
                    if ($dateOption === 'week') {
                        // Get the start and end date of the current week
                        $startDate = Carbon::now()->startOfWeek();
                        $endDate = Carbon::now()->endOfWeek();
                    } elseif ($dateOption === 'month') {
                        // Get the start and end date of the current month
                        $startDate = Carbon::now()->startOfMonth();
                        $endDate = Carbon::now()->endOfMonth();
                    } elseif ($dateOption === 'year') {
                        // Get the start and end date of the current year
                        $startDate = Carbon::now()->startOfYear();
                        $endDate = Carbon::now()->endOfYear();
                    }
                }else{
                    $startDate=$request->startDate;
                    $endDate=$request->endDate;
                }
                $jobs['items'] = JobadderController::checkDateRange($jobadder['jobs'],$startDate,$endDate);
                $interviews['items'] = JobadderController::checkDateRange($jobadder['interviews'],$startDate,$endDate);
                $contacts['items'] = JobadderController::checkDateRange($jobadder['contacts'],$startDate,$endDate);
                $candidates['items'] = JobadderController::checkDateRange($jobadder['candidates'],$startDate,$endDate);
                    // dd($jobs);
                    $dashboard_data[0] = isset($jobs['items']) ? count($jobs['items']): 0;
                    $dashboard_data[1] = isset($interviews['items']) ? count($interviews['items']): 0;
                    $dashboard_data[2] = isset($contacts['items']) ? count($contacts['items']): 0;
                    $dashboard_data[3] = isset($candidates['items']) ? count($candidates['items']): 0;

                    if($dateOption != 'custom') {
                        $dashboard_data['jobs_graph'] = JobadderController::dates_data($jobadder['jobs'], $dateOption) ?? null;
                        $dashboard_data['interviews_graph'] = JobadderController::dates_data($jobadder['interviews'], $dateOption) ?? null;
                        $dashboard_data['contacts_graph'] = JobadderController::dates_data($jobadder['contacts'], $dateOption) ?? null;
                        $dashboard_data['candidates_graph'] = JobadderController::dates_data($jobadder['candidates'], $dateOption) ?? null;
                    }else{
                        $dashboard_data['jobs_graph'] = JobadderController::dates_data($jobs, 'year') ?? null;
                        $dashboard_data['interviews_graph'] = JobadderController::dates_data($interviews, 'year') ?? null;
                        $dashboard_data['contacts_graph'] = JobadderController::dates_data($contacts,'year') ?? null;
                        $dashboard_data['candidates_graph'] = JobadderController::dates_data($candidates , 'year') ?? null;
                    }
                    // dd($dashboard_data);
                return $dashboard_data;

        } else{
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $jobs['items'] = JobadderController::checkDateRange($jobadder['jobs'],$startDate,$endDate);
            $interviews['items'] = JobadderController::checkDateRange($jobadder['interviews'],$startDate,$endDate);
            $contacts['items'] = JobadderController::checkDateRange($jobadder['contacts'],$startDate,$endDate);
            $candidates['items'] = JobadderController::checkDateRange($jobadder['candidates'],$startDate,$endDate);

            $dashboard_data['jobs_graph'] = JobadderController::dates_data($jobs, 'year') ?? null;
            $dashboard_data['interviews_graph'] = JobadderController::dates_data($interviews, 'year') ?? null;
            $dashboard_data['contacts_graph'] = JobadderController::dates_data($contacts,'year') ?? null;
            $dashboard_data['candidates_graph'] = JobadderController::dates_data($candidates , 'year') ?? null;

            $dashboard_data[0] = isset($jobs['items']) ? count($jobs['items']): 0;
            $dashboard_data[1] = isset($interviews['items']) ? count($interviews['items']): 0;
            $dashboard_data[2] = isset($contacts['items']) ? count($contacts['items']): 0;
            $dashboard_data[3] = isset($candidates['items']) ? count($candidates['items']): 0;
            $dashboard_data[4]  = $fullname ?? null;
            $dashboard_data[5] = $account_email ?? null;
            return $dashboard_data;
        }
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public static function client_error_page(){
        return view('client.error_page');
    }

    public static function jobadder_data(){
            $JobadderDetail = JobadderDetail::where('user_id',Auth::user()->id)->first();

            $new_token = $JobadderDetail['token'];

            $curl = curl_init();
            $api = ['jobs','contacts','candidates','interviews','placements',];
            //jobs
            for($i=0;$i<sizeof($api);$i++) {
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.jobadder.com/v2/$api[$i]/?limit=1000",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ". $new_token,
                "cache-control: no-cache"
                ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                if(isset($err) && $err!=null){
                    return null;
                }
                $jobadder[$api[$i]] = json_decode($response, true);

                // check if itemcount< totalcount & next page is there then run curl again and add items to last
                if(isset($jobadder[$api[$i]]['totalCount']) && isset($jobadder[$api[$i]]['items'])){
                    if(($jobadder[$api[$i]]['totalCount'] > count($jobadder[$api[$i]]['items'])) && isset($jobadder[$api[$i]]['links']['next'])) {
                        $next_link = $jobadder[$api[$i]]['links']['next'];
                        $condition = false;

                        // Your loop
                        while (!$condition) {

                            curl_setopt_array($curl, array(
                                CURLOPT_URL => "$next_link",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_HTTPHEADER => array(
                                "Authorization: Bearer " . $new_token,
                                "cache-control: no-cache"
                                ),
                            ));

                            $response1 = curl_exec($curl);
                            $err = curl_error($curl);
                            if(isset($err) && $err != null) {
                                return null;
                            }

                            $jobadder1[$api[$i]] = json_decode($response1, true);
                            if(isset($jobadder1[$api[$i]]['links']['next'])){
                                $next_link = $jobadder1[$api[$i]]['links']['next'];
                            }
                            // dd($jobadder1[$api[$i]]);

                            $jobadder[$api[$i]]['items'] = array_merge($jobadder[$api[$i]]['items'], $jobadder1[$api[$i]]['items']);

                            if ($jobadder[$api[$i]]['totalCount'] <= count($jobadder[$api[$i]]['items'])) {
                                $condition = true;
                            }
                        }
                    }
                }
                curl_close($curl);

            }
            return $jobadder ?? null;
    }

    public static function checkDateRange($array_data,$startDate,$endDate)
    {
        $currentDate = Carbon::now();
        $filtered_data = [];

        foreach ($array_data['items'] as $job) {
            if(isset($job['updatedAt'])){
                $createdAt = Carbon::parse($job['updatedAt']);
                // Check if the job falls within the desired date range
                if ($createdAt >= $startDate && $createdAt <= $endDate) {
                    $filtered_data[] = $job;
                }
            }else{
                $filtered_data[] = $job;
            }
        }

        return $filtered_data;
    }

    public static function dates_data($filter_data, $dateOption)
    {
        // Get the selected option (year, month, or week) from the user
        $selectedOption = $dateOption; // Replace with user's selected option
        // Data exists in cache
        // Initialize variables for start date and end date
        $start_date = null;
        $end_date = null;
        // $e = JobadderController::checkDateRange($jobadder['jobs'], '2023-02-01','2023-02-28');
        // dd($filter_data, $dateOption);

       // Determine the start and end dates based on the selected option
        if ($selectedOption === "year") {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Define the start date as January 1st of the current year
            $start_date = Carbon::createFromDate($currentYear, 1, 1);

            // Define the end date as December 31st of the current year
            $end_date = Carbon::createFromDate($currentYear, 12, 31);
        } elseif ($selectedOption === "month") {
            // Get the current date
            $current_date = Carbon::now();

            // Define the start date as the first day of the current month
            $start_date = $current_date->copy()->firstOfMonth();

            // Define the end date as the last day of the current month
            $end_date = $current_date->copy()->lastOfMonth();
        } elseif ($selectedOption === "week") {
            // Get the current date
            $current_date = Carbon::now();

            // Define the start date as the beginning of the current week (Sunday)
            $start_date = $current_date->copy()->startOfWeek();

            // Define the end date as the end of the current week (Saturday)
            $end_date = $current_date->copy()->endOfWeek();
        }

        // Initialize an associative array to store the date ranges
        $date_ranges = [];

        // Loop through the dates
        $current_date = $start_date->copy();
        while ($current_date <= $end_date) {
            // Calculate the end of the current period (day, week, or month)
            if ($selectedOption === "month") {
                $end_of_period = $current_date->copy()->endOfWeek();
            } elseif ($selectedOption === "week") {
                $end_of_period = $current_date->copy()->endOfDay();
            } else {
                $end_of_period = $current_date->copy()->endOfMonth();
            }

            // Determine the key based on the selected option
            $key = null;
            if ($selectedOption === "month") {
                // $key = 'week' . $current_date->format('W'); // for weeks
                // $key = $current_date->toDateString() . ':' . $end_of_period->toDateString();
                $key = date('M j', strtotime($current_date)) . ' - ' . date('M j', strtotime($end_of_period));
            } elseif ($selectedOption === "week") {
                // $key = $current_date->format('l'); // for days
                $key = date('M j', strtotime($current_date));
            } elseif ($selectedOption === "year") {
                    $key = $current_date->format('F'); // for months
            }

            // Append the date range to the associative array
            if ($key) {
                $date_ranges[$key] = $current_date->toDateString() . ':' . $end_of_period->toDateString();
            }

            // Move to the next period (day, week, or month)
            $current_date = $end_of_period->copy()->addDay();
        }
        // dd($date_ranges);

        // Print or use the date ranges as needed
        // foreach ($date_ranges as $key => $date_range) {
        //     echo $key . " => " .$date_range . "\n";
        // }

        $date_data = [];
                // Print the date ranges or use them as needed
        foreach ($date_ranges as $key => $date_range) {
            // echo $date_range . "\n";
            if(isset($filter_data)){
                $dates = explode(':',$date_range);
                $date1 = Carbon::parse($dates[0])->startOfDay(); // Set to the start of the day (00:00:00)
                $date2 = Carbon::parse($dates[1])->endOfDay();   // Set to the end of the day (23:59:59)
                $date_data[$key] = count(JobadderController::checkDateRange($filter_data, $date1, $date2)) ?? null;
            }
        }
        // dd($date_data);

        $graph = [];
        $j_key = 0;
        if(isset($filter_data)) {
            foreach ($date_data as $key => $week) {
                $graph[$j_key]['name'] = $key;
                $graph[$j_key]['y'] = $week;
                $j_key++;
            }
        }
        // dd($graph);

        return $graph ?? null;
        // return view('client.jobadder_new', compact('graph'));

    }
}
