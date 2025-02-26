<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Job;
use App\Models\User;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterestJobNotification;

class JobController extends Controller
{
    // Display the job sharing page with a list of jobs
    public function index(Request $request)
    {
        $jobs = Job::where('user_id', '=', Auth::user()->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['jobs' => $jobs], 200);
    }

    public function getJob(string $job_id)
    {

        $job = Job::where('id', $job_id)->first();

        return response()->json(['job' => $job]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'job_title' => 'required|string|max:255',
                'job_type' => 'required|in:Permanent,Contract',
                'recruitment_type' => 'required|in:Contingent,Retained',
                'industry' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'salary' => 'required|numeric',
                'start_date' => 'required|date',
                'margin_agreed' => 'required|numeric',
                'fee' => 'required|numeric',
                'job_description' => 'required|string',
            ]);

            $job = new Job;

            $job->user_id = Auth::user()->id;
            $job->job_title = $request->job_title;
            $job->job_type  = $request->job_type;
            $job->recruitment_type = $request->recruitment_type;
            $job->industry = $request->industry;
            $job->location  = $request->location;
            $job->salary = $request->salary;
            $job->margin_agreed = $request->margin_agreed;
            $job->fee = $request->fee;
            $job->start_date  = $request->start_date;
            $job->job_description  = $request->job_description;
            $job->status  = $request->status;
            $job->salary_currency  = $request->salary_currency;
            $job->fee_currency  = $request->fee_currency;

            $job->save();

            // Send email to matching profiles
            $this->sendNewJobEmail($job);

            return response()->json(['job' => $job], 200);
        } catch (\Exception $e) {

            return response()->json(['msg' => $e->getmessage()], 400);
        }
    }

    public function update(Request $request, string $job_id)
    {
        try {
            $job = Job::findOrFail($job_id);

            // Authorization check
            if ($job->user_id != Auth::id()) {
                return response()->json(['err' => "Permission denied!"], 403);
            }

            $job->job_title = $request->job_title;
            $job->job_type  = $request->job_type;
            $job->recruitment_type = $request->recruitment_type;
            $job->industry = $request->industry;
            $job->location  = $request->location;
            $job->salary = $request->salary;
            $job->margin_agreed = $request->margin_agreed;
            $job->fee = $request->fee;
            $job->start_date  = $request->start_date;
            $job->job_description  = $request->job_description;
            $job->status  = $request->status;
            $job->salary_currency  = $request->salary_currency;
            $job->fee_currency  = $request->fee_currency;


            $job->save();

            return response()->json(['job' => $job], 200);
        } catch (\Exception $e) {
            return response()->json(["err" => $e->getmessage()], 400);
        }
    }

    public function destroy(string $job_id)
    {
        $job = Job::find($job_id);

        $job->delete();

        return response()->json(['job' => $job], 200);
    }

    public function jobshared_list(Request $request)
    {
        $jobs = Job::where('user_id', "!=", $request->query('user_id'))->with('user')->get();

        return response()->json(['jobs' => $jobs], 200);
    }

    public function apply(Request $request)
    {
        if ($request->has('user_id') && $request->user_id) {

            $user = User::where('id', $request->user_id)->first();

            $job = Job::where('id', $request->job_id)->first();

            $interestedjob = [
                'username' => $user->name,
                'job_title' => $job->job_title,
                'questions' => $job->questions
            ];

            Mail::to($user->email)->send(new InterestJobNotification($interestedjob));
        }
    }

    public function search(Request $request)
    {


        // Check if the authenticated user's role is '1' (admin or superuser)

        $query = Job::query();

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->isShared ? "!=" : "=", $request->user_id);
        }


        // If there's a search query, filter jobs by title
        if ($request->has('job_title') && $request->job_title) {
            $query->where('job_title', 'like', '%' . $request->job_title . '%');
        }

        // Filter by location
        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by industry
        if ($request->has('industry') && $request->industry) {
            $query->where('industry', 'like', '%' . $request->industry . '%');
        }

        // Filter by salary
        if ($request->has('salary') && $request->salary) {
            $query->where('salary', '>=', $request->salary); // Assuming salary is a numeric field
        }
        if ($request->has('jobType') && $request->jobType) {
            $query->where('job_type', $request->jobType);
        }
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('recruitment_type') && $request->recruitment_type) {
            $query->where('recruitment_type', $request->recruitment_type);
        }
        if (!$request->has('start_date')) {
            $query->whereDate('start_date', '=', $request->start_date);
        }
        $jobs = $query->with('user')->get();

        return response()->json(['jobs' => $jobs], 200);
    }

    protected function sendNewJobEmail($job)
    {
        // Logic to find profiles with matching keywords (location, industry, etc.)
        // Example: Fetch profiles from the database
        $community_list = Community::where('location', 'like', '%' . $job->location . '%')
            ->orWhere('industry', 'like', '%' . $job->industry . '%')
            ->get();

        foreach ($community_list as $profile) {
            Mail::to($profile->email)->send(new NewJobNotification($job));
        }
    }
}
