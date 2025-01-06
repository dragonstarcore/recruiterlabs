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
                    <h5 class="mb-0">Create Document/FAQS</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('knowledgebases.store')}}" method="post" enctype="multipart/form-data" id="target">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Type: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="type" class="form-control form-control-select2" id="type">
                                    <option value="">Choose</option>
                                    <option value="DOCUMENT">Document</option>
                                    <option value="VIDEO">Video</option>
                                    <option value="FAQS">QA</option>
                                </select>
                                @if($errors->has('type'))
                                    <div class="text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label">Title: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="title" type="text" class="form-control" placeholder="Title" value="{{old('title')}}" required>
                                @if($errors->has('title'))
                                    <div class="text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label">Description:  </label>
                            <div class="col-lg-9">
                                <input name="description" type="text" class="form-control" placeholder="Description" value="{{old('description')}}" >
                                @if($errors->has('description'))
                                    <div class="text-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label"> Embedded link: </label>
                            <div class="col-lg-9">
                                <input name="embedded_link" type="text" id="embedded_link" class="form-control" placeholder="Embedded link" value="{{old('embedded_link')}}" >
                                @if($errors->has('embedded_link'))
                                    <div class="text-danger">{{ $errors->first('embedded_link') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label">  </label>
                            <div class="col-lg-9 ">
                                 <span class="ms-1 text-danger"><b> OR </b></span>
                            </div>
                        </div>

                        <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label"> Document: </label>
                            <div class="col-lg-9">
                                <input name="images[]" type="file" id="images" class="form-control" placeholder="File" value="" >
                                @if($errors->has('images'))
                                    <div class="text-danger">{{ $errors->first('images') }}</div>
                                @endif
                                <span class="text-danger mt-2" style="color:red" id="imagecheck"></span>
                            </div>
                        </div>

                        {{-- <div class="row mb-3 document" style="display:none">
                            <label class="col-lg-3 col-form-label">Document: </label>
                            <div class="col-lg-9 documentDiv">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                    <label class="upload__btn mb-3">
                                        <p>Upload</p>
                                        <input name="images[]" type="file" multiple="" data-max_length="1" class="upload__inputfile" >
                                    </label>
                                    </div>
                                    <div class="upload__img-wrap"></div>
                                </div>
                                <span class="text-danger error" id="title"></span>
                                <span class="text-danger" style="color:red" id="imagecheck"></span>
                            </div>
                        </div> --}}

                        <div class="row mb-3 QA" style="display:none">
                            <label class="col-lg-3 col-form-label">Question: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" name="question"  class="form-control" placeholder="Question" value="{{old('question')}}" required>
                                @if($errors->has('question'))
                                    <div class="text-danger">{{ $errors->first('question') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 QA" style="display:none">
                            <label class="col-lg-3 col-form-label">Answer: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="answer" type="text" class="form-control" placeholder="Answer" value="{{old('answer')}}" required>
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
            $( "#type" ).change(function() {
                var type_id = $(this).val();
                $( ".document" ).hide();
                $( ".QA" ).hide();
                if(type_id=='FAQS'){
                    $( ".QA" ).show();
                    $( ".document" ).hide();
                    $('.QA :input').prop('disabled', false);
                    $('.document :input').prop('disabled', true);
                }else if((type_id=='DOCUMENT') || (type_id=='VIDEO')){
                    $( ".document" ).show();
                    $( ".QA" ).hide();
                    $('.document :input').prop('disabled', false);
                    $('.QA :input').prop('disabled', true);
                }
            });
        });

        $( "#target" ).submit(function( event ) {
            var type_id = $("#type").val();
            var embedded_link = $( "#embedded_link").val();
            var images = $( "#images").val();
            // alert(type_id);
            if(type_id!='FAQS'){
                if( (!embedded_link) && (!images)) {
                    event.preventDefault();
                    $('#imagecheck').html('Please enter embedded link or choose a file');
                }
            }
        });
    
        </script>

@endsection