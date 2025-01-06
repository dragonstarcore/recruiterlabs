@extends('layouts.app')
@section('content')

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>

<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Home - <span class="fw-normal">Tickets</span>
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
                    <div class="col-lg-6"><h5 class="mb-0">Tickets List</h5></div>
                    <div class="col-lg-6 text-end">
                        <a class="btn btn-primary" href="{{route('tickets.create')}}">Create</a>
                    </div>
                </div>
            </div>

            <table class="table tasks-list ticket_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Created By</th>
                        <th>Team</th>
                        <th>Created At</th>
                        <th class="text-center" style="width=30px" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $key => $ticket)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{ucfirst($ticket->title)}}</td>
                            <td>{{ucfirst($ticket->user->name)}}</td>
                            <td>{{ucfirst($ticket->team)}}</td>
                            <td>{{$ticket->created_at->format('d/m/Y h:m:s') }}</td>
                            <td class="d-flex justify-content-center" >
                                    <a href="{{route('tickets.edit',$ticket->id)}}" class="btn btn-info btn-sm me-2">
                                    <i class="ph-pen"></i>
                                    </a>
                                <form action="{{route('tickets.destroy',$ticket->id)}}" method="post">
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ticket_list').dataTable( {
            // ordering: false,
                "language": {
                "emptyTable": "No records found "
                }
            } );
    
       });
    </script>

@endsection
        