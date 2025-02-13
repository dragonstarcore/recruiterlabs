<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webfox\Xero\OauthCredentialManager;
use App\Http\Controllers\Controller\XeroController;
use Analytics;
use Spatie\Analytics\Period;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $analytics_view_id = \Auth::user()->jobadder_details->analytics_view_id ?? null;
        //Start:  to set client id and secret keys
        \Config::set('xero.oauth.client_id', \Auth::user()->xero_details->client_id ?? null);
        \Config::set('xero.oauth.client_secret', \Auth::user()->xero_details->client_secret ?? null);
        //End:  to set client id and secret keys
        \Config::set('analytics.view_id', $analytics_view_id ?? null);
        // dd(\Auth::user()->role_type);

        if (\Auth::user()->role_type == 2) {
            // dd('1');
            //Start : Xero data
            try {
                $xero_result =  \App::call([\App\Http\Controllers\XeroController::class, 'dashboard_data']);
                // Process the fetched analytics data
            } catch (\Exception $e) {
                // Handle any exceptions that might occur during the data retrieval
                // You can log the error, display an error message, or take appropriate action
                \Log::error('An error occurred while fetching analytics data: ' . $e->getMessage());
            }
            $xero['data'] = $xero_result[1] ?? null;
            $xero['invoices_array'] = $xero_result[0] ?? null;
            $xero['total_cash'] = $xero_result[2] ?? null;
            $xero['balance']  = $xero_result[3] ?? null;
            $xero['organisationName'] = $xero_result[4] ?? null;
            $xero['username'] = $xero_result[5] ?? null;
            //end : Xero data

            //Start : Analytics data
            if (isset($analytics_view_id) && $analytics_view_id != null) {
                $GA_error = null;

                if (preg_match('/^\d{7,}$/', $analytics_view_id)) {
                    // It's a valid Google Analytics View ID (without 'ga:')
                    $page_views = 0;
                    $total_visitors = 0;
                    try {
                        $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
                        //  dd($analyticsData);
                        if (isset($analyticsData)) {
                            foreach ($analyticsData as $analytic_data) {
                                $total_visitors += $analytic_data['activeUsers'];
                                $page_views += $analytic_data['screenPageViews'];
                            }
                        }
                        // Process the fetched analytics data
                    } catch (\Exception $e) {
                        // Handle any exceptions that might occur during the data retrieval
                        // You can log the error, display an error message, or take appropriate action
                        \Log::error('An error occurred while fetching analytics data: ' . $e->getMessage());
                    }


                    // Now, $ga_Property_id contains the Property ID with 'ga:' attached
                } else {
                    // It's not a valid Google Analytics Property ID
                    $page_views = null;
                    $total_visitors = null;
                    $GA_error = 'Please enter valid Google Analytics Property ID';
                }
                // dd($page_views);
            } else {

                $page_views = null;
                $total_visitors = null;
                $GA_error = null;
            }
            //End: Analytics data

            //Start : JObadder data
            try {
                $result =  \App::call([\App\Http\Controllers\JobadderController::class, 'dashboard_jobadder_data']);
                // Process the fetched analytics data
            } catch (\Exception $e) {
                // Handle any exceptions that might occur during the data retrieval
                // You can log the error, display an error message, or take appropriate action
                \Log::error('An error occurred while fetching analytics data: ' . $e->getMessage());
            }
            //  dd($result);
            $jobadder['jobs'] = isset($result[0]) ? $result[0] : 0;
            $jobadder['interviews'] = isset($result[1]) ? $result[1] : 0;
            $jobadder['contacts'] = isset($result[2]) ? $result[2] : 0;
            $jobadder['candidates'] = isset($result[3]) ? $result[3] : 0;
            $jobadder['fullname']  = isset($result[4]) ? $result[4] : null;
            $jobadder['account_email'] = isset($result[5]) ? $result[5] : null;
            $jobadder['jobs_graph'] = isset($result['jobs_graph']) ? $result['jobs_graph'] : null;
            $jobadder['interviews_graph'] = isset($result['interviews_graph']) ? $result['interviews_graph'] : null;
            $jobadder['contacts_graph'] = isset($result['contacts_graph']) ? $result['contacts_graph'] : null;
            $jobadder['candidates_graph'] = isset($result['candidates_graph']) ? $result['candidates_graph'] : null;

            //End: JObadder data
            // dd($jobadder);
        } else {
            // dd('hi');
            $xero['data'] = null;
            $xero['invoices_array'] = null;
            $xero['total_cash'] =  null;
            $xero['balance']  =  null;
            $page_views = 0;
            $total_visitors = 0;
            $organisationName = null;
            $username =  null;
            $jobadder = null;
            $GA_error = null;
        }

        return response()->json(['xero' => $xero, 'page_views' => $page_views, 'total_visitors' => $total_visitors, 'jobadder' => $jobadder, 'GA_error' => $GA_error,], 200);
    }

    public function check_analytics_view_id()
    {
        if (isset(\Auth::user()->jobadder_details->analytics_view_id) && \Auth::user()->jobadder_details->analytics_view_id != null) {
            return redirect('/admin/filament-google-analytics-dashboard');
        } else {
            return view('client.google_analytics_error');
        }
    }

    public function getAnalyticsData(Request $request)
    {
        $analytics_view_id = \Auth::user()->jobadder_details->analytics_view_id ?? null;

        if (!$analytics_view_id) {
            return response()->json(['error' => 'Analytics View ID is required'], 400);
        }

        \Config::set('analytics.view_id', $analytics_view_id ?? null);

        $period = $request->input('period', 'lastweek');

        $periodMapping = [
            'today' => Period::days(1),
            'yesterday' => Period::create(now()->subDay(), now()->subDay()),
            'lastweek' => Period::days(7),
            'lastmonth' => Period::months(1),
            'lastyear' => Period::years(1),
        ];

        $selectedPeriod = $periodMapping[$period] ?? Period::days(1);

        try {
            $pageViews = Analytics::fetchVisitorsAndPageViewsByDate($selectedPeriod);
            $totalPageViews = Analytics::fetchTotalVisitorsAndPageViews($selectedPeriod);
            $mostVisitedPages = Analytics::fetchMostVisitedPages($selectedPeriod, 8);
            $topReferrers = Analytics::fetchTopReferrers($selectedPeriod, 8);
            $topCountries = Analytics::fetchTopCountries($selectedPeriod, 8);
            $topOperatingSystems = Analytics::fetchTopOperatingSystems($selectedPeriod, 5);

            return response()->json([
                'pageViews' => $pageViews,
                'totalPageViews' => $totalPageViews,
                'mostVisitedPages' => $mostVisitedPages,
                'topReferrers' => $topReferrers,
                'topCountries' => $topCountries,
                'topOperatingSystems' => $topOperatingSystems,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('An error occurred while fetching analytics data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch analytics data'], 500);
        }
    }
}
