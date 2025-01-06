@extends('layouts.app')
@section('content')

{{-- Start: tagsinput  --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" /> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>

<style>
    .bootstrap-tagsinput .tag {
         background: #0c83ff;
         padding: 1px;
         font-size: 16px;
         border-radius: 6px;
     }

     .bootstrap-tagsinput{
        width: 854px !important;
        height: 38px  !important;
    }

     .bootstrap-tagsinput input{
       border: none;
       box-shadow: none;
       outline: none;
       background-color: transparent;
       padding: 0 6px;
       margin: 0px;
        width: 250px;
        height: 26px;
       max-width: inherit;
     }
</style>
   {{-- End: tagsinput  --}}
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

    <!-- Content area -->
        <div class="content">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has($msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }}</p>
                    @endif
                @endforeach
            </div>
            <!-- Basic layout -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create Client</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Name: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" type="text" class="form-control" placeholder="Eugene Kopyov" value="{{old('name')}}" required>
                                @if($errors->has('name'))
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" type="email" class="form-control" placeholder="email@gmail.com" value="{{old('email')}}" required>
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Status:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="status" class="form-control form-control-select2" required>
                                    <option value="">Select Status</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @if($errors->has('status'))
                                    <div class="text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email confirmation </label>
                            <div class="col-lg-9 mt-2 ">
                                <input name="send_email" type="checkbox" class="form-check-input community m-1" value="1" >
                                @if($errors->has('community'))
                                    <div class="text-danger">{{ $errors->first('community') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Add to community </label>
                            <div class="col-lg-9 mt-2 ">
                                <input name="community" type="checkbox" class="form-check-input community m-1" id="community_user" placeholder="Add to community" value="{{old('community')}}" >
                                @if($errors->has('community'))
                                    <div class="text-danger">{{ $errors->first('community') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="row mb-3 community_data" style="display: none">
                            <label class="col-lg-3 col-form-label">Specialist: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="specialist" type="text" class="form-control specialist" placeholder="Specialist" value="{{old('specialist')}}" >
                                @if($errors->has('specialist'))
                                    <div class="text-danger">{{ $errors->first('specialist') }}</div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3 community_data" style="display: none">
                            <label class="col-lg-3 col-form-label">Specialism: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="keywords" type="text" class="form-control keywords" placeholder="Specialism" value="{{old('keywords')}}" id="keywords">
                                {{-- @if($errors->has('keywords'))
                                    <div class="text-danger">{{ $errors->first('keywords') }}</div>
                                @endif --}}
                                <span>[After each keyword use ENTER OR COMMA ( eg : php,js ) ]</span>
                            </div>
                        </div>

                        <div class="row mb-3 community_data" style="display: none">
                            <label class="col-lg-3 col-form-label">Industry: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="industry" type="text" class="form-control" placeholder="Industry" value="{{old('industry')}}" >
                                @if($errors->has('industry'))
                                    <div class="text-danger">{{ $errors->first('industry') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label "> <b>Company Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company Name: </label>
                            <div class="col-lg-9">
                                <input name="company_name" type="text" class="form-control" placeholder="Company Name" value="{{old('company_name')}}" >
                                @if($errors->has('company_name'))
                                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company  Number: </label>
                            <div class="col-lg-9">
                                <input name="company_number" type="text" class="form-control" placeholder="Company Number" value="{{old('company_number')}}" >
                                @if($errors->has('company_number'))
                                    <div class="text-danger">{{ $errors->first('company_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Registered Address: </label>
                            <div class="col-lg-9">
                                <input name="registered_address" type="text" class="form-control" placeholder="Registered Address" value="{{old('registered_address')}}" >
                                @if($errors->has('registered_address'))
                                    <div class="text-danger">{{ $errors->first('registered_address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">VAT Number: </label>
                            <div class="col-lg-9">
                                <input name="vat_number" type="text" class="form-control" placeholder="VAT Number" value="{{old('vat_number')}}" >
                                @if($errors->has('vat_number'))
                                    <div class="text-danger">{{ $errors->first('vat_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Authentication Code: </label>
                            <div class="col-lg-9">
                                <input name="authentication_code" type="text" class="form-control" placeholder="Authentication Code" value="{{old('authentication_code')}}" >
                                @if($errors->has('authentication_code'))
                                    <div class="text-danger">{{ $errors->first('authentication_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company UTR: </label>
                            <div class="col-lg-9">
                                <input name="company_utr" type="text" class="form-control" placeholder="Company UTR" value="{{old('company_utr')}}" >
                                @if($errors->has('company_utr'))
                                    <div class="text-danger">{{ $errors->first('company_utr') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Logo: </label>
                            <div class="col-lg-9">
                                <input id="cat_image" name="logo" type="file" class="form-control" placeholder="Logo" value="{{old('logo')}}" >
                                <label for="" class="col-form-label">[ Preferable size 500 × 450 px ]</label>
                                @if($errors->has('logo'))
                                    <div class="text-danger">{{ $errors->first('logo') }}</div>
                                @endif
                                <div class="preview " style="display: none">
                                    <img src="#" id="category-img-tag" height="100px" width="120px" />   <!--for preview purpose -->
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label"> <b>Bank Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Bank Name: </label>
                            <div class="col-lg-9">
                                <input name="bank_name" type="text" class="form-control" placeholder="Bank Name" value="{{old('bank_name')}}" >
                                @if($errors->has('bank_name'))
                                    <div class="text-danger">{{ $errors->first('bank_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Sort Code: </label>
                            <div class="col-lg-9">
                                <input name="sort_code" type="text" class="form-control" placeholder="Sort Code" value="{{old('sort_code')}}" >
                                @if($errors->has('sort_code'))
                                    <div class="text-danger">{{ $errors->first('sort_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Account Number: </label>
                            <div class="col-lg-9">
                                <input name="account_number" type="text" class="form-control" placeholder="Account Number" value="{{old('account_number')}}" >
                                @if($errors->has('account_number'))
                                    <div class="text-danger">{{ $errors->first('account_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">IBAN: </label>
                            <div class="col-lg-9">
                                <input name="iban" type="text" class="form-control" placeholder="IBAN" value="{{old('iban')}}" >
                                @if($errors->has('iban'))
                                    <div class="text-danger">{{ $errors->first('iban') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Swift Code: </label>
                            <div class="col-lg-9">
                                <input name="swift_code" type="text" class="form-control" placeholder="Swift Code" value="{{old('swift_code')}}" >
                                @if($errors->has('swift_code'))
                                    <div class="text-danger">{{ $errors->first('swift_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3" id="visit_link">
                            <label class="col-lg-3 col-form-label">API Details: </label>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#demo" id="hide_show">Collapsible</button>
                            </div>
                        </div>
                        <div id="demo" class="row mb-3 collapse">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="card col-lg-9">
                                <nav>
                                    <div class="nav nav-tabs nav-tabs-underline" id="nav-tab" role="tablist">
                                        <a class="nav-link active" id="nav-xero-tab" data-bs-toggle="tab" href="#nav-xero" role="tab" aria-controls="nav-xero" aria-selected="true">Xero</a>
                                        {{-- <a class="nav-link" id="nav-jobadder-tab" data-bs-toggle="tab" href="#nav-jobadder" role="tab" aria-controls="nav-jobadder" aria-selected="false">Jobadder</a> --}}
                                        <a class="nav-link" id="nav-analytics-tab" data-bs-toggle="tab" href="#nav-analytics" role="tab" aria-controls="nav-analytics" aria-selected="false">Google Analytics</a>
                                    </div>
                                </nav>
                                <div class="tab-content mt-3" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-xero" role="tabpanel" aria-labelledby="nav-xero-tab">
                                        <div class="row mb-3 " >
                                            <label  class="col-lg-2 col-form-label">Client Id</label>
                                            <div class="col-lg-10">
                                                <input name="xero_client_id" type="text" class="form-control" placeholder="Client Id" @if(isset($user->xero_details)) value="{{$user->xero_details->client_id}}" @endif >
                                                @if($errors->has('xero_client_id'))
                                                    <div class="text-danger">{{ $errors->first('xero_client_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3 " >
                                            <label  class="col-lg-2 col-form-label">Client Secret</label>
                                            <div class="col-lg-10">
                                                <input name="xero_client_secret" type="text" class="form-control" placeholder="Client Secret" @if(isset($user->xero_details)) value="{{$user->xero_details->client_secret}}" @endif >
                                                @if($errors->has('xero_client_secret'))
                                                    <div class="text-danger">{{ $errors->first('xero_client_secret') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="tab-pane fade" id="nav-jobadder" role="tabpanel" aria-labelledby="nav-jobadder-tab">
                                        <div class="row mb-3 " >
                                            <label  class="col-lg-2 col-form-label">Client Id</label>
                                            <div class="col-lg-10">
                                                <input name="jobadder_client_id" type="text" class="form-control" placeholder="Client Id"  @if(isset($user->jobadder_details)) value="{{$user->jobadder_details->client_id}}" @endif >
                                                @if($errors->has('jobadder_client_id'))
                                                    <div class="text-danger">{{ $errors->first('jobadder_client_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3 " >
                                                <label  class="col-lg-2 col-form-label">Client Secret</label>
                                            <div class="col-lg-10">
                                                <input name="jobadder_client_secret" type="text" class="form-control" placeholder="Client Secret"  @if(isset($user->jobadder_details)) value="{{$user->jobadder_details->client_secret}}" @endif >
                                                @if($errors->has('jobadder_client_secret'))
                                                    <div class="text-danger">{{ $errors->first('jobadder_client_secret') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="tab-pane fade" id="nav-analytics" role="tabpanel" aria-labelledby="nav-analytics-tab">
                                        <div class="row mb-3 " >
                                            <label  class="col-lg-4 col-form-label">Analytics property Id</label>
                                        <div class="col-lg-6">
                                            <input name="analytics_view_id" type="text" class="form-control" placeholder="Property Id"  @if(isset($user->jobadder_details->analytics_view_id)) value="{{$user->jobadder_details->analytics_view_id}}" @endif >
                                            @if($errors->has('analytics_view_id'))
                                                <div class="text-danger">{{ $errors->first('analytics_view_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--old code <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Documents: </label>
                            <div class="col-lg-9 documentDiv">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                    <label class="upload__btn mb-3">
                                        <p>Upload</p>
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
                            <label class="col-lg-3 col-form-label">Documents: </label>
                        </div>
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
                            <button type="submit" class="btn btn-primary">Save <i class="ph-paper-plane-tilt ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /basic layout -->
        </div>
    <!-- /content area -->

    <script type="text/javascript">
        function readURL(input) {
           if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                   $('#category-img-tag').attr('src', e.target.result);
               }

               reader.readAsDataURL(input.files[0]);
           }
       }

        $("#cat_image").change(function(){
            $('.preview').show();
            readURL(this);
        });
        // alert('1');

        $(document).ready(function(){
            $('#hide_show').html('Show');

            $("#hide_show").click(function(){
                if($("#hide_show").html()=='Show'){
                    $('#hide_show').html('Hide');
                }else{
                    $('#hide_show').html('Show');
                }
            });

            var tagsValue = $('#keywords').val();
            $('#keywords').val(tagsValue).tagsinput();


            $("#community_user").change(function() {
                // alert(this.checked);

                if(this.checked) {
                    $('.community_data').show();
                            $('.specialist').prop('required',true);
                            $('.keywords').prop('required',true);
                }else{
                    $('.community_data').hide();
                            $('.specialist').prop('required',false);
                            $('.keywords').prop('required',false);
                }
            });

        });
    </script>

@endsection
