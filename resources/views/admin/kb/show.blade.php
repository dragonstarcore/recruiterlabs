@extends('layouts.app')
@section('content')

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>

<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Home - <span class="fw-normal">Knowledge Base @if(isset($user)) - {{ucfirst($user->name)}}@endif</span>
            </h4>
            <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                <i class="ph-caret-down collapsible-indicator ph-sm m-1">1</i>
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
                <div class="col-lg-6"><h5 class="mb-0"> List</h5></div>
                <div class="col-lg-6 text-end">
                    @if (Auth::user()->role_type==1)
                         <a class="btn btn-primary" href="{{route('kb_create')}}">Create</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs nav-tabs-underline" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="nav-documents-tab" data-bs-toggle="tab" href="#nav-documents" role="tab" aria-controls="nav-documents" aria-selected="true">Document Library</a>
                    <a class="nav-link" id="nav-videos-tab" data-bs-toggle="tab" href="#nav-videos" role="tab" aria-controls="nav-videos" aria-selected="false">Video Library</a>
                    <a class="nav-link" id="nav-faqs-tab" data-bs-toggle="tab" href="#nav-faqs" role="tab" aria-controls="nav-faqs" aria-selected="false">FAQS</a>
                </div>
            </nav>
            <div class="tab-content mt-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-documents" role="tabpanel" aria-labelledby="nav-documents-tab">
                    {{-- <div class="row mb-3 document" >
                        <div class="col-lg-12 ">
                            <div class="upload__box"> --}}
                                {{-- <div class="upload__btn-box">
                                <label class="upload__btn mb-3">
                                    <p>Upload</p>
                                    <input name="images[]" type="file" multiple="" data-max_length="1" class="upload__inputfile" >
                                </label>
                                </div> --}}
                                {{-- <div class="upload__img-wrap"></div>
                            </div>
                            <span class="text-danger error" id="title"></span>
                            <span class="text-danger" style="color:red" id="imagecheck"></span>
                        </div>
                    </div> --}}
                    <div class="row mb-3">
                        <table class="table  employee_list text-nowrap">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $key => $document)
                                    <tr>
                                        <td>
                                            @if(isset($document->file))
                                                @php
                                                    $filename = $document->file; // Assuming $document->file contains the file name with extension
                                                    $extension = pathinfo($filename, PATHINFO_EXTENSION);

                                                    if ($extension == 'pdf') {
                                                        $image = 'public/assets/images/pdf.png';
                                                    } elseif (in_array($extension, ['jpg', 'jpeg'])) {
                                                        $image = 'public/'.$document->file;
                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                        $image = 'public/assets/images/doc.jpg';
                                                    }
                                                @endphp
                                                <img src="{{asset($image)}}" class="rounded-pill" width="36" height="36" alt="">
                                            @else
                                                <img src="{{asset('assets/images/default_user.jpg')}}" class="rounded-pill" width="36" height="36" alt="">
                                            @endif
                                        </td>
                                        <td class="text-body fw-semibold">@if(isset($document->title)){{ucfirst($document->title)}} @endif</td>
                                        <td class="d-flex ">
                                            @if(isset($document->file))
                                                <a href="{{asset(''.$document->file)}}" target="_blank"  class="btn btn-info btn-sm me-2"><i class="ph-eye"></i>
                                                </a>
                                            @elseif(isset($document->embedded_link))
                                                <a href="<?php echo $document->embedded_link; ?>"  target="_blank"  class="btn btn-info btn-sm me-2"><i class="ph-eye"></i>
                                                </a>
                                            @endif
                                            <form action="{{route('knowledgebases.destroy',$document->id)}}" method="post">
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
                <div class="tab-pane fade" id="nav-videos" role="tabpanel" aria-labelledby="nav-videos-tab">
                    {{-- <div class="row mb-3 document" >
                        <div class="col-lg-12 ">
                            <div class="upload__box2"> --}}
                                {{-- <div class="upload__btn-box">
                                <label class="upload__btn mb-3">
                                    <p>Upload</p>
                                    <input name="images[]" type="file" multiple="" data-max_length="1" class="upload__inputfile" >
                                </label>
                                </div> --}}
                                {{-- <div class="upload__img-wrap2"></div>
                            </div>
                            <span class="text-danger error" id="title"></span>
                            <span class="text-danger" style="color:red" id="imagecheck"></span>
                        </div>
                    </div> --}}
                    <div class="row mb-3">
                        <table class="table  employee_list text-nowrap">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($videos as $key => $video)
                                    <tr>
                                        <td>
                                            @if(isset($video->file))
                                                {{-- @php
                                                    $filename = $video->file; // Assuming $video->file contains the file name with extension
                                                    $extension = pathinfo($filename, PATHINFO_EXTENSION);

                                                    if (in_array($extension, ['mp4', 'avi'])) {
                                                        $image = 'public/assets/images/video_file.png';
                                                    }
                                                @endphp --}}
                                                <img src="{{asset('assets/images/video_file.png')}}" class="rounded-pill" width="36" height="36" alt="">
                                            @else
                                                <img src="{{asset('assets/images/video_file.png')}}" class="rounded-pill" width="36" height="36" alt="">
                                            @endif
                                        </td>
                                        <td class="text-body fw-semibold">@if(isset($video->title)){{ucfirst($video->title)}} @endif</td>
                                        <td class="d-flex">
                                            @if(isset($video->file))
                                                <a href="{{asset(''.$video->file)}}" target="_blank" class="btn btn-info btn-sm me-2"><i class="ph-eye"></i>
                                                </a>
                                            @elseif(isset($video->embedded_link))
                                                <a href="<?php echo $video->embedded_link; ?>"  target="_blank" class="btn btn-info btn-sm me-2"><i class="ph-eye"></i>
                                                </a>
                                            @endif
                                            <form action="{{route('knowledgebases.destroy',$video->id)}}" method="post">
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
                <div class="tab-pane fade" id="nav-faqs" role="tabpanel" aria-labelledby="nav-faqs-tab">
                    {{-- <div class="row mb-3">
                        <table class="table  employee_list ">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($FAQS as $data)
                                    <tr>
                                        <td class="text-body fw-semibold"><span class="keywords_break">@if(isset($data['question'])){{ucfirst($data['question'])}} @endif </span></td>
                                        <td class="text-muted">
                                            <span class="keywords_break">@if(isset($data['answer'])){{ucfirst($data['answer'])}} @endif</span>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <a href="{{route('kb_edit',['id'=>$data->id])}}" class=" btn btn-info btn-sm me-2">
                                                <i class="ph-pen"></i>
                                            </a>
                                            <form action="{{route('knowledgebases.destroy',$data->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" type="submit"><i class="ph-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                    <div class="row mb-3">
                        <div class="accordion" id="faqAccordion">
                            @foreach ($FAQS as $index => $data)

                            <div class="accordion-item">
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading{{ $index }}">
                                    <span class="d-flex align-items-center question_block fw-semibold" style="padding: 20px !important;">
                                        {{ ucfirst($data['question']) }}
                                    </span>
                                    <div class="d-flex me-auto ">
                                        <a href="{{route('kb_edit',['id'=>$data->id])}}" class="btn btn-info btn-sm me-2 ">
                                            <i class="ph-pen"></i>
                                        </a>
                                        <form action="{{route('knowledgebases.destroy',$data->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" type="submit" style="height: 40px;">
                                                <i class="ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <button class="accordion-button btn btn-sm custom-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                        <!-- Add your arrow icon here -->
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse @if($index === 0) show @endif" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {!! ucfirst(nl2br($data->answer)) !!}
                                    </div>
                                </div>
                            </div>


                            @endforeach
                        </div>
                        <div class="pagination-container mt-4">
                            {{ $FAQS->appends(['tab' => 'nav-faqs'])->links("pagination::bootstrap-4") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">

        $(document).ready(function(){

            //Start: Pagination with current tab
                $('.employee_list').dataTable( {
                    // ordering: false,
                    "language": {
                    "emptyTable": "No records found "
                    }
                } );

                    // Function to get the active tab from URL
                function getActiveTabFromUrl() {
                    var urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get('tab');
                }

                // Set the active tab on page load
                var activeTab = getActiveTabFromUrl();
                if (activeTab) {
                    $('.nav-link').removeClass('active');
                    $('#' + activeTab + '-tab').addClass('active');
                    $('.tab-pane').removeClass('show active');
                    $('#' + activeTab).addClass('show active');
                }

                // Handle pagination click
                $('.pagination-container').on('click', '.pagination a', function (e) {
                    e.preventDefault();

                    // Get the currently active tab
                    var activeTabId = $('.tab-content .tab-pane.active').attr('id');
                    var pageUrl = $(this).attr('href');

                    // Update the URL with the active tab
                    var newUrl = pageUrl.indexOf('?') !== -1 ? pageUrl + '&tab=' + activeTabId : pageUrl + '?tab=' + activeTabId;
                    window.history.pushState({ path: newUrl }, '', newUrl);

                    // Load the new content
                    $.ajax({
                        url: pageUrl,
                        type: 'GET',
                        success: function (data) {
                            // Update the content of the active tab with the fetched data
                            $('#' + activeTabId).html($(data).find('#' + activeTabId).html());
                        },
                        error: function () {
                            alert('Error loading content');
                        }
                    });
                });
            //End: Pagination with current tab

            //Start: Uploader FOR Documents
                // var client_documents = {!! json_encode($documents) !!};
                // let filesArr = [];

                // for (let i = 0; i < client_documents.length; i++) {
                //     if(client_documents[i]['embedded_link']==null){
                //         if(client_documents[i]['file_ext']=='pdf'){
                //             filesArr.push({id: client_documents[i]['id'],src:'{{asset("public/")}}/'+client_documents[i]['file'], name: client_documents[i]['title'],image:config.pdf});
                //         }else if(client_documents[i]['file_ext']=='doc' || client_documents[i]['file_ext']=='docx'){
                //             filesArr.push({id: client_documents[i]['id'],src:'{{asset("public/")}}/'+client_documents[i]['file'], name: client_documents[i]['title'], image:config.doc});
                //         }else{
                //             filesArr.push({id: client_documents[i]['id'], src: '{{asset("public/")}}/'+client_documents[i]['file'],name: client_documents[i]['title'], image:'{{asset("public/")}}/'+client_documents[i]['file']});
                //         }
                //     }else{
                //         filesArr.push({id: client_documents[i]['id'],src:client_documents[i]['embedded_link'], name: client_documents[i]['title'], image:config.pdf});
                //     }
                // }
                // //  console.log(filesArr);
                // let preloaded = [];
                //     for (var i = 0; i < filesArr.length; i++) {
                //         // console.log(filesArr[i].src);
                //         preloaded.push(filesArr[i].id);
                //         var html = "<div class='upload__img-box'><div style='background-image: url(" + filesArr[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg'></div><input type=hidden name='old_images[]['"+preloaded+"']' value='"+filesArr[i].id+"'><a target='_blank' href='" + filesArr[i].src + "'><p class='text-center'>"+filesArr[i].name+"<p></a><a class='btn btn-danger kb_del_btn' onclick='deleteentry("+filesArr[i].id+")'>Delete</a></div>";

                //         $('.upload__img-wrap').append(html);
                //     }
            //End: Uploader FOR Documents


            //Start: Uploader FOR VIDEOS

                // var client_videos = {!! json_encode($videos) !!};
                // let videos_array = [];
                // // console.log(client_videos);
                // for (let i = 0; i < client_videos.length; i++) {
                //     if(client_videos[i]['embedded_link']==null){
                //         videos_array.push({id: client_videos[i]['id'],src:client_videos[i]['file'], name: client_videos[i]['title'], image:config.video});
                //     }else{
                //         videos_array.push({id: client_videos[i]['id'],src:client_videos[i]['embedded_link'], name: client_videos[i]['title'], image:config.video});
                //     }
                // }
                // //  console.log(videos_array);
                // let preloaded1 = [];
                // for (var i = 0; i < videos_array.length; i++) {
                //     // console.log(client_videos[i]);
                //     preloaded1.push(videos_array[i].id);
                //     var html = "<div class='upload__img-box2'><div style='background-image: url(" + videos_array[i].image + ");margin-bottom:10px' data-number='" + i + "' data-file='" + i.name + "' class='img-bg2'></div><input type=hidden name='old_images[]['"+preloaded1+"']' value='"+videos_array[i].id+"'><a target='_blank' href='" + videos_array[i].src + "'><p class='text-center'>"+videos_array[i].name+"<p></a><a class='btn btn-danger kb_del_btn' onclick='deleteentry("+client_videos[i].id+")'>Delete</a></div>";

                //     $('.upload__img-wrap2').append(html);
                // }
            //End: Uploader FOR VIDEOS

        });

        function deleteentry(doc_id) {
            // Display a confirmation dialog
            var confirmation = confirm("Are you sure you want to delete this entry?");

            // Check the user's choice
            if (confirmation) {
                // User clicked "OK," proceed with the deletion
                var url = "{{ route('knowledgebases.destroy', ['knowledgebase' => ':doc_id']) }}";
                url = url.replace(':doc_id', doc_id);

                // Perform the AJAX call
                $.ajax({
                    url: url,
                    type: "DELETE", // Use POST or GET as appropriate
                    data: {
                        _token: "{{ csrf_token() }}", // Include CSRF token if needed
                        ajax_request : 'ajax_request',
                    },
                    success: function (response) {
                        // Check if the deletion was successful
                        if (response === "success") {
                            alert("Deleted successfully.");
                            // Reload the page
                            location.reload();
                        } else {
                            // Handle the error (e.g., display an error message)
                            alert("Failed to delete entry.");
                        }
                    },
                    error: function () {
                        // Handle AJAX call errors
                        alert("AJAX call failed.");
                    }
                });
            } else {
                // User clicked "Cancel," do nothing
            }
        }

    </script>
@endsection
