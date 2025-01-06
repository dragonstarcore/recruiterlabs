@extends('layouts.app')
@section('content')
{{-- Start: For datatable  --}}
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
{{-- End: For datatable  --}}
{{-- Start: tagsinput  --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" /> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>
{{-- End: tagsinput  --}}

{{-- <style>
    .keywords_break {
        width:200px;
        display:inline-block;
    }
</style> --}}
    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Community</span>
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
        <!--  table -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4"><h5 class="mb-0">Community Users List</h5></div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('community_index')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="text" @if(isset($keyword_value) && $keyword_value!=null) value="{{$keyword_value}}" @endif name="str_search" placeholder="Enter Specialism" class="form-control" id="keywords"><br>
                                    <span>[After each keyword use ENTER OR COMMA ( eg : php, js ) ]</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-10">
                                    <select class="form-select" name="location_search" id="">
                                        <option value="" selected >Select Location</option>
                                        <option value="All" @if($location_value=='All') selected @endif>All</option>
                                        @foreach ($user_locations as $item)
                                            <option @if($location_value==$item) selected @endif value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 text-start">
                                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
                <hr>
            <div class="table-responsive">
            <table class="table  users_list "  >
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialism</th>
                        <th>Industry</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>
                                @if(isset($user->logo))
                                    <img class="rounded-pill" width="36" height="36" src="{{asset(''.$user->logo)}}" alt="" >
                                @else
                                    <img class="rounded-pill" width="36" height="36" src="{{asset('assets/images/nologo.jpg')}}" alt="" >
                                @endif
                            </td>
                            <td class="text-body fw-semibold">{{ucfirst($user->name)}}</td>
                            <td class="text-muted">{{$user->email}}</td>
                            <td class="text-muted">
                                @if(isset($user->keywords))
                                    @php
                                        $keywords = explode(',', $user->keywords); // Split the string into an array
                                        $formattedKeywords = implode(', ', $keywords); // Add a space after each comma
                                    @endphp
                                    <span class="keywords_break">{{ucfirst($formattedKeywords)}}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ucfirst($user->industry)}}</td>
                            <td class="text-muted">{{ucfirst($user->location)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            {{-- <div class="card-body">
                <form action="{{route('community_index')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="text" @if(isset($keyword_value) && $keyword_value!=null) value="{{$keyword_value}}" @endif name="str_search" placeholder="Enter Specialism" class="form-control" id="keywords"><br>
                                    <span>[After each keyword use ENTER OR COMMA ( eg : php, js ) ]</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-10">
                                    <select class="form-select" name="location_search" id="">
                                        <option value="" selected >Select Location</option>
                                        <option value="All" @if($location_value=='All') selected @endif>All</option>
                                        @foreach ($user_locations as $item)
                                            <option @if($location_value==$item) selected @endif value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 text-start">
                                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                @foreach ($users as $key => $user)
                <div class="col-sm-6 col-xl-3 community_div" style="">
                    <a href="#">
                    <div class="card community_card" style="cursor: default;">
                        <div class="card-img-actions mx-1 mt-1">
                            @if(isset($user->logo))
                                <img class="card-img img-fluid" src="{{asset(''.$user->logo)}}" alt="" style="height: 10rem;">
                            @else
                                <img class="card-img img-fluid" src="{{asset('assets/images/nologo.jpg')}}" alt="" style="height: 10rem;">
                            @endif
                        </div>

                        @php
                            $string = preg_replace('/(,)(?=[^\s])/', ', ', $user->keywords);
                        @endphp

                        <div class="card-body">
                            <div class="d-flex align-items-start flex-nowrap ">
                                <div>
                                    <div class="fw-semibold me-2"><h6 style="margin-bottom: 0px;"><i class="ph-user me-1 mb-1" style="font-size: 15px;vertical-align: middle;"></i><span >{{ucfirst($user->name)}}</span></h6></div>
                                    <div class=" community_content" data-bs-custom-class="tooltip-inverse"  data-container="body"
                                    data-toggle="tooltip" data-placement="top" title="Email"><i class="ph-envelope me-1 mb-1 mt-1" style="font-size: 15px;vertical-align: middle;"></i>{{$user->email}}</div>
                                    <div class=" community_content" data-bs-custom-class="tooltip-inverse"  data-container="body"
                                    data-toggle="tooltip" data-placement="top" title="Industry"><i class="ph-buildings me-1 mb-1 " style="font-size: 15px;vertical-align: middle;"></i>{{ucfirst($user->industry)}}</div>
                                    <div class=" community_content keywords_break" data-bs-custom-class="tooltip-inverse"  data-container="body"
                                    data-toggle="tooltip" data-placement="top" title="Specialism: {{ucfirst($string)}}">
                                    <i class="ph-list me-1" style="font-size: 15px;vertical-align: middle;"></i>{{ucfirst($string)}}</div>
                                    <div class=" community_content b" data-bs-custom-class="tooltip-inverse"  data-container="body"
                                    data-toggle="tooltip" data-placement="top" title="Location"><i class="ph-globe me-1 mb-1 mt-1" style="font-size: 15px;vertical-align: middle;"></i>{{ucfirst($user->location)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                @endforeach
                </div>
            </div> --}}
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();

             var tagsValue = $('#keywords').val();
            $('#keywords').val(tagsValue).tagsinput();

                $('.users_list').dataTable( {
                 // ordering: false,
                "language": {
                "emptyTable": "No records found "
                }

            } );

       });
    </script>

@endsection
