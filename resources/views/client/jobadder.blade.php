@extends('layouts.app')
@section('content')
<div class="content">


    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>

    <div class="card">
        <div class="card-header">
            <form id="filter_data" action="{{ route('search_jobadder') }}" method="post">
                @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="mb-0">Jobadder</h5>
                            @if(isset($fullname)){{$fullname}} -@endif
                            @if(isset($account_email)){{$account_email}}@endif
                        </div>
                        @if(isset($fullname))
                            <div class="col-md-6">
                                <div class="row custom-dates" @if(isset($dateOption) && $dateOption=='custom') style="display: flex" @else style="display: none" @endif >
                                    <div class="col-md-3 me-1" style="width: 170px;">
                                        <input type="date" name="startDate" onfocus="this.showPicker()" id="startDate" class="form-control" placeholder="Start date" @if(isset($startDate)) value="{{ $startDate }}" @endif>
                                    </div>
                                    <div class="col-md-3 me-1" style="width: 170px;">
                                        <input type="date" name="endDate" onfocus="this.showPicker()" id="endDate" class="form-control " placeholder="End date" @if(isset($endDate)) value="{{ $endDate }}" @endif>
                                    </div>
                                    <div class="col-md-2 me-1">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 ml-auto">
                                <select class="form-select" name="date_option" id="selection" >
                                    <option value="" disabled selected>Filter By</option>
                                    <option @if(isset($dateOption) && $dateOption=='year') selected @endif value="year">This Year</option>
                                    <option @if(isset($dateOption) && $dateOption=='month') selected @endif value="month">This Month</option>
                                    <option @if(isset($dateOption) && $dateOption=='week') selected @endif value="week">This Week</option>
                                    <option @if(isset($dateOption) && $dateOption=='custom') selected @endif value="custom">Custom</option>
                                </select>
                            </div>
                        @endif
                    </div>
            </form>
        </div>

        @if(!isset($error))
            <div class="card-body">
                <!-- Quick stats boxes -->
                <div class="row">
                    <div class="col-lg-1" >
                    </div>
                    <div class="col-lg-2" onclick="show('jobs')">
                        <!-- Members online -->
                        <div class="card bg-primary text-white jobadder_cards">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="mb-0">@if(isset($jobs['items'])) {{count($jobs['items'])}} @endif</h3>
                                    <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down "></i></span>
                                </div>

                                <div>
                                    Jobs
                                    <div class="fs-sm opacity-75"> &nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!-- /members online -->
                    </div>

                    <div class="col-lg-2" onclick="show('contacts')">
                        <!-- Members online -->
                        <div class="card bg-pink text-white jobadder_cards">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="mb-0"> @if(isset($contacts['items'])) {{count($contacts['items'])}}  @endif</h3>
                                    <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down "></i></span>
                                </div>

                                <div>
                                    Contacts
                                    <div class="fs-sm opacity-75"> &nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!-- /members online -->
                    </div>
                    <div class="col-lg-2">
                        <!-- Members online -->
                        <div class="card bg-teal  text-white jobadder_cards">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="mb-0"> @if(isset($placements['items'])) {{count($placements['items'])}}  @endif</h3>
                                    <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down "></i></span>
                                </div>

                                <div>
                                    Placements
                                    <div class="fs-sm opacity-75"> &nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!-- /members online -->
                    </div>
                    <div class="col-lg-2" onclick="show('interviews')">
                        <!-- Members online -->
                        <div class="card bg-warning text-white jobadder_cards">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="mb-0"> @if(isset($interviews['items'])) {{count($interviews['items'])}} @endif</h3>
                                    <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down "></i></span>
                                </div>

                                <div>
                                    Interviews
                                    <div class="fs-sm opacity-75"> &nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!-- /members online -->
                    </div>
                    <div class="col-lg-2" onclick="show('candidates')">
                        <!-- Members online -->
                        <div class="card bg-info text-white jobadder_cards">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="mb-0"> @if(isset($candidates['items'])) {{count($candidates['items'])}} @endif</h3>
                                    <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down "></i></span>
                                </div>

                                <div>
                                    Candidates
                                    <div class="fs-sm opacity-75"> &nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!-- /members online -->
                    </div>
                </div>
                <!-- /quick stats boxes -->
            </div>

        @else
            <div class="card-body">
                <div class="alert alert-danger mb-2">{{$error}}.
                    <p class="mb-2">
                        <a href="{{ url('my_business#visit_link') }}" class="">
                            Visit here
                        </a>
                        to add relevant details to connect Jobadder account.
                    </p>
                </div>
            </div>
        @endif
        <!-- Place the loading spinner inside the card element -->
        <div class="loading-spinner" id="loading-spinner">
        </div>
    </div>
    {{-- Start: detail item data on card click data  --}}
    <div class="card jobs" style="display: none">
        <div class="card-body ">
            <h5 class="text-center">Jobs @if(isset($jobs['items']))[ {{count($jobs['items'])}} ]@endif</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered employee_list">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>JobTitle</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($jobs['items']))
                    @foreach ($jobs['items'] as $job)
                        <tr>
                            <td>@if(isset($job['jobId'])){{$job['jobId']}}@endif</td>
                            <td>@if(isset($job['jobTitle'])){{$job['jobTitle']}}@endif</td>
                            <td>@if(isset($job['company'])){{$job['company']['name']}}@endif</td>
                            <td>@if(isset($job['contact']['firstName'])){{$job['contact']['firstName']}}@endif @if(isset($job['contact']['lastName'])){{$job['contact']['lastName']}}@endif</td>
                            <td>@if(isset($job['status'])){{$job['status']['name']}}@endif</td>
                            <td>@if(isset($job['owner']['firstName'])){{$job['owner']['firstName']}}@endif @if(isset($job['owner']['lastName'])){{$job['owner']['lastName']}}@endif</td>
                            <td>@if(isset($job['updatedAt'])){{$job['updatedAt']}}@endif</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="card contacts" style="display: none">
        <div class="card-body ">
            <h5 class="text-center">Contacts @if(isset($contacts['items']))[ {{count($contacts['items'])}} ] @endif</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered employee_list">
                <thead>
                    <tr>
                        <th>ContactId</th>
                        <th>Position</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($contacts['items']))
                    @foreach ($contacts['items'] as $contact)
                        <tr>
                            <td>@if(isset($contact['contactId'])){{$contact['contactId']}}@endif</td>
                            <td>@if(isset($contact['position'])){{$contact['position']}}@endif</td>
                            <td>@if(isset($contact['company'])){{$contact['company']['name']}}@endif</td>
                            <td>@if(isset($contact['firstName'])){{$contact['firstName']}}@endif @if(isset($contact['lastName'])){{$contact['lastName']}}@endif</td>
                            <td>@if(isset($contact['email'])){{$contact['email']}}@endif</td>
                            <td>@if(isset($contact['phone'])){{$contact['phone']}}@endif</td>
                            <td>@if(isset($contact['status'])){{$contact['status']['name']}}@endif</td>
                            <td>@if(isset($contact['owner']['firstName'])){{$contact['owner']['firstName']}}@endif @if(isset($contact['owner']['lastName'])){{$contact['owner']['lastName']}}@endif</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card interviews" style="display: none">
        <div class="card-body ">
            <h5 class="text-center">Interviews @if(isset($interviews['items']))[ {{count($interviews['items'])}} ] @endif</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered employee_list">
                <thead>
                    <tr>
                        <th>InterviewId</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Interviewee</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($interviews['items']))
                    @foreach ($interviews['items'] as $interview)
                        <tr>
                            <td>@if(isset($interview['interviewId'])){{$interview['interviewId']}}@endif</td>
                            <td>@if(isset($interview['type'])){{$interview['type']}}@endif</td>
                            <td>@if(isset($interview['location'])){{$interview['location']}}@endif</td>
                            <td>@if(isset($interview['interviewee']['jobTitle'])){{$interview['interviewee']['jobTitle']}}@endif</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card candidates" style="display: none">
        <div class="card-body ">
            <h5 class="text-center">Candidates @if(isset($candidates['items']))[ {{count($candidates['items'])}} ]@endif</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered employee_list">
                <thead>
                    <tr>
                        <th>CandidateId</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($candidates['items']))
                    @foreach ($candidates['items'] as $candidate)
                        <tr>
                            <td>@if(isset($candidate['candidateId'])){{$candidate['candidateId']}}@endif</td>
                            <td>@if(isset($candidate['firstName'])){{$candidate['firstName']}}@endif @if(isset($candidate['lastName'])){{$candidate['lastName']}}@endif</td>
                            <td>@if(isset($candidate['email'])){{$candidate['email']}}@endif</td>
                            <td>@if(isset($candidate['phone'])){{$candidate['phone']}}@endif</td>
                            <td>@if(isset($candidate['status']['name'])){{$candidate['status']['name']}}@endif</td>
                            <td>
                               <a target="_blank" href="{{route('get_CV_Attachment', ['new_token' => $new_token,'candidate_id' => $candidate['candidateId']])}}"> Get CV attachment </a>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- End: detail item data on card click data  --}}

    {{-- Start: graph data  --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card col-md-12">
                @if(isset($graph_data['jobs']))
                    <div class="card-body "><div id="container1"></div></div>
                @endif
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="card col-md-12">
                @if(isset($graph_data['contacts']))
                    <div class="card-body "><div id="container3"></div></div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card col-md-12">
                @if(isset($graph_data['interviews']))
                    <div class="card-body "><div id="container4"></div></div>
                @endif
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="card col-md-12 ">
                @if(isset($graph_data['candidates']))
                    <div class="card-body "><div id="container2"></div></div>
                @endif
            </div>
        </div>
    </div>
    {{-- End: graph data  --}}
</div>
<script>
    $(document).ready(function () {

            $('.employee_list').dataTable( {
                // ordering: false,
                "pageLength": 15,
                    "language": {
                    "emptyTable": "No records found "
                    }
            } );
            // Initially hide the loading spinner
            $('#loading-spinner').hide();

            // Show the loading spinner when a page reload is triggered
            $(window).on('beforeunload', function () {
                $('#loading-spinner').show();
            });

            $('#selection').on('change', function() {
                var selectedOption = $(this).val();
                // alert(selectedOption);
                if (selectedOption === 'custom') {
                    // Get the current date in the format "YYYY-MM-DD"
                    var currentDate = new Date().toISOString().split('T')[0];
                    // alert(currentDate);
                    // Calculate the start date of the current year
                    var currentYear = new Date().getFullYear();
                    var currentYearStartDate = currentYear + '-01-01';

                    // Set the minimum and maximum date attributes for the date inputs
                    $('#startDate').attr('min', currentYearStartDate);
                    $('#startDate').attr('max', currentDate);
                    $('#endDate').attr('min', currentYearStartDate);
                    $('#endDate').attr('max', currentDate);

                    $('.custom-dates').css('display', 'flex');
                } else {
                    $('#loading-spinner').show();
                    $('#startDate').val(null);
                    $('#endDate').val(null);
                    $('#filter_data').submit();
                }
            });

        //Start: jobs data
            let arr_name1 = [];
            let arr_y1 = [];
            var jobs_array =   <?php if (isset($graph_data['jobs'])) {
                                            echo json_encode($graph_data['jobs']);
                                        } else {
                                            echo json_encode([]); // Default to an empty array, for example.
                                        }
                                        ?>;

            // Display the array elements
            for(var i = 0; i <Object.keys(jobs_array).length; i++){
                // console.log(jobs_array[i]);
                arr_name1.push(jobs_array[i]['name']);
                arr_y1.push(jobs_array[i]['y']);
            }

            // console.log(arr_name1,arr_y1);
            Highcharts.setOptions({
                    colors: ['#0a6dd4']
            });
            Highcharts.chart('container1', {
                chart: {
                    type: 'column',
                    zoomType: 'y'
                },
                title: {
                    text: 'Jobs Data'
                },
                subtitle: {
                    text: 'Jobadder'
                },
                xAxis: {
                    categories: arr_name1,
                    title: {
                    text: null
                    },
                    accessibility: {
                    description: 'Weeks'
                    }
                },
                    //   yAxis: {
                    //     min: 0,
                    //     tickInterval: 2,
                    //     title: {
                    //       text: 'Exportation in billion US$'
                    //     },
                    //     labels: {
                    //       overflow: 'justify',
                    //       format: '{value}'
                    //     }
                    //   },
                plotOptions: {
                    column: {
                    dataLabels: {
                        enabled: true,
                        format: ' {y}'
                    }
                    }
                },
                tooltip: {
                    // valueSuffix: ' billion US$',
                    // valuePrefix: '£ ',
                    stickOnContact: true,
                    // backgroundColor: 'rgba(255, 255, 255, 0.93)'
                },
                legend: {
                    enabled: false
                },
                series: [
                    {
                    name: 'Count ',
                    data: arr_y1,
                    // borderColor: ['#5997DE','#RED','#RED']
                    }
                ]
            });
        // End: jobs data

        //Start: candidates data
            let arr_name2 = [];
            let arr_y2 = [];
            var candidates_array =   <?php if (isset($graph_data['candidates'])) {
                                            echo json_encode($graph_data['candidates']);
                                        } else {
                                            echo json_encode([]); // Default to an empty array, for example.
                                        }
                                        ?>;

                                    //  console.log(candidates_array);
            // Display the array elements
            for(var i = 0; i <Object.keys(candidates_array).length; i++){
                // console.log(candidates_array[i]);
                arr_name2.push(candidates_array[i]['name']);
                arr_y2.push(candidates_array[i]['y']);
            }

            // console.log(arr_name1,arr_y1);
            Highcharts.setOptions({
                    colors: ['#049aad']
            });
            Highcharts.chart('container2', {
                chart: {
                    type: 'spline',
                    zoomType: 'y'
                },
                title: {
                    text: 'Candidates Data'
                },
                subtitle: {
                    text: 'Jobadder'
                },
                xAxis: {
                    categories: arr_name2,
                    title: {
                    text: null
                    },
                    accessibility: {
                    description: 'Weeks'
                    }
                },
                    //   yAxis: {
                    //     min: 0,
                    //     tickInterval: 2,
                    //     title: {
                    //       text: 'Exportation in billion US$'
                    //     },
                    //     labels: {
                    //       overflow: 'justify',
                    //       format: '{value}'
                    //     }
                    //   },
                plotOptions: {
                    column: {
                    dataLabels: {
                        enabled: true,
                        format: ' {y}'
                    }
                    }
                },
                tooltip: {
                    // valueSuffix: ' billion US$',
                    // valuePrefix: '£ ',
                    stickOnContact: true,
                    // backgroundColor: 'rgba(255, 255, 255, 0.93)'
                },
                legend: {
                    enabled: false
                },
                series: [
                    {
                    name: 'Count ',
                    data: arr_y2,
                    // borderColor: ['#5997DE','#RED','#RED']
                    }
                ]
            });
        // End: candidates data

        //Start: contacts data
            let arr_name3 = [];
            let arr_y3 = [];
            var contacts_array =   <?php if (isset($graph_data['contacts'])) {
                                            echo json_encode($graph_data['contacts']);
                                        } else {
                                            echo json_encode([]); // Default to an empty array, for example.
                                        }
                                        ?>;

                                    //  console.log(contacts_array);
            // Display the array elements
            for(var i = 0; i <Object.keys(contacts_array).length; i++){
                // console.log(contacts_array[i]);
                arr_name3.push(contacts_array[i]['name']);
                arr_y3.push(contacts_array[i]['y']);
            }

            // console.log(arr_name1,arr_y1);
            Highcharts.setOptions({
                    colors: ['#f35c86']
            });
            Highcharts.chart('container3', {
                chart: {
                    type: 'spline',
                    zoomType: 'y'
                },
                title: {
                    text: 'Contacts Data'
                },
                subtitle: {
                    text: 'Jobadder'
                },
                xAxis: {
                    categories: arr_name3,
                    title: {
                    text: null
                    },
                    accessibility: {
                    description: 'Weeks'
                    }
                },
                    //   yAxis: {
                    //     min: 0,
                    //     tickInterval: 2,
                    //     title: {
                    //       text: 'Exportation in billion US$'
                    //     },
                    //     labels: {
                    //       overflow: 'justify',
                    //       format: '{value}'
                    //     }
                    //   },
                plotOptions: {
                    column: {
                    dataLabels: {
                        enabled: true,
                        format: ' {y}'
                    }
                    }
                },
                tooltip: {
                    // valueSuffix: ' billion US$',
                    // valuePrefix: '£ ',
                    stickOnContact: true,
                    // backgroundColor: 'rgba(255, 255, 255, 0.93)'
                },
                legend: {
                    enabled: false
                },
                series: [
                    {
                    name: 'Count ',
                    data: arr_y3,
                    // borderColor: ['#5997DE','#RED','#RED']
                    }
                ]
            });
        // End: contacts data

        //Start: interviews data
            let arr_name4 = [];
            let arr_y4 = [];
            var interviews_array =   <?php if (isset($graph_data['interviews'])) {
                                            echo json_encode($graph_data['interviews']);
                                        } else {
                                            echo json_encode([]); // Default to an empty array, for example.
                                        }
                                        ?>;

                                    //  console.log(invoices_array2);
            // Display the array elements
            for(var i = 0; i <Object.keys(interviews_array).length; i++){
                // console.log(interviews_array[i]);
                arr_name4.push(interviews_array[i]['name']);
                arr_y4.push(interviews_array[i]['y']);
            }

            // console.log(arr_name1,arr_y1);
            Highcharts.setOptions({
                    colors: ['#f58646']
            });
            Highcharts.chart('container4', {
                chart: {
                    type: 'areaspline',
                    zoomType: 'y'
                },
                title: {
                    text: 'Interviews Data'
                },
                subtitle: {
                    text: 'Jobadder'
                },
                xAxis: {
                    categories: arr_name4,
                    title: {
                    text: null
                    },
                    accessibility: {
                    description: 'Weeks'
                    }
                },
                    //   yAxis: {
                    //     min: 0,
                    //     tickInterval: 2,
                    //     title: {
                    //       text: 'Exportation in billion US$'
                    //     },
                    //     labels: {
                    //       overflow: 'justify',
                    //       format: '{value}'
                    //     }
                    //   },
                plotOptions: {
                    column: {
                    dataLabels: {
                        enabled: true,
                        format: ' {y}'
                    }
                    }
                },
                tooltip: {
                    // valueSuffix: ' billion US$',
                    // valuePrefix: '£ ',
                    stickOnContact: true,
                    // backgroundColor: 'rgba(255, 255, 255, 0.93)'
                },
                legend: {
                    enabled: false
                },
                series: [
                    {
                    name: 'Count ',
                    data: arr_y4,
                    // borderColor: ['#5997DE','#RED','#RED']
                    }
                ]
            });
        // End: interviews data
    });

    //Start: Open dates option on custom click
        function custom_dates(e){
            const customDates = document.querySelector(".custom-dates");
                if (customDates.style.display == "none") {
                    customDates.style.display = "flex";
                    customDates.style.justifyContent = "top"; // Center horizontally
                    customDates.style.alignItems = "top"; // Center vertically
                } else {
                    customDates.style.display = "none";
                }
        }
    //End: Open dates option on custom click

    //Start: To show detail view of jobs etc onclick
        function show(id){
            // assumes element with id='button'
            if($("."+id).is(':hidden')){
                $(".jobs, .contacts, .interviews, .candidates").hide();
                $("."+id).show();
            }else{
                $("."+id).hide();
            }
        }
    //End: To show detail view of jobs etc onclick

    //Start: for candidates cv download;
        function download(id){

                var token = $('#token').val();
                var candidateId = id;
            //Some code
                $.ajax({

                    type: "get", //send it through get method
                    data: {
                        new_token: token,
                        candidate_id: candidateId,
                    },
                    success: function(response) {
                        //Do Something
                    },
                    error: function(xhr) {
                        //Do Something to handle error
                    }
                });
        }
    //End: for candidates cv download;

</script>

@endsection
