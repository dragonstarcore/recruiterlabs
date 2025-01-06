@extends('layouts.app')
@section('content')
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

    <!-- Content area -->
        <div class="content">
            <!-- Basic layout -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Ticket</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('tickets.update',$ticket->id)}}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Choose Team: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="team" class="form-control form-control-select2" >
                                    <option value="">Choose Team</option>
                                    @foreach (get_teams() as $data)
                                        <option @if ($ticket->team==$data) selected @endif value="{{$data}}">{{$data}}</option>
                                    @endforeach   
                                </select>
                                @if($errors->has('team'))
                                    <div class="text-danger">{{ $errors->first('team') }}</div>
                                @endif
                            </div>
                        </div>  --}}

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Choose Team: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="" type="text" class="form-control" placeholder="" value="{{$ticket->team}}" required readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Title: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="title" type="text" class="form-control" placeholder="Title" value="{{$ticket->title}}" required readonly>
                                @if($errors->has('title'))
                                    <div class="text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Description: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <textarea name="description" rows="3" cols="3" class="form-control" placeholder="Enter description here" required readonly>{{$ticket->description}}</textarea>
                                @if($errors->has('description'))
                                    <div class="text-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Status:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="status" class="form-control form-control-select2" required>
                                    <option value="">Select Status</option>
                                    <option @if ($ticket->status==1) selected @endif value="1" >Active</option>
                                    <option @if ($ticket->status==0) selected @endif value="0">Inactive</option>
                                </select>
                                @if($errors->has('status'))
                                    <div class="text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Priority: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="priority" class="form-control form-control-select2" >
                                    <option value="">Select Priority</option>
                                    @foreach (get_priority() as $item)
                                        <option @if ($ticket->priority==$item) selected @endif value="{{$item}}">{{$item}}</option>
                                    @endforeach   
                                </select>
                                @if($errors->has('priority'))
                                    <div class="text-danger">{{ $errors->first('priority') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">History: </label>
                            <div class="col-lg-9">
                            @foreach ($history as $data)
                                <label class="form-control" class="row col-lg-12 ms-2"><b>{{$data['user']}}</b> : {{$data['message']}} </label>
                            @endforeach
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Message: <span class="requiredData">*</span></label>
                            
                            <div class="col-lg-9">
                                <textarea name="message" rows="3" cols="3" class="form-control" placeholder="Enter your message here" required></textarea>
                                @if($errors->has('message'))
                                    <div class="text-danger">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{url()->previous()}}" class="btn btn-secondary">Discard</a>
                            <button type="submit" class="btn btn-primary">Save <i class="ph-paper-plane-tilt ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /basic layout -->
        </div>
    <!-- /content area -->
    <script type="text/javascript">
        
    </script>

@endsection