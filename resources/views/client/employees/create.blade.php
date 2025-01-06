@extends('layouts.app')
@section('content')

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
                    <h5 class="mb-0">Create Staff</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('employees.store')}}" method="post" enctype="multipart/form-data">
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
                            <label class="col-lg-3 col-form-label"> Address: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="address" type="text" class="form-control" placeholder="Address" value="{{old('address')}}" required>
                                @if($errors->has('address'))
                                    <div class="text-danger">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Phone Number: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" name="phone_number"  class="form-control phoneNumber" placeholder="Phone Number" value="{{old('phone_number')}}" required>
                                @if($errors->has('phone_number'))
                                    <div class="text-danger">{{ $errors->first('phone_number') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Date of Birth: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="date_of_birth" type="date" class="form-control" onfocus="this.showPicker()" placeholder="Date of Birth" value="{{old('date_of_birth')}}" required>
                                @if($errors->has('date_of_birth'))
                                    <div class="text-danger">{{ $errors->first('date_of_birth') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label  class="col-lg-3 col-form-label">Profile Picture</label>
                            <div class="col-lg-9">
                              <input id="cat_image" type="file" class="form-control" name="emp_picture">
                                    <div class="preview mt-3" style="display: none">
                                        <img src="#" id="category-img-tag" height="100px" width="120px" />   <!--for preview purpose -->
                                    </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-12 col-form-label"> <b>Other Details</b> </label>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Bank Name: </label>
                            <div class="col-lg-9">
                                <input name="bank_name" type="text" class="form-control" placeholder="Bank Name" value="{{old('bank_name')}}"  >
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
                            <label class="col-lg-3 col-form-label">Title: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="title" type="text" class="form-control" placeholder="Title" value="{{old('title')}}" required>
                                @if($errors->has('company_name'))
                                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Salary: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control money" name="salary"  placeholder="Salary" value="{{old('salary')}}" required>
                                @if($errors->has('salary'))
                                    <div class="text-danger">{{ $errors->first('salary') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Direct Reports: </label>
                            <div class="col-lg-9">
                                <input name="direct_reports" type="text" class="form-control" placeholder="Direct Reports" value="{{old('direct_reports')}}" >
                                @if($errors->has('direct_reports'))
                                    <div class="text-danger">{{ $errors->first('direct_reports') }}</div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Direct Reports: </label>
                            <div class="col-lg-9">
                                <select name="direct_reports" class="form-control form-control-select2" >
                                    <option value="">Choose</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
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
                                <input name="date_of_joining" type="date" class="form-control" onfocus="this.showPicker()" placeholder="Date of Joining" value="{{old('date_of_joining')}}" required>
                                @if($errors->has('date_of_joining'))
                                    <div class="text-danger">{{ $errors->first('date_of_joining') }}</div>
                                @endif
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


    // $('.document').show();
        var doc_type='people_doc_type';

        $(document).ready(function(){

            $('.upload__box').on('change', function (e) {
                var divsToHide = document.getElementsByClassName("upload__img-box");
                for(var i = 0; i < divsToHide.length; i++){
                    $(".form-select").hide();
                }
            });

            $(".money").inputmask({ alias: "decimal",integerDigits: 10,digits: 2,digitsOptional: false,placeholder: "0",allowMinus: false,prefix:'£ '});
            $(".phoneNumber").inputmask({ alias: "numeric",digits: false,digitsOptional: false,placeholder: "",allowMinus: false,});
        });

        </script>

@endsection
