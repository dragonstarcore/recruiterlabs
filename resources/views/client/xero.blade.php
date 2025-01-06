@extends('layouts.app')
@section('content')

        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <style>
            #container, #container1 {
                min-width: 110px;
                max-width: 500px;
                height: 400px;
                margin: 0 auto;
            }
        </style>
        <div class="content">
            <div class="card mb-2">
                    <div class="card-header text-start ">
                        <h4 class="mb-0">Xero</h4>
                    </div>
                    <div class="card-body">
                        @if($error)
                            @if (!isset(Auth::user()->xero_details->client_id) || Auth::user()->xero_details->client_id==null)
                                <div class="alert alert-danger mb-2">You have not connected any account yet, please connect your Xero account.
                                    <p class="mb-2">
                                        <a href="{{ url('my_business#visit_link') }}" class="">
                                            Visit here
                                        </a>
                                        to add relevant details to connect Xero account.
                                    </p>
                                </div>
                            @else
                                <h6>Your connection to Xero failed</h6>
                                <p class="mb-2">{{ $error }}</p>
                                <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                    Connect to Xero
                                </a>
                            @endif
                        @elseif($connected)
                            <h6>You are connected to Xero</h6>
                            <p>{{ $organisationName }} via {{ $username }} </p>
                            {{-- <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                Reconnect to Xero
                            </a> --}}
                        @else
                            <h5>You are not connected to Xero</h5>
                            @if (!isset(Auth::user()->xero_details->client_id) || Auth::user()->xero_details->client_id==null)
                                <div class="alert alert-danger mb-2">You have not connected any account yet, please connect your Xero account.</div>
                                <p class="mb-2">
                                    <a href="{{ url('my_business') }}" class="btn btn-primary btn-large ">
                                        Visit link
                                    </a>
                                     add relevant details to connect Xero:</p>
                            @else
                                <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                    Connect to Xero
                                </a>
                            @endif
                        @endif
                    </div>
            </div>

            @if(isset(Auth::user()->xero_details) && (isset(Auth::user()->xero_details->client_id)  || Auth::user()->xero_details->client_id!=null))
                <div class="row">
                    <div class="col-md-6">
                        <div class="card col-md-12" style="height: 240px;">
                            <div class="card-header text-start ">
                            <h6> Account watchlist </h6>
                            </div>
                            <div class="card-body text-center ">
                                <div class="row">
                                <div class="col-md-6 text-start"> Account </div>
                                <div class="col-md-3">This month</div>
                                <div class="col-md-3">YTD</div>
                                </div>
                                <hr>
                                @if(isset($account_watchlist) && $account_watchlist!=null)
                                    @foreach ($account_watchlist as $key => $item)
                                        <div class="row">
                                            <div class="col-md-6 text-start">{{$item[0]}}</div>
                                            <div class="col-md-3">{{$item[1]}}</div>
                                            <div class="col-md-3">{{$item[2]}}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row">
                                        <p>There is no data for this field as fetched from Xero</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card col-md-12 " style="height: 240px;">
                            <div class="card-header text-start ">
                            <h6> Business Bank Account </h6>
                            </div>
                            <div class="card-body text-center ">
                                <div class="row">
                                    <div class="col-md-6 text-start">Balance in Xero </div>
                                    <div class="col-md-6 text-start">@if(isset($balance[1])){{$balance[1]}}@else There is no data for this field as fetched from Xero @endif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 ">
                    <div class="card col-md-12">
                        @if(isset($data))
                            <div class="card-header text-center ">
                                <h6>Invoices owed to you</h6>
                                <p>{{ $data['draft_count'] }} Draft invoices : {{ $data['draft_amount'] }}</p>
                                <p>{{ $data['aw_count'] }} Awaiting payment  : {{ $data['aw_amount'] }}</p>
                                <p>{{ $data['overdue_count'] }} Overdue      : {{ $data['overdue_amount'] }}</p>
                            </div>
                            <div class="card-body "><div id="container"></div></div>
                        @endif
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card col-md-12">
                        @if(isset($my_data))
                        <div class="card-header text-center ">
                            <h6>Bills you need to pay</h6>
                            <p>{{ $my_data['draft_count'] }} Draft bills : {{ $my_data['draft_amount'] }}</p>
                            <p>{{ $my_data['aw_count'] }} Awaiting payment  : {{ $my_data['aw_amount'] }}</p>
                            <p>{{ $my_data['overdue_count'] }} Overdue      : {{ $my_data['overdue_amount'] }}</p>
                        </div>
                        <div class="card-body "><div id="container1"></div></div>
                        @endif
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card " style="">
                                <div class="card-header text-center ">
                                    <h6>Total Cash In/Out</h6>
                                </div>
                            <div class="card-body "><div id="container2"></div></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <script>
            $( document ).ready(function() {
                // Start: Invoices need to pay
                    let arr_name = [];
                    let arr_y = [];
                    var invoices_array =    <?php echo json_encode($invoices_array); ?> ;
                    const container = document.getElementById('container');


                    try {
                            // Display the array elements
                            for(var i = 0; i <Object.keys(invoices_array).length; i++){
                                arr_name.push(invoices_array[i]['name']);
                                arr_y.push(Math.round(invoices_array[i]['y']* 100) / 100);
                            }

                            // console.log(arr_name,arr_y);
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

                //Start: bills need to pay
                    let bill_arr_name = [];
                    let bill_arr_y = [];
                    var bills_array =    <?php echo json_encode($bills_array); ?> ;
                    const container1 = document.getElementById('container1');


                    try {
                            // Display the array elements
                                for(var i = 0; i <Object.keys(bills_array).length; i++){
                                    bill_arr_name.push(bills_array[i]['name']);
                                    bill_arr_y.push(bills_array[i]['y']);
                                }

                                Highcharts.chart('container1', {
                                        chart: {
                                            type: 'column',
                                            zoomType: 'y'
                                        },
                                        title: {
                                            text: undefined
                                        },
                                        subtitle: {
                                            text: 'Bills Data'
                                        },
                                        xAxis: {
                                            categories: bill_arr_name,
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
                                            data: bill_arr_y,
                                            }
                                        ],
                                        lang: {
                                            noData: 'Nichts zu anzeigen'
                                        },
                                        noData: {
                                            style: {
                                                fontWeight: 'bold',
                                                fontSize: '15px',
                                                color: '#303030'
                                            }
                                        }

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
                            container1.innerHTML = '';
                            container1.appendChild(errorDiv);
                    }
                // End: bills need to pay

                //Start: Total cash in and out

                        let cash_in = [];
                        let cash_out = [];
                        var total_cash =    <?php echo json_encode($total_cash); ?> ;
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
                                text: 'Total cash in and out data'
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
            });
        </script>

@endsection
