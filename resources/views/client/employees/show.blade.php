@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

{{--Start:  for calender events  --}}
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" /> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
{{--End: for calender events  --}}
{{-- Start: for datatables  --}}
{{-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
{{-- End: for datatables  --}}

<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Home - <span class="fw-normal">My Staff</span>
            </h4>
            <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
            </a>
        </div>
    </div>
</div>


    <div class="content">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has($msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
                @endif
            @endforeach
        </div>

        <!--  Begin:Events  -->
        <div class="card" style="height:420px;">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6"><h5 class="mb-0">Upcoming Events</h5></div>
                    <div class="col-lg-3 ">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mySwitch" name="" value="yes" checked>
                            <label class="form-check-label" for="mySwitch">List View/ Calender View</label>
                          </div>
                    </div>
                    <div class="col-lg-3 text-end">
                        <a class="btn btn-primary" href="{{route('events.index')}}">Create Event</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12" style="height:50px;">
                        <div id='calendar1' style="height:50px;display: none"></div>
                        <div id='calendar3' style="height:50px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--  End:Events -->
        <!--  Begin:Staff list  -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6"><h5 class="mb-0">My Staff List</h5></div>
                    {{-- <div class="col-lg-6 text-end">
                        <a class="btn btn-primary" href="{{route('employees.create')}}">Create</a>
                    </div> --}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table  employee_list text-nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Profile Picture</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Job Title</th>
                            <th class="text-center" style="width=30px" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($employees))
                        @foreach ($employees as $key => $employee)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>
                                    @if(isset($employee->employee_details->emp_picture))
                                    <img src="{{asset(''.$employee->employee_details->emp_picture)}}" class="rounded-pill" width="36" height="36" alt="">
                                    @else
                                    <img src="{{asset('assets/images/default_user.jpg')}}" class="rounded-pill" width="36" height="36" alt="">
                                    @endif
                                </td>

                                <td class="text-body fw-semibold">{{ucfirst($employee->name)}}</td>
                                <td class="text-muted">{{$employee->email}}</td>
                                <td class="text-muted"> @if(isset($employee->employee_details->title)){{ucfirst($employee->employee_details->title)}}@endif</td>
                                <td class="d-flex justify-content-center" >
                                        <a href="{{route('employees.edit',$employee->id)}}" class="btn btn-info btn-sm me-2">
                                        <i class="ph-eye"></i>
                                        </a>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!--  End:Staff list  -->

        <!--  Begin:HR Documents  -->
        {{--old code <div class="card">
            <div class="card-header">
                <form action="{{route('employee_index')}}" method="post" id="client_hrdoc_form">
                    @csrf
                    <div class="row ">
                        <div class="col-lg-3"><h5 class="mb-0">General Documents</h5></div>
                        <div class="col-lg-4 text-end">
                            <input type="text" name="str_search" placeholder="Enter Title" class="form-control" id="str_search">
                        </div>
                        <div class="col-lg-1 text-end">
                            <button type="button" class="btn btn-primary" id="hrdoc_search">Filter</button>
                        </div>
                    </div>
                </form>
            </div> --}}

            {{-- <div class="card-body">
                <form action="{{route('hr_docs')}}" method="post" enctype="multipart/form-data" >
                    <input type="hidden" name="user_id" value="{{$data->id}}">
                    <input type="hidden" name="search_title" id="search_title" value="">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Documents: </label>
                        <div class="col-lg-9 documentDiv">
                            <div class="upload__box"> --}}
                                {{-- <div class="upload__btn-box">
                                <label class="upload__btn mb-3">
                                    <p id="upload">Upload</p>
                                    <input name="images[]" type="file" multiple="" data-max_length="5" class="upload__inputfile" >
                                </label>
                                </div> --}}
                                {{-- <div class="upload__img-wrap"></div>
                            </div>
                            <span class="text-danger error" id="title"></span>
                            <span class="text-danger" style="color:red" id="imagecheck"></span>
                        </div>
                    </div> --}}

                    {{-- <div class="text-end">
                        <a href="{{url()->previous()}}" class="btn btn-secondary">Discard</a>
                        <button type="submit" class="btn btn-primary">Save <i class="ph-paper-plane-tilt ms-2"></i></button>
                    </div> --}}
                {{-- </form>
            </div>
        </div> --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6"><h5 class="mb-0">General Documents</h5></div>
                </div>
            </div>

            <table class="table employee_list text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data->user_hr_documents))
                    @foreach ($data->user_hr_documents as $key => $hr_doc)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>
                                @if(isset($hr_doc->file))
                                <img src="{{asset(''.$hr_doc->file)}}" class="rounded-pill" width="36" height="36" alt="">
                                @else
                                <img src="{{asset('assets/images/default_user.jpg')}}" class="rounded-pill" width="36" height="36" alt="">
                                @endif
                            </td>
                            <td class="text-body fw-semibold"> @if(isset($hr_doc->title)){{ucfirst($hr_doc->title)}}@endif</td>
                            <td class="d-flex" >
                                @if(isset($hr_doc->file))
                                    <a href="{{asset(''.$hr_doc->file)}}" target="_blank" class="btn btn-info btn-sm me-2">
                                        <i class="ph-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!--  End:HR Documents -->
    </div>
<script type="text/javascript">

        //Start To avoid doc_type select box
            var doc_type='people_doc_type';
        //End To avoid doc_type select box
        $(document).ready(function () {
            //Start:For people list datatable
                $('.employee_list').dataTable( {
                // ordering: false,
                    "language": {
                    "emptyTable": "No records found "
                    }
                } );
            //End:For people list datatable
            //Start:TO show client and client-people events
                var SITEURL = "{{ url('/') }}";
                let currentDate = new Date().toJSON().slice(0, 10);
                    //    console.log(currentDate);

                            $('#calendar3').fullCalendar({
                                events: SITEURL + "/allevents",
                                contentHeight: 250,
                            });


                $( "#mySwitch" ).on( "change", function() {
                        if($('#calendar3').is(":hidden")){
                            $('#calendar1').hide();
                            $('#calendar3').show();

                        }else{
                            $('#calendar3').hide();

                            $('#calendar1').show();
                            var calendar = $('#calendar1').fullCalendar({
                                editable: true,
                                    //    events: SITEURL + "/fullcalender",

                                header: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'listYear,listMonth,listWeek,listDay'
                                },
                                views: {
                                    listDay: {
                                    buttonText: 'this day'
                                    },
                                    listWeek: {
                                    buttonText: 'this week'
                                    },
                                    listMonth: {
                                    buttonText: 'this month'
                                    },
                                    listYear: {
                                    buttonText: 'this year'
                                    }
                                },
                                height: 300,
                                defaultView: 'listWeek',
                                defaultDate: currentDate,
                                navLinks: true, // can click day/week names to navigate views
                                editable: true,
                                eventLimit: true, // allow "more" link when too many events
                                events: SITEURL + "/fullcalender",

                            });
                        }
                } );
            //End:TO show client and client-people events

            // Start :To load HR documents
            var hr_documents = {!! json_encode($data->user_hr_documents) !!};
                image_load(hr_documents);
                // End :To load HR documents

                // Start :To check search variable set before saving documents
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#hrdoc_search").click(function(){
                    //  alert('1');
                    var formData = {
                        str_search: $("#str_search").val(),
                    };
                    // alert(str_search);
                    $("#search_title").val($("#str_search").val());
                    // alert($("#search_title").val());
                    $.ajax({
                        type: "POST",
                        url: $("#client_hrdoc_form").attr("action"),
                        data: formData,
                        dataType: "json",
                        encode: true,
                        }).done(function (data) {
                            // console.log(data);
                            // $("#str_search").val(undefined);
                            $('.upload__img-box').remove();// To empty imageloaded div
                            image_load(data);// To load all images
                    });
                });
                // End :To check search variable set before saving documents

                // Start :To load all HR documents on_upload_click[to remove search entry and to bring all docs]
                $("#upload").click(function(){
                    //  alert('1');
                    var formData = {
                        on_upload_click: 1,
                    };

                    $.ajax({
                        type: "POST",
                        url: $("#client_hrdoc_form").attr("action"),
                        data: formData,
                        dataType: "json",
                        encode: true,
                        }).done(function (data) {
                            // console.log(data);
                            $("#search_title").val(undefined);
                            $("#str_search").val(undefined);
                            $('.upload__img-box').remove();// To empty imageloaded div
                            image_load(data);// To load all images
                    });
                });
                // End :To load all HR documents on_upload_click


        });

        //Start:TO show client HR Documents
        function image_load(hr_documents){
            var hr_documents = hr_documents;
            let filesArr = [];
            // console.log(hr_documents);
            for (let i = 0; i < hr_documents.length; i++) {
                var file_extension = hr_documents[i]['file'].split('.').pop();
                var file_name = hr_documents[i]['file'].split('/').pop();
                console.log(file_name);
                if(file_extension=='pdf'){
                    filesArr.push({id: hr_documents[i]['id'],src:'{{asset("public/")}}/'+hr_documents[i]['file'], name: hr_documents[i]['title'], type_id:hr_documents[i]['type_id'],image:config.pdf});
                }else if(file_extension=='doc' || file_extension=='docx'){
                    filesArr.push({id: hr_documents[i]['id'],src:'{{asset("public/")}}/'+hr_documents[i]['file'],name: hr_documents[i]['title'],  type_id:hr_documents[i]['type_id'],image:config.doc});
                }else{
                    filesArr.push({id: hr_documents[i]['id'], src: '{{asset("public/")}}/'+hr_documents[i]['file'],name: hr_documents[i]['title'], type_id:hr_documents[i]['type_id'],image:'{{asset("public/")}}/'+hr_documents[i]['file']});
                }

            }
            // console.log(filesArr);
            let preloaded = [];
                for (var i = 0; i < filesArr.length; i++) {

                    // if(filesArr[i].type_id==1){
                    preloaded.push(filesArr[i].id);
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "' disabled><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                    // }else if(filesArr[i].type_id==2){
                    //     preloaded.push(filesArr[i].id);
                    //     var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "'><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                    // }else if(filesArr[i].type_id==3){
                    //     preloaded.push(filesArr[i].id);
                    //     var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "'><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                    // }
                    $('.upload__img-wrap').append(html);
                }
        }
        //End:TO show client HR Documents
</script>
@endsection
