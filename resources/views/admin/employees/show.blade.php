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
                Home - <span class="fw-normal">Staff - {{ucfirst($user->name ?? '')}}</span>
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
                    <div class="col-lg-6 text-end">
                        <a class="btn btn-primary" href="{{route('client_events_list',$user->id)}}">Events List</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12"><div  id='calendar1' style="height:50px;"></div></div>
                </div>
            </div>
        </div>
        <!--  End:Events  -->
        <!--  Begin:People list  -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6"><h5 class="mb-0"> Staff List</h5></div>
                    <div class="col-lg-6 text-end">
                        <form action="{{route('employees.create')}}" method="get">
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-primary" >Create</a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap client_list ">
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
                        @foreach ($employee_list as $key => $employee)
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
                                        <form action="{{route('employees.edit',$employee->id)}}" method="get">
                                            <input type="hidden" id="userId"  name="user_id" value="{{$user->id}}">
                                            <button type="submit" class="btn btn-primary me-2" ><i class="ph-pen"></i></a>
                                        </form>
                                        <form action="{{route('employees.destroy',$employee->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="ph-trash"></i></button>
                                        </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--  End:People list  -->

        <!--  Begin:HR Documents  -->
        <div class="card">
            <div class="card-header">
                <form action="{{route('client_hrdoc_search',$user->id)}}" method="post" id="client_hrdoc_form">
                    @csrf
                    <div class="row ">
                        <div class="col-lg-3"><h5 class="mb-0">General Documents</h5></div>
                        <div class="col-lg-4 text-end">
                            <input type="text" @if (isset($value)) value="{{$value}}"

                            @endif name="str_search" placeholder="Enter Title" class="form-control" id="str_search">
                        </div>
                        <div class="col-lg-1 text-end">
                            <button type="button" class="btn btn-primary" id="hrdoc_search" >Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <form action="{{route('hr_docs')}}" method="post" enctype="multipart/form-data" id="hr_docs">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <input type="hidden" name="search_title" id="search_title" value="">
                    @csrf
                    {{-- <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Documents: </label>
                        <div class="col-lg-9 documentDiv">
                            <div class="upload__box">
                                <div class="upload__btn-box">
                                <label class="upload__btn mb-3">
                                    <p id="upload">Upload</p>
                                    <input name="images[]" type="file" multiple="" data-max_length="5" class="upload__inputfile" >
                                </label>
                                </div>
                                <div class="upload__img-wrap"></div>
                            </div>
                            <span class="text-danger error" id="title"></span>
                            <span class="text-danger" style="color:red" id="imagecheck"></span>
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <div class="col-lg-12 documentDiv" >
                            <div class="upload__box3">
                                <div class="upload__btn-box3">
                                <label class="upload__btn3 mb-3">
                                    <p id="upload3">Upload</p>
                                    <input name="images[]" type="file" multiple="" data-max_length="5" class="upload__inputfile3" >
                                </label>
                                </div>
                                <div class="upload__img-wrap3">

                                </div>
                            </div>
                            <span class="text-danger error" id="title"></span>
                            <span class="text-danger" style="color:red" id="imagecheck"></span>
                        </div>
                    </div>


                    <div class="text-end">
                        <a href="{{url()->previous()}}" class="btn btn-secondary">Discard</a>
                        <button type="submit" class="btn btn-primary" >Save <i class="ph-paper-plane-tilt ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <!--  End:HR Documents -->
    </div>

    <script type="text/javascript">
            //Start:To avoid doc_type select box
            var doc_type='people_doc_type';
            //End:To avoid doc_type select box
        $(document).ready(function () {
            // var doc_type='people_doc_type';

            $('.client_list').dataTable( {
                "language": {
                "emptyTable": "No records found "
                }
            } );

            // Start : for events
            var user = <?php echo json_encode($user); ?>;
            var SITEURL = "{{ url('/') }}";
            let currentDate = new Date().toJSON().slice(0, 10);
            var calendar = $('#calendar1').fullCalendar({
                editable: true,
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
                events: SITEURL + "/fullcalender/"+user['id'],

            });
            // End : for events

            //Start:To avoid doc_type select box
            // var doc_type='people_doc_type';
            //End:To avoid doc_type select box

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
                        $('.image_box_data').remove();// To empty imageloaded div
                        image_load(data);// To load all images
                });
            });
            // End :To check search variable set before saving documents

            // Start :To load HR documents
            var hr_documents = {!! json_encode($user->user_hr_documents) !!};
            image_load(hr_documents);
            // End :To load HR documents

            // Start :To load all HR documents on_upload_click[to remove search entry and to bring all docs]


            $("#upload3").click(function(){
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
                        console.log(data);
                        $("#search_title").val(undefined);
                        $("#str_search").val(undefined);
                        $('.image_box_data').remove();// To empty imageloaded div
                        image_load(data);// To load all images
                });
            });
            // End :To load all HR documents on_upload_click

        });

        //Start:TO show client HR Documents
        function image_load(hr_documents){
            // var doc_type='people_doc_type';
            var hr_documents = hr_documents;
            let filesArr = [];
            // console.log(hr_documents);
            for (let i = 0; i < hr_documents.length; i++) {
                var file_extension = hr_documents[i]['file'].split('.').pop();
                var file_name = hr_documents[i]['file'].split('/').pop();
                // console.log(file_name);
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
            var html = `<div class="col-lg-12 row mb-2 image_box_data">
                                    <div class="col-lg-2">
                                        <b>Image</b>
                                    </div>
                                    <div class="col-lg-4">
                                        <b>Title</b>
                                    </div>
                                </div>`;
                                $('.upload__img-wrap3').append(html);
            for (var i = 0; i < filesArr.length; i++) {
                // old code if(filesArr[i].type_id==4){
                //     preloaded.push(filesArr[i].id);
                //     var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "'><select name='old_document[]' class='form-select mb-2' aria-label='Default select example'><option >Select Type</option><option value='4' selected>Marketing & brand</option><option value='5'>Legal business documentation</option><option value='6'>Templates</option></select><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                // }else if(filesArr[i].type_id==5){
                //     preloaded.push(filesArr[i].id);
                //     var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "'><select name='old_document[]' class='form-select mb-2' aria-label='Default select example'><option >Select Type</option><option value='4' >Marketing & brand</option><option value='5' selected>Legal business documentation</option><option value='6'>Templates</option></select><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                // }else if(filesArr[i].type_id==6){
                //     preloaded.push(filesArr[i].id);
                //     var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='old_title[]' placeholder='Enter title' required value='" + filesArr[i].name + "'><select name='old_document[]' class='form-select mb-2' aria-label='Default select example'><option >Select Type</option><option value='4'>Marketing & brand</option><option value='5' >Legal business documentation</option><option value='6' selected>Templates</option></select><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>View<p></a></div>";
                // }
                // $('.upload__img-wrap').append(html);

                var html1= `<div class="col-md-12 row image_box_data">
                                <div class="col-md-2">
                                    <img src="{{('${filesArr[i].image}')}}" class="rounded-pill" width="36" height="36" alt="">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control mb-2 text-body fw-semibold"" name="old_title[]" placeholder="Enter title" required value="${filesArr[i].name}">
                                </div>
                                <input type="hidden" name="old_images[${preloaded}]" value="${filesArr[i].id}">
                                <div class="col-md-2">
                                    <a href="${filesArr[i].src}" class="btn btn-info btn-sm" target="_blank"><i class="ph-eye"></i></a>
                                    <button class="btn btn-danger btn-sm upload__img-close3" type="submit"><i class="ph-trash"></i></button>
                                </div>
                            </div>`;


                $('.upload__img-wrap3').append(html1);
            }
        }
        //End:TO show client HR Documents
    </script>

@endsection
