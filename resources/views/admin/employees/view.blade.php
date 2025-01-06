@extends('layouts.app')
@section('content')

<script src="{{asset('assets/js/inputmask.js')}}"></script>

    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">People</span>
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
                    <h5 class="mb-0">Update People</h5>
                </div>

                <div class="card-body">
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Name: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->name}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->email}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label"> Address: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->address}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Phone Number: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->phone_number}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Date of Birth: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->date_of_birth}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label"> <b>Other Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Title: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->employee_details->title}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Salary: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->employee_details->salary}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Direct Reports: </label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->employee_details->direct_report_to->name}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Date of Joining: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">{{$employee->employee_details->date_of_joining}}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label"> <b>Documents</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Documents: </label>
                            <div class="col-lg-9 documentDiv">
                                <div class="upload__box">
                                    <div class="upload__btn-box">
                                    {{-- <label class="upload__btn mb-3">
                                        <p>Upload</p>
                                        <input name="images[]" type="file" multiple="" data-max_length="5" class="upload__inputfile" >
                                    </label> --}}
                                    </div>
                                    <div class="upload__img-wrap"></div>
                                </div>
                                <span class="text-danger error" id="title"></span>
                                <span class="text-danger" style="color:red" id="imagecheck"></span>
                            </div>
                        </div>

                </div>
            </div>
            <!-- /basic layout -->
        </div>
    <!-- /content area -->
    <script >
        $(document).ready(function(){

            var employee_documents = {!! json_encode($employee->employee_documents) !!};
            let filesArr = [];
            console.log(employee_documents);
            for (let i = 0; i < employee_documents.length; i++) {
                var file_extension = employee_documents[i]['file'].split('.').pop();
                var file_name = employee_documents[i]['file'].split('/').pop();
                console.log(file_name);
                if(file_extension=='pdf'){
                    filesArr.push({id: employee_documents[i]['id'],src:'{{asset("public/")}}/'+employee_documents[i]['file'], name: file_name, type_id:employee_documents[i]['type_id'],image:config.pdf});
                }else if(file_extension=='doc' || file_extension=='docx'){
                    filesArr.push({id: employee_documents[i]['id'],src:'{{asset("public/")}}/'+employee_documents[i]['file'],name: file_name,  type_id:employee_documents[i]['type_id'],image:config.pdf});
                }else{
                    filesArr.push({id: employee_documents[i]['id'], src: '{{asset("public/")}}/'+employee_documents[i]['file'],name: file_name, type_id:employee_documents[i]['type_id'],image:'{{asset("public/")}}/'+employee_documents[i]['file']});
                }
                
            }
            // console.log(filesArr);
            let preloaded = [];
                for (var i = 0; i < filesArr.length; i++) {
                
                if(filesArr[i].type_id==1){
                    preloaded.push(filesArr[i].id);
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'></div><input type='text' class='form-control' disabled value='General'><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>"+filesArr[i].name+"<p></a></div>";
                }else if(filesArr[i].type_id==2){
                    preloaded.push(filesArr[i].id);
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'></div><input type='text' class='form-control' disabled value='Terms'><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>"+filesArr[i].name+"<p></a></div>";
                }else if(filesArr[i].type_id==3){
                    preloaded.push(filesArr[i].id);
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'></div><input type='text' class='form-control' disabled value='Terms'><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>"+filesArr[i].name+"<p></a></div>";
                }
                    $('.upload__img-wrap').append(html);
                }

                $(".money").inputmask({ alias: "decimal",integerDigits: 10,digits: 2,digitsOptional: false,placeholder: "0",allowMinus: false,});
            $(".phoneNumber").inputmask({ alias: "numeric",digits: false,digitsOptional: false,placeholder: "",allowMinus: false,});

        });
    </script>

@endsection