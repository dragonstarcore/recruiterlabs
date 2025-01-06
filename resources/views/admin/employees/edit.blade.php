@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{asset('assets/js/inputmask.js')}}"></script>

    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Staff</span>
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
                    <h5 class="mb-0">Edit Staff</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('employees.update',$employee->id)}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="employee_id" value="{{$employee->id}}" id="employee_id">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Name: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" type="text" class="form-control" placeholder="Eugene Kopyov" value="{{$employee->name}}" required>
                                @if($errors->has('name'))
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" type="email" class="form-control" placeholder="email@gmail.com" value="{{$employee->email}}" required>
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label"> Address: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="address" type="text" class="form-control" placeholder="Address" value="{{$employee->address}}" required>
                                @if($errors->has('address'))
                                    <div class="text-danger">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Phone Number: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="phone_number" type="text" class="form-control phoneNumber" placeholder="Phone Number" value="{{$employee->phone_number}}" required>
                                @if($errors->has('phone_number'))
                                    <div class="text-danger">{{ $errors->first('phone_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Date of Birth: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="date_of_birth" type="date" class="form-control" onfocus="this.showPicker()" placeholder="Date of Birth" value="{{$employee->date_of_birth}}" required>
                                @if($errors->has('date_of_birth'))
                                    <div class="text-danger">{{ $errors->first('date_of_birth') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label  class="col-lg-3 col-form-label">Profile Picture</label>
                            <div class="col-lg-9">
                              <input id="cat_image" type="file" class="form-control" name="emp_picture">
                              <label for="" class="col-form-label">[ Preferable size 500 × 450 px ]</label>
                                @if(isset($employee->employee_details->emp_picture))
                                    <div class="preview mt-3" style="display: block">
                                        <img src="{{asset(''.$employee->employee_details->emp_picture)}}" id="category-img-tag" height="100px" width="100px" />   <!--for preview purpose -->
                                    </div>
                                @else
                                    <div class="preview mt-3" style="display: none">
                                        <img src="#" id="category-img-tag" height="100px" width="120px" />   <!--for preview purpose -->
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label"> <b>Other Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Bank Name: </label>
                            <div class="col-lg-9">
                                <input name="bank_name" type="text" class="form-control" placeholder="Bank Name" @if(isset($employee->employee_details)) value="{{$employee->employee_details->bank_name}}" @endif >
                                @if($errors->has('bank_name'))
                                    <div class="text-danger">{{ $errors->first('bank_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Sort Code: </label>
                            <div class="col-lg-9">
                                <input name="sort_code" type="text" class="form-control" placeholder="Sort Code" @if(isset($employee->employee_details)) value="{{$employee->employee_details->sort_code}}" @endif >
                                @if($errors->has('sort_code'))
                                    <div class="text-danger">{{ $errors->first('sort_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Account Number: </label>
                            <div class="col-lg-9">
                                <input name="account_number" type="text" class="form-control" placeholder="Account Number" @if(isset($employee->employee_details)) value="{{$employee->employee_details->account_number}}" @endif >
                                @if($errors->has('account_number'))
                                    <div class="text-danger">{{ $errors->first('account_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Title: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="title" type="text" class="form-control" placeholder="Title" value="{{$employee->employee_details->title}}" required>
                                @if($errors->has('company_name'))
                                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Salary: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="salary" type="text" class="form-control money" placeholder="Salary" value="{{$employee->employee_details->salary}}" required>
                                @if($errors->has('salary'))
                                    <div class="text-danger">{{ $errors->first('salary') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Direct Reports: </label>
                            <div class="col-lg-9">
                                <select name="direct_reports" class="form-control form-control-select2" >
                                    <option value="">Choose</option>
                                    @foreach ($employee_list as $item)
                                        <option @if($item->id==$employee->employee_details->direct_reports) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('direct_reports'))
                                    <div class="text-danger">{{ $errors->first('direct_reports') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Date of Joining: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="date_of_joining" type="date" class="form-control" onfocus="this.showPicker()" placeholder="Date of Joining" value="{{$employee->employee_details->date_of_joining}}" required>
                                @if($errors->has('date_of_joining'))
                                    <div class="text-danger">{{ $errors->first('date_of_joining') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label "> <b> Documents</b>  </label>
                            <div class="col-lg-4 text-end">
                                <input type="text" name="title_search" placeholder="Search Title" class="form-control" id="title_search">
                            </div>
                            <div class="col-lg-1 text-end">
                                <button type="button" id="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>


                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Documents: </label>
                            <div class="col-lg-9 documentDiv">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                    <label class="upload__btn mb-3">
                                        <p id="upload">Upload</p>
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
    <script >
            // start: Profile image
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
        // end : profile image
        // Start : TO remove document type select dropdown for each image
        var doc_type='people_doc_type';
        // End : TO remove document type select dropdown for each image

        $(document).ready(function(){
            // var doc_type='people_doc_type';

            var employee_documents = {!! json_encode($employee->employee_documents) !!};
            // console.log(employee_documents);
            image_load(employee_documents);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#submit").click(function(){
                // alert(this.value);
                var employee_id= $('#employee_id').val();
                var formData = {
                    title_search: $('#title_search').val(),
                };
                var url = "{{url('people_doc_search/')}}"+'/'+employee_id+'/edit';

                $.ajax({
                    type: "POST",
                    url:url,
                    data: formData,
                    dataType: "json",
                    encode: true,
                    }).done(function (data) {
                        // console.log(data);
                        $('.image_box_data').remove();
                        // window.location.reload();
                        image_load(data);
                });

            });

            // Start : on upload click reload all docs
            $("#upload3").click(function(){
                //  alert('1');
                var employee_id= $('#employee_id').val();
                var formData = {
                    on_upload_click: 1,
                };
                var url = "{{url('people_doc_search/')}}"+'/'+employee_id+'/edit';

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: "json",
                    encode: true,
                    }).done(function (data) {
                        // console.log(data);
                        $('#title_search').val(undefined);
                        $('.image_box_data').remove();// To empty imageloaded div
                        image_load(data);// To load all images
                });
            });
            // End : on upload click reload all docs

            $(".money").inputmask({ alias: "decimal",integerDigits: 10,digits: 2,digitsOptional: false,placeholder: "0",allowMinus: false,prefix:'£ '});
            $(".phoneNumber").inputmask({ alias: "numeric",digits: false,digitsOptional: false,placeholder: "",allowMinus: false,});



        });

        function image_load(employee_documents){
            var employee_documents = employee_documents;
            let filesArr = [];
            // console.log(employee_documents);
            for (let i = 0; i < employee_documents.length; i++) {
                var file_extension = employee_documents[i]['file'].split('.').pop();
                var file_name = employee_documents[i]['file'].split('/').pop();
                // console.log(file_name);
                if(file_extension=='pdf'){
                    filesArr.push({id: employee_documents[i]['id'],src:'{{asset("public/")}}/'+employee_documents[i]['file'], name: employee_documents[i]['title'], type_id:employee_documents[i]['type_id'],image:config.pdf});
                }else if(file_extension=='doc' || file_extension=='docx'){
                    filesArr.push({id: employee_documents[i]['id'],src:'{{asset("public/")}}/'+employee_documents[i]['file'],name: employee_documents[i]['title'],  type_id:employee_documents[i]['type_id'],image:config.doc});
                }else{
                    filesArr.push({id: employee_documents[i]['id'], src: '{{asset("public/")}}/'+employee_documents[i]['file'],name: employee_documents[i]['title'], type_id:employee_documents[i]['type_id'],image:'{{asset("public/")}}/'+employee_documents[i]['file']});
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
