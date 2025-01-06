@extends('layouts.app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" />
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
                    <h5 class="mb-0">Edit Client</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('users.update',$user->id)}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="client_id" id="client_id" value="{{$user->id}}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Name: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" type="text" class="form-control" placeholder="Eugene Kopyov" value="{{$user->name}}" required>
                            </div>
                            @if($errors->has('name'))
                                <div class="error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" type="email" class="form-control" placeholder="email@gmail.com" value="{{$user->email}}" required>
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Password:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="password" type="password" class="form-control" placeholder="Password" value="{{$user->password}}" required>
                                @if($errors->has('password'))
                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Status:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <select name="status" class="form-control form-control-select2" required>
                                    <option value="">Select Status</option>
                                    <option @if($user->status==1) selected @endif value="1">Active</option>
                                    <option @if($user->status==0) selected @endif value="0">Inactive</option>
                                </select>
                                @if($errors->has('status'))
                                    <div class="text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label "> <b>Company Details</b>  </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company Name: </label>
                            <div class="col-lg-9">
                                <input name="company_name" type="text" class="form-control" placeholder="Company Name" @if(isset($user->user_details)) value="{{$user->user_details->company_name}}" @endif >
                                @if($errors->has('company_name'))
                                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company Number: </label>
                            <div class="col-lg-9">
                                <input name="company_number" type="text" class="form-control" placeholder="Company Number" @if(isset($user->user_details)) value="{{$user->user_details->company_number}}" @endif >
                                @if($errors->has('company_number'))
                                    <div class="text-danger">{{ $errors->first('company_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Registered Address: </label>
                            <div class="col-lg-9">
                                <input name="registered_address" type="text" class="form-control" placeholder="Registered Address" @if(isset($user->user_details)) value="{{$user->user_details->registered_address}}" @endif >
                                @if($errors->has('registered_address'))
                                    <div class="text-danger">{{ $errors->first('registered_address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">VAT Number: </label>
                            <div class="col-lg-9">
                                <input name="vat_number" type="text" class="form-control" placeholder="VAT Number" @if(isset($user->user_details)) value="{{$user->user_details->vat_number}}" @endif >
                                @if($errors->has('vat_number'))
                                    <div class="text-danger">{{ $errors->first('vat_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Authentication Code: </label>
                            <div class="col-lg-9">
                                <input name="authentication_code" type="text" class="form-control" placeholder="Authentication Code" @if(isset($user->user_details)) value="{{$user->user_details->authentication_code}}" @endif >
                                @if($errors->has('authentication_code'))
                                    <div class="text-danger">{{ $errors->first('authentication_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Company UTR: </label>
                            <div class="col-lg-9">
                                <input name="company_utr" type="text" class="form-control" placeholder="Company UTR" @if(isset($user->user_details)) value="{{$user->user_details->company_utr}}" @endif >
                                @if($errors->has('company_utr'))
                                    <div class="text-danger">{{ $errors->first('company_utr') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row ">
                            <label for="image" class="col-lg-3 col-form-label">Logo</label>
                            <div class="col-lg-9">
                                <input id="cat_image" type="file" class="form-control" name="logo">
                                <label for="" class="col-form-label">[ Preferable size 500 × 450 px ]</label>
                                @if(isset($user->user_details->logo))
                                    <div class="preview mt-3" style="display: block">
                                        <img src="{{asset(''.$user->user_details->logo)}}" id="category-img-tag" height="100px" width="100px" />   <!--for preview purpose -->
                                    </div>
                                @else
                                    <div class="preview mt-3" style="display: none">
                                        <img src="#" id="category-img-tag" height="100px" width="120px" />   <!--for preview purpose -->
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label "> <b>Bank Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Bank Name: </label>
                            <div class="col-lg-9">
                                <input name="bank_name" type="text" class="form-control" placeholder="Bank Name" @if(isset($user->user_details)) value="{{$user->user_details->bank_name}}" @endif >
                                @if($errors->has('bank_name'))
                                    <div class="text-danger">{{ $errors->first('bank_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Sort Code: </label>
                            <div class="col-lg-9">
                                <input name="sort_code" type="text" class="form-control" placeholder="Sort Code" @if(isset($user->user_details)) value="{{$user->user_details->sort_code}}" @endif >
                                @if($errors->has('sort_code'))
                                    <div class="text-danger">{{ $errors->first('sort_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Account Number: </label>
                            <div class="col-lg-9">
                                <input name="account_number" type="text" class="form-control" placeholder="Account Number" @if(isset($user->user_details)) value="{{$user->user_details->account_number}}" @endif >
                                @if($errors->has('account_number'))
                                    <div class="text-danger">{{ $errors->first('account_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">IBAN: </label>
                            <div class="col-lg-9">
                                <input name="iban" type="text" class="form-control" placeholder="IBAN" @if(isset($user->user_details)) value="{{$user->user_details->iban}}" @endif >
                                @if($errors->has('iban'))
                                    <div class="text-danger">{{ $errors->first('iban') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Swift Code: </label>
                            <div class="col-lg-9">
                                <input name="swift_code" type="text" class="form-control" placeholder="Swift Code" @if(isset($user->user_details)) value="{{$user->user_details->swift_code}}" @endif >
                                @if($errors->has('swift_code'))
                                    <div class="text-danger">{{ $errors->first('swift_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label "> <b> Documents</b>  </label>
                            <div class="col-lg-4">
                                <select name="type_search" class="form-control" id="type_search">
                                    <option value="" disabled selected><b>Filter By Document Type</b></option>
                                    <option value="" >All</option>
                                    @foreach (doc_type() as $item)
                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{--old doc div <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Documents: </label>
                            <div class="col-lg-9 documentDiv">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                    <label class="upload__btn mb-3">
                                        <p id="upload">Upload</p>
                                        <input name="images[]" type="file" multiple="" data-max_length="5" class="upload__inputfile" >
                                    </label>
                                    </div>
                                    <div class="upload__img-wrap">

                                    </div>
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

                        <div class="text-end">
                            <a href="{{url()->previous()}}" class="btn btn-secondary">Discard</a>
                            <button type="submit" class="btn btn-primary">Save <i class="ph-paper-plane-tilt ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /basic layout -->
             <!-- Delete api data  -->
            <div class="card">
                <div class="card-header row">
                    <h5 class="mb-0">
                        <i class="ph-info" title="Admin can use this feature to In case of resetting/deleting the connected account/s. After resetting/deleting, client will have to connect new account/s (JobAdder, Xero, Google Analytics)"></i>
                        Delete/Reset Thirdparty Data:
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{route('delete_api_data')}}" method="post" >
                        <input type="hidden" name="client_id" id="client_id" value="{{$user->id}}">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label"> API: <span class="requiredData">*</span></label>
                            <div class="col-lg-3">
                                <select class="form-select" name="thirdparty_data" id="" required>
                                    <option value="" selected disabled>Select Thirdparty</option>
                                    <option value="xero_data">Xero data</option>
                                    <option value="jobadder_data">Jobadder data</option>
                                    <option value="ga_data">GA data</option>
                                </select>
                            </div>
                            @if($errors->has('thirdparty_data'))
                                <div class="error">{{ $errors->first('thirdparty_data') }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <a href="{{url()->previous()}}" class="btn btn-secondary">Discard</a>
                            <button type="submit" class="btn btn-primary">Reset <i class="ph-paper-plane-tilt ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Delete api data -->
        </div>
    <!-- /content area -->
    <script>
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



        $(document).ready(function(){

            $('#hide_show').html('Show');

            $("#hide_show").click(function(){
                if($("#hide_show").html()=='Show'){
                    $('#hide_show').html('Hide');
                }else{
                    $('#hide_show').html('Show');
                }
            });

            var user_documents = {!! json_encode($user->user_documents) !!};
            // console.log(user_documents);
            image_load(user_documents);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#type_search").change(function(){
                // alert(this.value);
                var client_id = $('#client_id').val();
                var formData = {
                    type_search: this.value,
                };
                var url = "{{url('client_doc_search/')}}"+'/'+client_id+'/edit';
                $.ajax({
                    type: "POST",
                    url : url,
                    data: formData,
                    dataType: "json",
                    encode: true,
                    }).done(function (data) {
                        $('.image_box_data').remove();
                        // window.location.reload();
                        image_load(data);
                });

            });

            // On upload click show all documents
            $("#upload3").click(function(){
                // alert('1');
                var client_id = $('#client_id').val();
                var formData = {
                    on_upload_click: 1,
                };
                var url = "{{url('client_doc_search/')}}"+'/'+client_id+'/edit';
                $.ajax({
                    type: "POST",
                    url : url,
                    data: formData,
                    dataType: "json",
                    encode: true,
                    }).done(function (data) {
                        // console.log(data);
                        $('#type_search').val(undefined);
                        $('.image_box_data').remove();
                        // window.location.reload();
                        image_load(data);
                });

            });

        });

        function image_load(user_documents){

            var user_documents = user_documents;
            let filesArr = [];

            for (let i = 0; i < user_documents.length; i++) {
                var file_extension = user_documents[i]['file'].split('.').pop();
                var file_name = user_documents[i]['file'].split('/').pop();
                // console.log(file_name);
                if(file_extension=='pdf'){
                    filesArr.push({id: user_documents[i]['id'],src:'{{asset("public/")}}/'+user_documents[i]['file'], name: user_documents[i]['title'], type_id:user_documents[i]['type_id'],image:config.pdf});
                }else if(file_extension=='doc' || file_extension=='docx'){
                    filesArr.push({id: user_documents[i]['id'],src:'{{asset("public/")}}/'+user_documents[i]['file'],name: user_documents[i]['title'],  type_id:user_documents[i]['type_id'],image:config.doc});
                }else{
                    filesArr.push({id: user_documents[i]['id'], src: '{{asset("public/")}}/'+user_documents[i]['file'],name: user_documents[i]['title'], type_id:user_documents[i]['type_id'],image:'{{asset("public/")}}/'+user_documents[i]['file']});
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
                                    </div><div class="col-lg-4">
                                    <b>Document Type</b>
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
                                <div class="col-md-4">
                                    <select name="old_document[]" class="form-select mb-2 text-muted" aria-label="Default select example">
                                        <option value=""  disabled>Select Document Type</option>
                                        <option value="4"${filesArr[i].type_id === 4 ? ' selected' : ''}>Marketing & brand</option>
                                        <option value="5"${filesArr[i].type_id === 5 ? ' selected' : ''}>Legal business documentation</option>
                                        <option value="6"${filesArr[i].type_id === 6 ? ' selected' : ''}>Templates</option>
                                    </select>
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
   </script>
@endsection
