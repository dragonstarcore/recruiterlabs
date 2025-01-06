@extends('layouts.app')
@section('content')

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>

<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Home - <span class="fw-normal">Clients</span>
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
                    <div class="col-lg-6"><h5 class="mb-0">Clients List</h5></div>
                    <div class="col-lg-6 text-end">
                        <a class="btn btn-primary" href="{{route('users.create')}}" >Create</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table tasks-list text-nowrap client_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-center" style="width=30px" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>
                                    @if(isset($user->user_details->logo))
                                        <img src="{{asset(''.$user->user_details->logo)}}" class="rounded-pill" width="36" height="36" alt="">
                                    @else
                                        <img src="{{asset('assets/images/default_user.jpg')}}" class="rounded-pill" width="36" height="36" alt="">
                                    @endif
                                </td>
                                <td class="text-body fw-semibold">{{ucfirst($user->name)}}</td>
                                <td class="text-muted">{{$user->email}}</td>
                                <td >
                                    @if ($user->status==1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center" >
                                        <a href="{{route('users.edit',$user->id)}}" class="btn btn-info btn-sm me-2">
                                            <i class="ph-pen"></i>
                                        </a>
                                    <form action="{{route('users.destroy',$user->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure?')" type="submit"><i class="ph-trash "></i></button>
                                    </form>

                                    <form action="{{route('reset_link')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        <button class="btn btn-success btn-sm" type="submit">Resend Email</button>
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

           $('.client_list').dataTable( {
            // ordering: false,
            // "pageLength": 15,
                "language": {
                "emptyTable": "No records found "
                }
            } );

       });
    </script>

@endsection
