@extends('layouts.app')
@section('content')

{{-- Start: tagsinput  --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        height: 60px  !important;
    }
     .bootstrap-tagsinput input{
       border: none;
       box-shadow: none;
       outline: none;
       background-color: transparent;
       padding: 0 6px;
       margin: 0px;
        width: 135px; 
        height: 26px;
       max-width: inherit;
     }
   </style>
   {{-- End: tagsinput  --}}
    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Community</span>
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
                    <h5 class="mb-0">Edit User</h5>
                </div>

                <div class="card-body">
                    <form action="{{route('communities.update',$community_user->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Name: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" type="text" class="form-control" placeholder="Eugene Kopyov" value="{{$community_user->name}}" required>
                                @if($errors->has('name'))
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email:  <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" type="email" class="form-control" placeholder="email@gmail.com" value="{{$community_user->email}}" required>
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Location: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="location" type="text" class="form-control" placeholder="Location" value="{{$community_user->location}}" required>
                                @if($errors->has('location'))
                                    <div class="text-danger">{{ $errors->first('location') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Industry: <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="industry" type="text" class="form-control" placeholder="Industry" value="{{$community_user->industry}}" required>
                                @if($errors->has('industry'))
                                    <div class="text-danger">{{ $errors->first('industry') }}</div>
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

                        {{-- <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Specialist <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="specialist" type="text" class="form-control" placeholder="specialist" value="{{$community_user->specialist}}" required>
                                @if($errors->has('specialist'))
                                    <div class="text-danger">{{ $errors->first('specialist') }}</div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Specialism <span class="requiredData">*</span></label>
                            <div class="col-lg-9">
                                <input name="keywords" type="text" class="form-control " placeholder="Specialism" value="{{$community_user->keywords}}" required id="keywords">
                                @if($errors->has('keywords'))
                                    <div class="text-danger">{{ $errors->first('keywords') }}</div>
                                @endif
                                <span>[After each keyword use ENTER OR COMMA ( eg : php,js ) ]</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label  class="col-lg-3 col-form-label">Logo</label>
                            <div class="col-lg-9">
                              <input id="cat_image" type="file" class="form-control" name="logo">
                                @if(isset($community_user->logo))
                                    <div class="preview mt-3" style="display: block">
                                        <img src="{{asset(''.$community_user->logo)}}" id="category-img-tag" height="100px" width="100px" />   <!--for preview purpose -->
                                    </div>
                                @else
                                    <div class="preview mt-3" style="display: none">
                                        <img src="#" id="category-img-tag" height="100px" width="100px" />   <!--for preview purpose -->
                                    </div>
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
        // Start: To show image preview
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
        // End: To show image preview

        $(document).ready(function(){
             var tagsValue = $('#keywords').val();
            $('#keywords').val(tagsValue).tagsinput();
         });
    </script>

@endsection