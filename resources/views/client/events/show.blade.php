@extends('layouts.app')
@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" /> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" /> --}}

    <div class="page-header page-header-light shadow">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Events</span>
                </h4>
                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card" >
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6"><h5 class="mb-0">Manage Events</h5></div>
                    <div class="col-lg-6 text-end">
                        <a class="btn btn-primary" href="{{route('employees.index')}}">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="New-event-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="todo-list-tile">Add New Event</h2>
                </div>
                <div class="modal-body" id="todo-list-body">
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Title: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="title" id="title" type="text" class="form-control" placeholder="Title" value="" required>
                        </div>
                    </div>
                    {{-- <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Description: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="description" id="description" type="text" class="form-control" placeholder="Description" value="" required>
                        </div>
                    </div> --}}
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Location: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="location" id="location" type="text" class="form-control" placeholder="Location" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Start date: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="date" id="date" type="date" onfocus="this.showPicker()"  class="form-control" placeholder="Date" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">End date: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="end_date" id="end_date" type="date" onfocus="this.showPicker()"  class="form-control" placeholder="end_date" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-8 offset-lg-4">
                            <div id="error-message" class="text-danger"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Newevent-save-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Delete-event-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="todo-list-tile">Delete Event</h2>
                </div>
                <div class="modal-body" id="todo-list-body">
                    <div class="row mb-3">
                        <input type="hidden" id="deleteeventid" value="">
                        <label class="col-lg-12 col-form-label">Do you really want to delete?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Edit-event-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="todo-list-tile"> Event</h2>
                </div>
                <div class="modal-body" id="todo-list-body">
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Title: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="title" id="edittitle" type="text" class="form-control" placeholder="Title" value="" required>
                        </div>
                    </div>
                    {{-- <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Description: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="description" id="editdescription" type="text" class="form-control" placeholder="Description" value="" required>
                        </div>
                    </div> --}}
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Location: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="location" id="editlocation" type="text" class="form-control" placeholder="Location" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">Start date: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="date" id="editdate" type="date" onfocus="this.showPicker()" class="form-control" placeholder="Date" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label">End date: <span class="requiredData">*</span></label>
                        <div class="col-lg-8">
                            <input name="end_date" id="edit_end_date" type="date" onfocus="this.showPicker()" class="form-control" placeholder="End_date" value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="Delete-check-btn">Delete</button>
                    <button type="button" class="btn btn-primary"  id="Editevent-save-btn">Save</button>
                </div>
            </div>
        </div>
    </div>


