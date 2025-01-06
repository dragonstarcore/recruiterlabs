<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>RecruiterLabs</title>

	<!-- Global stylesheets -->
	<link href="{{asset('assets/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/ltr/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<link href="{{asset('assets/css/imageUploader.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

	<!-- Core JS files -->
	<script src="{{asset('assets/demo/demo_configurator.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
	<!-- /core JS files -->

    <!-- Theme JS files -->
	{{-- <script src="{{asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
	<script src="{{asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script> --}}

	<script src="{{asset('assets/js/app.js')}}"></script>
	{{-- <script src="{{asset('assets/demo/pages/dashboard.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/streamgraph.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/sparklines.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/lines.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/areas.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/donuts.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/bars.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/progress.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/heatmaps.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/pies.js')}}"></script>
	<script src="{{asset('assets/demo/charts/pages/dashboard/bullets.js')}}"></script> --}}

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="{{asset('assets/js/imageUploader.js')}}"></script>
    <script src="{{asset('assets/js/imageUploader1.js')}}"></script>


	<!-- /theme JS files -->

	<script type="text/javascript">
		var config={
			pdf:"{{ asset ('public/assets/images/pdf.png')}}",
			doc:"{{ asset ('public/assets/images/doc.jpg')}}",
			video:"{{ asset ('public/assets/images/video_file.png')}}"

		};
		</script>


</head>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LYF7XGS7NK"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-LYF7XGS7NK');
</script>
<body>
	<!-- header -->
    @include('layouts.header')
	<!-- /header -->
	<!-- Page content -->
	<div class="page-content">
		<!-- side_nav -->
		@include('layouts.side_nav')
		<!-- /side_nav -->
		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Inner content -->
			<div class="content-inner">
				<!-- Page header -->
				{{-- <div class="page-header page-header-light shadow">
					<div class="page-header-content d-lg-flex">
						<div class="d-flex">
							<h4 class="page-title mb-0">
								Home - <span class="fw-normal">Dashboard</span>
							</h4>
							<a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
								<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
							</a>
						</div>
					</div>
				</div> --}}
				<!-- /page header -->
				<!-- Content area -->
				@yield('content')
				<!-- /content area -->
				<!-- footer -->
				@include('layouts.footer')
				<!-- /footer -->
			</div>
			<!-- /inner content -->
		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->

	{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="{{asset('assets/js/imageUploader.js')}}"></script> --}}
</body>
</html>
