@extends('layouts.app')
@section('content')

 

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
        <div class="row">
            @foreach ($users as $key => $user)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{route('employee_list',$user->id)}}">
                    <div class="card" style="height:268px">
                        <div class="card-img-actions mx-1 mt-1">
                            @if(isset($user->user_details->logo))
                                <img class="card-img img-fluid" src="{{asset(''.$user->user_details->logo)}}" alt="" style="height: 10rem;">
                            @else
                                <img class="card-img img-fluid" src="{{asset('assets/images/nologo.jpg')}}" alt="" style="height: 10rem;">
                            @endif
                            <div class="card-img-actions-overlay card-img">
                                {{-- <a href="{{asset('assets/images/demo/flat/1.png')}}" class="btn btn-outline-white btn-icon rounded-pill" data-bs-popup="lightbox" data-gallery="gallery1">
                                    <i class="ph-plus"></i>
                                </a>
                                <a href="#" class="btn btn-outline-white btn-icon rounded-pill ms-2">
                                    <i class="ph-link"></i>
                                </a> --}}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-start flex-nowrap">
                                <div>
                                    <div class="fw-semibold me-2">{{ucfirst($user->name)}}</div>
                                    <div class="fw-semibold me-2">{{ucfirst($user->user_details->company_name)}}</div>
                                    <div class="fw-semibold me-2">{{$user->user_details->company_number}}</div>
                                    {{-- <span class="fs-sm text-muted">{{$user->user_details->company_name}}</span>
                                    <span class="fs-sm text-muted">{{$user->name}}</span>
                                    <span class="fs-sm text-muted">{{$user->name}}</span> --}}
                                </div>

                                <div class="d-inline-flex ms-auto">
                                    <span class="fs-sm ">{{count($user->user_people)}}</span>
                                    {{-- <a href="#" class="text-body"><i class="ph-download-simple"></i></a>
                                    <a href="#" class="text-body ms-2"><i class="ph-trash"></i></a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endforeach
        </div>


            {{-- <table class="table tasks-list client_list">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td><a href="{{route('employee_list',$user->id)}}" class="btn btn-info btn-sm me-2">
                                {{$user->name}}
                                </a>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
        
    </div>

    <script type="text/javascript">
    //     $(document).ready(function () {
    //        $('.client_list').DataTable()({
    //         ordering: false,
    //         //    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    //        });
    
    //    });
    </script>

@endsection
        