<script>

    document.addEventListener("DOMContentLoaded", function () {
        const startDateInput = document.getElementById("date");
        const endDateInput = document.getElementById("end_date");
        const titleInput = document.getElementById("title");
        const locationInput = document.getElementById("location");
        const saveButton = document.getElementById("Newevent-save-btn");
        const errorMessage = document.getElementById("error-message");

        // Function to compare dates and check title and location
        function validateForm() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate < startDate) {
                errorMessage.textContent = "End date cannot be less than the start date.";
                saveButton.disabled = true;
            } else if (titleInput.value.trim() === "") {
                errorMessage.textContent = "Title cannot be empty.";
                saveButton.disabled = true;
            } else if (locationInput.value.trim() === "") {
                errorMessage.textContent = "Location cannot be empty.";
                saveButton.disabled = true;
            } else {
                errorMessage.textContent = "";
                saveButton.disabled = false;
            }
        }

        // Event listeners for input changes
        startDateInput.addEventListener("change", validateForm);
        endDateInput.addEventListener("change", validateForm);
        titleInput.addEventListener("input", validateForm);
        locationInput.addEventListener("input", validateForm);
    });

    $(document).ready(function () {

                var SITEURL = "{{ url('/') }}";

                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var calendar = $('#calendar').fullCalendar({
                    events: SITEURL + "/allevents",

                    contentHeight:500,
                    // eventRender: function (event, element, view) {
                    //     if (event.allDay === 'true') {
                    //         console.log(event);
                    //             event.allDay = true;
                    //     } else {
                    //             event.allDay = false;
                    //     }
                    // },
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                            // alert(start);
                            $('#date').val(start); // TO assign clicked date
                            $('#New-event-modal').modal('show');
                            $('#Newevent-save-btn').off().on('click', function (g){
                                // alert(1);
                                $('#New-event-modal').modal('hide');
                                var title = $('#title').val();
                                var description = $('#description').val();
                                var location = $('#location').val();
                                var date = $('#date').val();
                                var end_date = $('#end_date').val();
                                // alert(start);
                                $.ajax({
                                    url: SITEURL + "/fullcalenderAjax",
                                    data: {
                                        title: title,
                                        description: description,
                                        location: location,
                                        start: date,
                                        end: end_date,
                                        type: 'add'
                                    },
                                    type: "POST",
                                    success: function (data1) {
                                        // console.log(data);
                                        calendar.fullCalendar('renderEvent',
                                            {
                                                id: data1.id,
                                                title: title,
                                                description: description,
                                                location: location,
                                                start: date,
                                                end: end_date,
                                                allDay: allDay
                                            },true);

                                        calendar.fullCalendar('unselect');
                                        displayMessage("Event Created Successfully");
                                        $('#Newevent-save-btn').off('click');
                                    }
                                });
                            });
                    },
                    eventClick: function (event) {
                            // console.log(event);
                            $('#edittitle').val(event.title);
                            // $('#editdescription').val(event.description);
                            $('#editlocation').val(event.location);
                            $('#editdate').val(event.start['_i']);
                            if(event.end!=null){
                                $('#edit_end_date').val(event.end['_i']);
                            }else{
                                $('#edit_end_date').val();
                            }
                            $('#Edit-event-modal').modal('show');

                            //to edit an event
                            $('#Editevent-save-btn').on('click',function (f){
                                $('#Edit-event-modal').modal('hide');
                                var title = $('#edittitle').val();
                                // var description = $('#editdescription').val();
                                var location = $('#editlocation').val();
                                var date = $('#editdate').val();
                                var end_date = $('#edit_end_date').val();
                                // alert(start);
                                $.ajax({
                                    url: SITEURL + "/fullcalenderAjax",
                                    data: {
                                        id: event.id,
                                        title: title,
                                        // description: description,
                                        location: location,
                                        start: date,
                                        end: end_date,
                                        type: 'update'
                                    },
                                    type: "POST",
                                    success: function (response) {
                                        // console.log(data);
                                        calendar.fullCalendar('removeEvents');
                                        calendar.fullCalendar('refetchEvents',response.id);
                                                //     {
                                                //         id: data.id,
                                                //         title: data.title,
                                                //         // description: description,
                                                //         // location: location,
                                                //         // date: date,
                                                //         allDay: allDay
                                                //     },true);

                                            //   calendar.fullCalendar('unselect');
                                        displayMessage("Event Updated Successfully");
                                        $('#Editevent-save-btn').off('click');
                                        $('#Newevent-save-btn').off('click');
                                    }
                                });
                            });

                            //to delete an event
                            $('#Delete-check-btn').off().on('click',function (f){
                                $('#Edit-event-modal').modal('hide');
                                $('#Delete-event-modal').modal('show');
                                $('#Delete-btn').click(function (e){
                                    $('#Delete-event-modal').modal('hide');
                                    $.ajax({
                                        type: "POST",
                                        url: SITEURL + '/fullcalenderAjax',
                                        data: {
                                                id: event.id,
                                                type: 'delete'
                                        },
                                        success: function (response) {
                                            calendar.fullCalendar('removeEvents', event.id);
                                            displayMessage("Event Deleted Successfully");
                                        }
                                    });
                                });
                            });
                    }

                });

    });

    function displayMessage(message) {
        $('#title').val('');
        // $('#description').val('');
        $('#location').val('');
        $('#date').val('');
        $('#end_date').val('');
        // alert(message);
    }

</script>

@endsection
