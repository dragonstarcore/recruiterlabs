<!DOCTYPE html>
<html>
<head>
    <title>  Job Interesting Notification</title>
</head>
<body>
    <h1>{{ $jobDetails['username']}} interested in working on this vacancy</h1> 
    <p>Hello, {{$jobDetails['job_title']}} - job has been interested with the following questions:</p>
    
   
    <p>
        {{ $jobDetails['questions']}}  
    </p>
    <p>Thank you for using our platform!</p>
</body>
</html>