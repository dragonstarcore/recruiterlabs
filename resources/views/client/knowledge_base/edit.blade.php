@extends('layouts.app')
@section('content')

<script src="{{asset('assets/js/inputmask.js')}}"></script>

    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Knowledge Base</span>
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
                    <h5 class="mb-0">Edit FAQS</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('knowledgebases.update',$data->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Type: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="type" class="form-control form-control-select2" id="type">
                                    <option value="">Choose</option>
                                    <option value="FAQS" selected>QA</option>
                                </select>
                                @if($errors->has('type'))
                                    <div class="text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3 QA" >
                            <label class="col-lg-3 col-form-label">Question: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" name="question"  class="form-control fieldset" placeholder="Question" value="{{$data->question}}" required>
                                @if($errors->has('question'))
                                    <div class="text-danger">{{ $errors->first('question') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 QA" >
                            <label class="col-lg-3 col-form-label">Answer: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="answer" type="text" class="form-control fieldset" placeholder="Answer" value="{{$data->answer}}" required>
                                @if($errors->has('answer'))
                                    <div class="text-danger">{{ $errors->first('answer') }}</div>
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
        $(document).ready(function(){
            // $( "#type" ).change(function() {
            //     var type_id = $(this).val();
            //     $( ".document" ).hide();
            //     $( ".QA" ).hide();
            //     if(type_id=='FAQS'){
            //         $( ".QA" ).show();
            //         $( ".document" ).hide();
            //         $('.document :input').prop('disabled', true);
            //     }else if((type_id=='DOCUMENT') || (type_id=='VIDEO')){
            //         $( ".document" ).show();
            //         $( ".QA" ).hide();
            //         $('.QA :input').prop('disabled', true);
            //     }
            // });
        });
    
        </script>

@endsection