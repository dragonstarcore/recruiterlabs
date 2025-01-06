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
                    <div class="col-lg-8"><h5 class="mb-0">Community Users List</h5></div>

                    <div class="col-lg-4 text-end">
                        <a class="btn btn-primary" href="{{route('communities.create')}}" >Create</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('community_index')}}" method="post">
                    @csrf
                    <div class="row ">
                        <div class="col-lg-5">
                            <input type="text" name="str_search"  @if(isset($searchvalue) && $searchvalue!=null) value="{{$searchvalue}}" @endif placeholder="Enter Specialism" class="form-control" id="keywords">
                            <span>[After each keyword use ENTER OR COMMA ( eg : php, js ) ]</span>
                        </div>
                        <div class="col-lg-1 text-start">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table  client_list "  >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Specialism</th>
                            <th>Location</th>
                            <th class="text-center" style="width=30px" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>
                                    @if(isset($user->logo))
                                        <img src="{{asset(''.$user->logo)}}" class="rounded-pill" width="36" height="36" alt="">
                                    @else
                                        <img src="{{asset('assets/images/default_user.jpg')}}" class="rounded-pill" width="36" height="36" alt="">
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
                                <td class="text-muted">
                                    {{$user->location}}
                                    {{-- @if ($user->status==1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif --}}
                                </td>
                                <td class="d-flex justify-content-center" >
                                        <a href="{{route('communities.edit',$user->id)}}" class="btn btn-info btn-sm me-2">
                                            <i class="ph-pen"></i>
                                        </a>
                                    <form action="{{route('communities.destroy',$user->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="ph-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();

            var tagsValue = $('#keywords').val();
            $('#keywords').val(tagsValue).tagsinput();

           $('.client_list').dataTable( {
            // ordering: false,
                "language": {
                "emptyTable": "No records found "
                }
            } );
       });
    </script>

@endsection
