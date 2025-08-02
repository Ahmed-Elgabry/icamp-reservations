<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" direction="{{app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}" dir="{{app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}" style="direction: {{ app()->getLocale() == 'en' ? 'ltr' : 'rtl'}}">
<!--begin::Head-->

<head>
	<title>@lang('dashboard.app_name') | @yield('pageTitle')</title>
	<meta charset="utf-8" />
	<meta name="description" content="@lang('dashboard.app_desc')" />
	<meta name="keywords" content="@lang('dashboard.app_key_words')" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta property="og:locale" content="{{ (App::isLocale('ar') ? 'ar_EG' : 'en_US') }}" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="@lang('dashboard.app_name')" />
	<meta property="og:url" content="{{ Request::fullUrl() }}" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="600" />
	<meta property="og:site_name" content="@lang('dashboard.app_name')" />
	<link rel="canonical" href="{{ Request::fullUrl() }}" />
	<link rel="shortcut icon" href="{{asset('images/logo.png')}}" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

	<!-- html5-qrcode -->
	<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
	<!-- CKeditor -->
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

	<link href="{{asset('css/jquery.toast.css')}}" rel="stylesheet" />

	<link href="https://fonts.googleapis.com/css2?family=Changa:wght@200;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="{{asset('dashboard/custom/css/main.css')}}" rel="stylesheet" />

	@if(app()->getLocale() == "en")
	<!--EN-->
	<!--begin::Page Vendor Stylesheets(used by this page)-->
	<link href="{{asset('dashboard/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('dashboard/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('dashboard/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->
	<!--EN-->
	@else
	<!--ar-->
	<link href="{{asset('dashboard/assets/plugins/custom/prismjs/prismjs.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('dashboard/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('dashboard/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
	<!--ar-->
	@endif

	@stack('css')
	@if(app()->getLocale() != 'en')
	<style>
		input,
		label {
			text-align: right !important
		}
	</style>
	@endif

	<style>
		table th,
		table td,
		table td div,
		table .text-end {
			text-align: center !important;
		}

		table td div a {
			text-align: center !important;
			display: block
		}
	</style>

	@yield('style')
</head>
<!--end::Head-->
<!--begin::Body-->


<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="page d-flex flex-row flex-column-fluid">
			<!--begin::Aside-->
			<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
				<!--begin::Brand-->
				<div class="aside-logo flex-column-auto" id="kt_aside_logo">
					<!--begin::Logo-->
					<a href="{{route('home')}}">

					</a>
					<!--end::Logo-->
					<!--begin::Aside toggler-->
					<div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
						<span class="svg-icon svg-icon-1 rotate-180">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
								<path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</div>
					<!--end::Aside toggler-->
				</div>
				<!--end::Brand-->
				<!--begin::Aside menu-->
				<div class="aside-menu flex-column-fluid">
					<!--begin::Aside Menu-->
					<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
						<!--begin::Menu-->
						@include('dashboard.layouts.menu')
						<!--end::Menu-->
					</div>
					<!--end::Aside Menu-->
				</div>
				<!--end::Aside menu-->
			</div>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<!--begin::Header-->
				@include('dashboard.layouts.header')
				<!--end::Header-->

				@yield('content')


				@include('dashboard.layouts.footer')
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>


	<script>
		var hostUrl = "assets/";
	</script>
	<script src="{{asset('dashboard/assets/js/jquery.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/popper.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/bootstrap.js')}}"></script>
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('dashboard/assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/scripts.bundle.js')}}"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="{{asset('dashboard/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<!--end::Page Vendors Javascript-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('dashboard/custom/js/dataTable.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/widgets.bundle.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/custom/widgets.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/custom/apps/chat/chat.js')}}"></script>
	<script src="{{asset('dashboard/custom/plugins/jquery-localize/dist/jquery.localize.min.js')}}"></script>
	<script src="{{asset('dashboard/custom/js/main.js')}}"></script>
	<script src="{{asset('js/jquery.toast.js')}}"></script>
	<script src="{{asset('dashboard/custom/js/jquery.validate.min.js')}}"></script>
	@if(lang() == "ar")
	<script src="{{asset('dashboard/custom/js/messages_ar.js')}}"></script>
	@endif
	<script type="text/javascript" src="{{ URL::asset('dashboard/custom/plugins/axios/dist/axios.min.js') }}"></script>
	<script src="{{asset('dashboard/custom/js/sending-forms.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/custom/utilities/modals/create-app.js')}}"></script>
	<script src="{{asset('dashboard/assets/js/custom/utilities/modals/users-search.js')}}"></script>
	<!--end::Page Custom Javascript-->
	@yield('scripts')

	@stack('js')

	<!--end::Page Custom Javascript-->
	<!--- hundel messges --->
	@if($errors->any())
	@foreach($errors->all() as $e)
	<script>
		$.toast({
			text: "{{$e}}",
			allowToastClose: true,
			hideAfter: 12000,
			position: 'top-right',
			bgColor: '#ff0000'
		})
	</script>
	@endforeach
	@endif

	@if(session()->has('success'))
	<script>
		$.toast({
			text: "{{ session()->get('success') }}",
			allowToastClose: true,
			hideAfter: 12000,
			position: 'top-right',
			bgColor: 'green'
		})
	</script>
	@endif


	<script>
		function successMessge(messge) {
			$.toast({
				text: messge,
				allowToastClose: true,
				hideAfter: 12000,
				position: 'top-right',
				bgColor: 'green'
			});
		}
	</script>

	@if(session()->has('error'))
	<script>
		$.toast({
			text: "{{ session()->get('error') }}",
			allowToastClose: true,
			hideAfter: 12000,
			position: 'top-right',
			bgColor: '#ff0000'
		})
	</script>
	@endif

	@if(session()->has('danger'))
	<script>
		$.toast({
			text: "{{ session()->get('danger') }}",
			allowToastClose: true,
			hideAfter: 12000,
			position: 'top-right',
			bgColor: '#ff0000'
		})
	</script>
	@endif

</body>
<!--end::Body-->

</html>