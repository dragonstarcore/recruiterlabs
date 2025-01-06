@extends('layouts.app')
@section('content')

        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}

        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> --}}
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <style>
            #container, #container1 {
                min-width: 110px !important;
                max-width: 500px!important;
                height: 400px!important;
                margin: 0 auto!important;
            }

            #container2 {
                min-width: 110px!important;
                max-width: 500px!important;
                height: 310px!important;
                /* width: 520px; */
                margin: 0 auto!important;
            }

        </style>
        <div class="content">
            @if(Auth::user()->role_type==2)
                <!--Start: Jobadder-->
                    <div class="card" >
                        <div class="card-header">
                            <form id="filter_data" action="{{ route('search_jobadder') }}" method="post">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h5 class="mb-0">Jobadder</h5>
                                            @if(isset($jobadder['fullname'])){{$jobadder['fullname']}} -@endif
                                            @if(isset($jobadder['account_email'])){{$jobadder['account_email']}}@endif
                                        </div>
                                        @if(isset($jobadder['fullname']))
                                            <div class="col-md-6">
                                                <div class="row custom-dates" @if(isset($dateOption) && $dateOption=='custom') style="display: flex" @else style="display: none" @endif >
                                                    <div class="col-md-3 me-1" style="width: 170px;">
                                                        <input type="date" name="startDate" onfocus="this.showPicker()" id="startDate" class="form-control" placeholder="Start date" @if(isset($startDate)) value="{{ $startDate }}" @endif>
                                                    </div>
                                                    <div class="col-md-3 me-1" style="width: 170px;">
                                                        <input type="date" name="endDate" onfocus="this.showPicker()" id="endDate" class="form-control " placeholder="End date" @if(isset($endDate)) value="{{ $endDate }}" @endif>
                                                    </div>
                                                    <div class="col-md-2 me-1">
                                                        <button type="button" id="custom_search" class="btn btn-primary">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 ml-auto">
                                                <select class="form-select " name="date_option" id="selection" >
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

                            @if(isset($jobadder['fullname']))
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-lg-3" >
                                            <!-- Members online -->
                                            <div class="card bg-info text-white">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-center">
                                                        <h3 class="mb-0 text-center jobs" >@if(isset($jobadder['fullname'])) {{$jobadder['jobs']}} @endif</h3>
                                                        {{-- <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down me-2"></i></span> --}}
                                                    </div>

                                                    <div>
                                                    Total Jobs
                                                        <div class="fs-sm opacity-75"> &nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /members online -->
                                        </div>
                                        <div class="col-lg-3" >
                                            <!-- Members online -->
                                            <div class="card bg-pink text-white">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-center">
                                                        <h3 class="mb-0 contacts">@if(isset($jobadder['contacts'])) {{$jobadder['contacts']}}  @endif</h3>
                                                        {{-- <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down me-2"></i></span> --}}
                                                    </div>

                                                    <div>
                                                        Total Contacts
                                                        <div class="fs-sm opacity-75"> &nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /members online -->
                                        </div>
                                        <div class="col-lg-3" >
                                            <!-- Members online -->
                                            <div class="card bg-teal text-white">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-center">
                                                        <h3 class="mb-0 interviews"> @if(isset($jobadder['interviews'])) {{$jobadder['interviews']}}  @endif</h3>
                                                        {{-- <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down me-2"></i></span> --}}
                                                    </div>

                                                    <div>
                                                    Total Interviews
                                                        <div class="fs-sm opacity-75"> &nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /members online -->
                                        </div>
                                        <div class="col-lg-3" >
                                            <!-- Members online -->
                                            <div class="card bg-warning text-white">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-center">
                                                        <h3 class="mb-0 candidates"> @if(isset($jobadder['candidates'])) {{$jobadder['candidates']}}  @endif</h3>
                                                        {{-- <span class="badge bg-opacity-50 align-self-center ms-auto"><i class="ph-arrow-down me-2"></i></span> --}}
                                                    </div>

                                                    <div>
                                                    Total Candidates
                                                        <div class="fs-sm opacity-75"> &nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /members online -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="card col-md-12 ">
                                                @if(isset($jobadder['jobs_graph']))
                                                    <div class="card-body "><div id="container5"></div></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="card col-md-12">
                                                @if(isset($jobadder['jobs_graph']))
                                                    <div class="card-body "><div id="container7"></div></div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card col-md-12">
                                                @if(isset($jobadder['jobs_graph']))
                                                    <div class="card-body "><div id="container8"></div></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card col-md-12">
                                                @if(isset($jobadder['jobs_graph']))
                                                    <div class="card-body "><div id="container6"></div></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <div class="card-body">
                                    <div class="alert alert-danger">You have not connected any account yet, please connect your Jobadder account.</div>
                                </div>
                            @endif
                            <!-- Place the loading spinner inside the card element -->
                            <div class="loading-spinner" id="loading-spinner">
                            </div>
                        </div>
                <!--End: Jobadder-->

                <!--Start: Google Analytics Data-->
                    <div class="card col-md-12">
                        <div class="card-header text-start ">
                            <h5 class="mb-0"> Google Analytics Data </h5>
                            {{-- @if(isset($page_views) && $page_views!=null)<p> Website : Recruiterlabs </p>@endif --}}
                        </div>
                            @if(isset($page_views))
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6> Page Views </h6>
                                            <p>@if(isset($page_views)){{ $page_views }}@endif</p>
                                        </div>
                                        <div class="col-md-6 ">
                                                <h6> Unique Users </h6>
                                            <p>@if(isset($total_visitors)){{ $total_visitors }}@endif</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card-body">
                                    @if(isset($GA_error) && $GA_error!=null)
                                        <div class="alert alert-danger">{{$GA_error}}</div>
                                    @else
                                        <div class="alert alert-danger">You have not connected any account yet, please connect your Google Analytics account.</div>
                                    @endif
                                </div>
                            @endif
                    </div>
                <!--End: Google Analytics Data-->

                <!--Start: Xero Data-->
                    <div class="card col-md-12">
                        <div class="card-header text-start ">
                        <h5 class="mb-0"> Xero Data </h5>
                            @if(isset($xero['organisationName']) && $xero['organisationName']!=null)
                                <p> Organisation Name -  {{$xero['organisationName']}} via {{ $xero['username'] }}</p>
                            @endif
                        </div>
                        @if(isset($xero['organisationName']) && $xero['organisationName']!=null)
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="card col-md-11 ms-4">
                                                <div class="card-header text-center">
                                                    <h6>Invoices owed to you</h6>
                                                    @if(isset($xero['data']))
                                                        <p>{{ $xero['data']['draft_count'] }} Draft invoices : {{ $xero['data']['draft_amount'] }}</p>
                                                        <p>{{ $xero['data']['aw_count'] }} Awaiting payment  : {{ $xero['data']['aw_amount'] }}</p>
                                                        <p>{{ $xero['data']['overdue_count'] }} Overdue      : {{ $xero['data']['overdue_amount'] }}</p>
                                                    @endif
                                                </div>
                                                <div class="card-body "><div id="container"></div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="card col-md-12" >
                                            <div class="card-header text-start ">
                                            <h6> Business Bank Account </h6>
                                            </div>
                                            <div class="card-body text-center ">
                                                <div class="row">
                                                    <div class="col-md-6 text-start"> Balance in Xero </div>
                                                    <div class="col-md-6 text-start">@if(isset($xero['balance'][1])){{$xero['balance'][1]}} @else There is no data for this field as fetched from Xero @endif</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="card col-md-12" style="height: 422px!important;">
                                                <div class="card-header text-center">
                                                    <h6>Total cash in and out</h6>
                                                </div>
                                                <div class="card-body ">
                                                    <div id="container2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="alert alert-danger">You have not connected any account yet, please connect your Xero account.</div>
                            </div>
                        @endif
                    </div>
                <!--End: Xero Data-->

                <!--Start: My Apps-->
                    <div class="card">
                        <div class="card-header text-start ">
                            <h3 class="mb-0"> My Apps </h3>
                        </div>
                        <div class="card-body app_card_body">
                            <div class="row">
                                <div class="col-sm-6 col-xl-3 app_card" >
                                    <a href="https://www.xero.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid" src="{{asset('assets/images/xero.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://jobadder.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid" src="{{asset('assets/images/jobadder.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.linkedin.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid " src="{{asset('assets/images/linkedin.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.microsoft.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid" src="{{asset('assets/images/msoffice.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.dropbox.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid" src="{{asset('assets/images/dropbox.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://login.microsoftonline.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                <img class="card-img img-fluid "  src="{{asset('assets/images/sharepoint.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-xl-3 app_card" >
                                    <a href="https://calendly.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                <img class="card-img img-fluid" src="{{asset('assets/images/calendly.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.vincere.io/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                <img class="card-img img-fluid" src="{{asset('assets/images/vincere.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.sourcebreaker.com/" target="_blank">
                                        <div class="card apps" style="" >
                                            <div class="card-img-actions mx-1 mt-1">
                                                <img class="card-img img-fluid" src="{{asset('assets/images/sourcebreaker.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.sonovate.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid apps_card_img" src="{{asset('assets/images/sonovate.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xl-3 app_card" style="">
                                    <a href="https://www.lastpass.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                    <img class="card-img img-fluid apps_card_img" src="{{asset('assets/images/lastpass.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-sm-6 col-xl-3 special_card" style="">
                                    <a href="https://login.microsoftonline.com/" target="_blank">
                                        <div class="card apps" style="">
                                            <div class="card-img-actions mx-1 mt-1">
                                                <img class="card-img img-fluid apps_card_img"  src="{{asset('assets/images/sharepoint.jpg')}}" alt="" style="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                <!--Start: My Apps-->
            @else
                <div class="card">
                    <div class="card-header text-start">
                        <h5 class="mb-0"> Dashboard </h5>
                    </div>
                    <div class="card-body text-start">
                        <p> No data </p>
                        {{-- <a href="{{ route('dates_data') }}" target="_blank">data</a> --}}
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Initially hide the loading spinner
            $(document).ready(function () {
                $('#loading-spinner').hide();

                // Show the loading spinner when a page reload is triggered
                $(window).on('beforeunload', function () {
                    $('#loading-spinner').show();
                });
            });

            $( document ).ready(function() {
                var jobadder_data =   <?php if (isset($jobadder)) {
                                                    echo json_encode($jobadder);
                                                } else {
                                                    echo json_encode([]); // Default to an empty array, for example.
                                                }
                                                ?>;
                if (jobadder_data['candidates_graph']) { // Check if jobadder_data is set and not null
                    // console.log("jobadder_data:", jobadder_data); // Debugging
                    updateJobsArray(jobadder_data);
                }

                $('#selection').on('change', function() {
                    var start_date, end_date;
                    var date_option = this.value;

                    if(date_option=='custom'){
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
                        start_date = $('#startDate').val();
                        end_date = $('#endDate').val();

                    }else{
                        // alert(1);
                        $('#loading-spinner').show();

                        $('.custom-dates').css('display', 'none');
                        start_date = null;
                        end_date = null;

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('dashboard_jobadder') }}", // Use the named route
                            data: {
                                // Any data you want to send to the server
                                _token: "{{ csrf_token() }}",
                                startDate: start_date,
                                endDate: end_date,
                                date_option: date_option,
                            },
                            success: function(response) {
                                $('.jobs').html(response[0]);
                                $('.interviews').html(response[1]);
                                $('.contacts').html(response[2]);
                                $('.candidates').html(response[3]);
                                // $('#response').html(response.message);
                                updateJobsArray(response);
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                            }
                        });
                    }

                });

                $('#custom_search').on('click', function() {

                    var start_date, end_date;
                    var date_option = $('#selection').val();
                    // alert(date_option);
                    if(date_option=='custom'){
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
                        start_date = $('#startDate').val();
                        end_date = $('#endDate').val();
                    }else{
                        start_date = null;
                        end_date = null;
                    }
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('dashboard_jobadder') }}", // Use the named route
                        data: {
                            // Any data you want to send to the server
                            _token: "{{ csrf_token() }}",
                            startDate: start_date,
                            endDate: end_date,
                            date_option: date_option,
                        },
                        success: function(response) {
                            $('.jobs').html(response[0]);
                            $('.interviews').html(response[1]);
                            $('.contacts').html(response[2]);
                            $('.candidates').html(response[3]);
                            // jobs_array = response['jobs_graph'];
                            // Call updateJobsArray with the response data
                            updateJobsArray(response);// Pass the actual data received from the server

                            // At this point, the updateJobsArray function will update the data and redraw the graph.
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                });

                // Start: Invoices need to pay
                    let arr_name = [];
                    let arr_y = [];
                    var invoices_array =    <?php echo json_encode($xero['invoices_array']); ?> ;
                    const container = document.getElementById('container');

                    try{
                            // Display the array elements
                            for(var i = 0; i <Object.keys(invoices_array).length; i++){
                                arr_name.push(invoices_array[i]['name']);
                                arr_y.push(Math.round(invoices_array[i]['y']* 100) / 100);
                            }

                            // console.log(arr_y);
                            Highcharts.chart('container', {
                                chart: {
                                    type: 'column',
                                    zoomType: 'y'
                                },
                                title: {
                                    text: undefined
                                },
                                subtitle: {
                                    text: 'Invoice Data'
                                },
                                xAxis: {
                                    categories: arr_name,
                                    title: {
                                    text: null
                                    },
                                    accessibility: {
                                    description: 'Weeks'
                                    }
                                },

                                plotOptions: {
                                    column: {
                                    dataLabels: {
                                        enabled: true,
                                        format: '£ {y}'
                                    }
                                    }
                                },
                                tooltip: {
                                    valuePrefix: '£ ',
                                    stickOnContact: true,
                                },
                                legend: {
                                    enabled: false
                                },
                                series: [
                                    {
                                    name: 'Amount ',
                                    data: arr_y,
                                    }
                                ]
                            });
                    } catch (error) {
                        // Handle errors that occur during the loop or chart creation
                        console.error('An error occurred:', error);

                        // Create a div for the error message and style it
                        const errorDiv = document.createElement('div');
                        errorDiv.style.textAlign = 'center';
                        errorDiv.style.position = 'absolute';
                        errorDiv.style.top = '60%';
                        errorDiv.style.left = '50%';
                        errorDiv.style.transform = 'translate(-50%, -50%)';
                        errorDiv.innerHTML = 'There is no data as fetched from Xero';

                        // Replace the contents of the container with the error message
                        container.innerHTML = '';
                        container.appendChild(errorDiv);
                    }
                // End: Invoices need to pay

                //Start: Total cash in and out
                    let cash_in = [];
                    let cash_out = [];
                    var total_cash =    <?php echo json_encode($xero['total_cash']); ?> ;
                    const container2 = document.getElementById('container2');

                    try {
                            // Display the array elements
                            for(var i = 0; i <Object.keys(total_cash['y']['In']).length; i++){
                                cash_in.push(Math.round(total_cash['y']['In'][i]* 100) / 100);
                            }
                            for(var i = 0; i <Object.keys(total_cash['y']['Out']).length; i++){
                                cash_out.push(Math.round(total_cash['y']['Out'][i]* 100) / 100);
                            }
                            // console.log(cash_in,cash_out);
                            Highcharts.chart('container2', {
                                chart: {
                                    type: 'column',
                                    zoomType: 'y'
                                },
                                title: {
                                    text: undefined
                                },
                                subtitle: {
                                    text: 'Cash in and out Data'
                                },
                                xAxis: {
                                    categories: total_cash['name'],
                                    title: {
                                    text: null
                                    },
                                    accessibility: {
                                    description: 'Weeks'
                                    }
                                },
                                plotOptions: {
                                    column: {
                                    dataLabels: {
                                        enabled: true,
                                        format: '£ {y}'
                                    }
                                    }
                                },
                                tooltip: {
                                    valuePrefix: '£ ',
                                    stickOnContact: true,
                                    backgroundColor: 'yellow'
                                },
                                legend: {
                                    enabled: false
                                },
                                series: [{
                                    name: 'In' ,
                                    data: cash_in
                                    },
                                    {
                                    name: 'Out',
                                    data: cash_out
                                    }
                                ]
                            });
                    } catch (error) {
                        // Create a div for the error message and style it
                        const errorDiv = document.createElement('div');
                        errorDiv.style.textAlign = 'center';
                        errorDiv.style.position = 'absolute';
                        errorDiv.style.top = '60%';
                        errorDiv.style.left = '50%';
                        errorDiv.style.transform = 'translate(-50%, -50%)';
                        errorDiv.innerHTML = 'There is no data as fetched from Xero';

                        // Replace the contents of the container with the error message
                        container2.innerHTML = '';
                        container2.appendChild(errorDiv);
                    }
                //End: Total cash in and out

                //Start: Jobadder graph data
                    function updateJobsArray(jobadder_data) {
                        // console.log(jobadder_data);
                        //Start: jobs data
                            jobs_array = jobadder_data['jobs_graph'];
                                let arr_name5 = [];
                                let arr_y5 = [];

                                // Display the array elements
                                for(var i = 0; i <Object.keys(jobs_array).length; i++){
                                    // console.log(jobs_array[i]);
                                    arr_name5.push(jobs_array[i]['name']);
                                    arr_y5.push(jobs_array[i]['y']);
                                }

                                // console.log(arr_name5,arr_y1);
                                Highcharts.chart('container5', {
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
                                        categories: arr_name5,
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
                                        data: arr_y5,
                                        // borderColor: ['#5997DE','#RED','#RED']
                                        }
                                    ]
                                });
                        // End: jobs data

                        //Start: candidates data
                            let arr_name6 = [];
                            let arr_y6 = [];
                            var candidates_array =  jobadder_data['candidates_graph'];

                                                    //  console.log(candidates_array);
                            // Display the array elements
                            for(var i = 0; i <Object.keys(candidates_array).length; i++){
                                // console.log(candidates_array[i]);
                                arr_name6.push(candidates_array[i]['name']);
                                arr_y6.push(candidates_array[i]['y']);
                            }

                            // console.log(arr_name1,arr_y1);
                            Highcharts.setOptions({
                                    colors: ['#FF9655']
                            });
                            Highcharts.chart('container6', {
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
                                    categories: arr_name6,
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
                                    data: arr_y6,
                                    // borderColor: ['#5997DE','#RED','#RED']
                                    }
                                ]
                            });
                        // End: candidates data

                        //Start: contacts data
                            let arr_name7 = [];
                            let arr_y7 = [];
                            var contacts_array =   jobadder_data['contacts_graph'];

                                                    //  console.log(contacts_array);
                            // Display the array elements
                            for(var i = 0; i <Object.keys(contacts_array).length; i++){
                                // console.log(contacts_array[i]);
                                arr_name7.push(contacts_array[i]['name']);
                                arr_y7.push(contacts_array[i]['y']);
                            }

                            // console.log(arr_name1,arr_y1);
                            Highcharts.setOptions({
                                    colors: ['#f35c86']
                            });
                            Highcharts.chart('container7', {
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
                                    categories: arr_name7,
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
                                    data: arr_y7,
                                    // borderColor: ['#5997DE','#RED','#RED']
                                    }
                                ]
                            });
                        // End: contacts data

                         //Start: interviews data
                            let arr_name8 = [];
                            let arr_y8 = [];
                            var interviews_array =   jobadder_data['interviews_graph'];
                                                    //  console.log(interviews_array);
                            // Display the array elements
                            for(var i = 0; i <Object.keys(interviews_array).length; i++){
                                // console.log(interviews_array[i]);
                                arr_name8.push(interviews_array[i]['name']);
                                arr_y8.push(interviews_array[i]['y']);
                            }

                            // console.log(arr_name1,arr_y1);
                            Highcharts.setOptions({
                                    colors: ['#26a69a']
                            });
                            Highcharts.chart('container8', {
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
                                    categories: arr_name8,
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
                                    data: arr_y8,
                                    // borderColor: ['#5997DE','#RED','#RED']
                                    }
                                ]
                            });
                        // End: interviews data
                    }
                //End: Jobadder graph data
            });

        </script>

@endsection
