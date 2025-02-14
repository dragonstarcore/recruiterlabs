<!DOCTYPE html>
<html>
<head>
    <title>New Job Notification</title>
</head>
<body>
    <h1>New Job Posted</h1>
    <p>Hello, a new job has been posted with the following details:</p>
    
    <ul>
        <li><strong>Job Title:</strong> {{ $jobDetails['title'] }}</li>
        <li><strong>Location:</strong> {{ $jobDetails['location'] }}</li>
        <li><strong>Salary:</strong> ${{ number_format($jobDetails['salary'], 2) }}</li>
        <li><strong>Start Date:</strong> {{ $jobDetails['start_date'] }}</li>
    </ul>

    <p>Thank you for using our platform!</p>
</body>
</html>