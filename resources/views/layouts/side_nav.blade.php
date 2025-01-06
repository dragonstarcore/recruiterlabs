	<!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Sidebar header -->
            <div class="sidebar-section">
                <div class="sidebar-section-body d-flex justify-content-center">
                    <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>

                    <div>
                        <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                            <i class="ph-arrows-left-right"></i>
                        </button>

                        <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                            <i class="ph-x"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /sidebar header -->

            <!-- Main navigation -->
            <div class="sidebar-section">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <!-- Main -->
                    {{-- <li class="nav-item-header pt-0">
                        <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                        <i class="ph-dots-three sidebar-resize-show"></i>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link" id="home">
                            <i class="ph-house"></i>
                            <span>
                                Dashboard
                                {{-- <span class="d-block fw-normal opacity-50">No pending orders</span> --}}
                            </span>
                        </a>
                    </li>
                    @if(Auth::user()->can('client-list') && Auth::user()->hasRole('Admin'))
                        <li class="nav-item ">
                            <a href="{{route('users.index')}}" class="nav-link" id="client">
                                <i class="ph-user-list"></i>
                                <span>Clients</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('Admin'))
                        <li class="nav-item ">
                            <a href="{{route('client_list')}}" class="nav-link" id="admin_people">
                                <i class="ph-user"></i>
                                <span>Staff</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->can('client-edit') && Auth::user()->hasRole('Client'))
                        <li class="nav-item ">
                            <a href="{{route('my_business')}}" class="nav-link" id="client">
                                <i class="ph-user-circle"></i>
                                <span>My Business</span>
                            </a>
                        </li>
                    @endif

                     @if( Auth::user()->hasRole('Client'))
                        <li class="nav-item">
                            <a href="{{url('/jobadder_data')}}" class="nav-link" id="jobadder">
                                <i class="ph-columns" ></i>Performance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('xero.auth.success')}}" class="nav-link" id="xero">
                                <i class="ph-link"></i>Finance and Forecast
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('/manage/analytics')}}" class="nav-link" id="analytics">
                                <i class="ph-graph"></i>Google Analytics
                            </a>
                        </li>

                    @endif

                    @if(Auth::user()->can('people-list') && (Auth::user()->hasRole('Client')))
                        <li class="nav-item ">
                            <a href="{{route('employees.index')}}" class="nav-link" id="people">
                                <i class="ph-users"></i>
                                <span>My Staff</span>
                            </a>
                        </li>
                    @endif

                     {{-- @if( Auth::user()->hasRole('Client'))
                        <li class="nav-item ">
                            <a href="{{route('events.index')}}" class="nav-link" id="event">
                                <i class="ph-calendar-check"></i>
                                <span>Events</span>
                            </a>
                        </li>
                    @endif --}}


                    @if(Auth::user()->can('ticket-list') && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Client')))
                        <li class="nav-item ">
                            <a href="{{route('tickets.index')}}" class="nav-link" id="ticket">
                                <i class="ph-ticket"></i>
                                <span>Tickets</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Admin') )
                        <li class="nav-item ">
                            <a href="{{route('knowledgebases.index')}}" class="nav-link" id="KB1">
                                <i class="ph-book"></i>
                                <span>Knowledge Base</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Client'))
                        <li class="nav-item ">
                            <a href="{{route('knowledgebases.index')}}" class="nav-link" id="KB">
                                <i class="ph-book"></i>
                                <span>Knowledge Base</span>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="{{route('communities.index')}}" class="nav-link" id="community">
                                <i class="ph-users"></i>
                                <span>Community</span>
                            </a>
                        </li>


                        <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link">
								<i class="ph-squares-four"></i>
								<span>My Apps</span>
							</a>
							<ul class="nav-group-sub collapse">
								<li class="nav-item"><a href="https://login.xero.com/" target="_blank"  class="nav-link"><i class="ph-link"></i>Xero</a></li>
                                <li class="nav-item"><a href="https://analytics.google.com" target="_blank" class="nav-link" id="analytics"><i class="ph-graph"></i>Google Analytics</a></li>
								<li class="nav-item"><a href="https://jobadder.com/" target="_blank" class="nav-link"><i class="ph-columns" ></i>Jobadder</a></li>
								<li class="nav-item"><a href="https://www.microsoft.com/" target="_blank" class="nav-link"><i class="ph-article"></i>Microsoft</a></li>
								<li class="nav-item"><a href="https://www.linkedin.com/" target="_blank" class="nav-link"><i class="ph-dots-three-circle"></i>Linkedin</a></li>
								<li class="nav-item"><a href="https://www.sourcebreaker.com/" target="_blank" class="nav-link "><i class="ph-browser"></i>Sourcebreaker </a></li>
								<li class="nav-item"><a href="https://www.sonovate.com/" target="_blank" class="nav-link "><i class="ph-note-pencil"></i>Sonovate </a></li>
								<li class="nav-item"><a href="https://www.lastpass.com/" target="_blank" class="nav-link "><i class="ph-dots-three"></i>Lastpass </a></li>
								<li class="nav-item"><a href="https://www.vincere.io/" target="_blank" class="nav-link "><i class="ph-text-aa"></i>Vincere </a></li>
								<li class="nav-item"><a href="https://www.dropbox.com/" target="_blank" class="nav-link "><i class="ph-hand-pointing"></i>Dropbox </a></li>
								<li class="nav-item"><a href="https://login.microsoftonline.com/" target="_blank" class="nav-link "><i class="ph-layout"></i>Sharepoint </a></li>

                            </ul>
						</li>
                    @endif

                    @if(Auth::user()->hasRole('Admin'))
                        <li class="nav-item ">
                            <a href="{{route('communities.index')}}" class="nav-link" id="community">
                                <i class="ph-users"></i>
                                <span>Community</span>
                            </a>
                        </li>
                    @endif
                    <!-- /page kits -->
                </ul>
            </div>
            <!-- /main navigation -->
        </div>
        <!-- /sidebar content -->
    </div>
    <!-- /main sidebar -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script>
                $(document).ready(function() {
                    // alert(window.location.href.indexOf("xero"));
                     if (window.location.href.indexOf("home") > -1) {
                        $('#home').addClass('active');
                    }else if((window.location.href.indexOf("users") > -1) || (window.location.href.indexOf("my_business") > -1))  {
                        $('#client').addClass('active');
                    }else if (window.location.href.indexOf("tickets") > -1) {
                        $('#ticket').addClass('active');
                    }else if (window.location.href.indexOf("employees") > -1) {
                        $('#people,#admin_people').addClass('active');
                    }else if (window.location.href.indexOf("events") > -1) {
                        $('#event').addClass('active');
                    }else if ((window.location.href.indexOf("knowledgebases") > -1) || (window.location.href.indexOf("kb") > -1)) {
                        $('#KB').addClass('active');
                        $('#KB1').addClass('active');
                    }else if (window.location.href.indexOf("communities") > -1) {
                        $('#community').addClass('active');
                    }else if ((window.location.href.indexOf("client_list") > -1) || (window.location.href.indexOf("employee_list") > -1) ) {
                        $('#admin_people').addClass('active');
                    }else if ((window.location.href.indexOf("xero") > -1)) {
                        $('#xero').addClass('active');
                        // $('.nav-item-submenu').addClass('active nav-item-open');
                        // $('.nav-group-sub').addClass('show');
                    }else if ((window.location.href.indexOf("analytics") > -1))  {
                        //  alert('1');
                        //  $(".collapse").css({"visibility": "visible"});
                        $('#analytics').addClass('active');
                        // $('.nav-item-submenu').addClass('active nav-item-open');
                        // $('.nav-group-sub').addClass('show');

                    }else if ((window.location.href.indexOf("jobadder") > -1)) {
                        $('#jobadder').addClass('active');
                        // $('.nav-item-submenu').addClass('active nav-item-open');
                        // $('.nav-group-sub').addClass('show');
                    }

                });
            </script>
