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
	<!-- Quill CDN -->
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
		/* Global Select Validation Styles */
		select.is-invalid {
			border-color: #dc3545 !important;
			box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
		}
		
		select.is-invalid:focus {
			border-color: #dc3545 !important;
			box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
		}
		
		.form-group label .text-danger {
			color: #dc3545 !important;
		}
		
		/* Enhanced Table Styling */
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

		/* Enhanced Table Header Styling */
		table thead tr {
			background: linear-gradient(135deg, #ce6316ce 0%, #b16e17ff 100%) !important; /* Dark brown gradient */
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
		}

		table thead th {
			font-weight: 900 !important;
			font-size: 0.875rem !important;
			color: #ffffff !important;
			text-transform: uppercase !important;
			letter-spacing: 0.5px !important;
			padding: 1rem 0.75rem !important;
			border: none !important;
			text-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
		}

		/* Alternating row colors for better readability */
		table tbody tr:nth-child(even) {
			background-color: #f8f9fa !important;
		}

		table tbody tr:hover {
			background-color: #e3f2fd !important;
			transition: background-color 0.2s ease-in-out !important;
		}

		/* Enhanced badge styling */
		.badge {
			font-size: 0.75rem !important;
			padding: 0.5rem 0.75rem !important;
			border-radius: 0.375rem !important;
			font-weight: 600 !important;
		}

		/* Money/Amount column styling */
		td[data-kt-ecommerce-category-filter="category_name"] {
			font-weight: 700 !important;
			color: #28a745 !important;
			font-size: 1rem !important;
		}

		/* Action buttons styling */
		.btn-sm {
			padding: 0.375rem 0.75rem !important;
			font-size: 0.875rem !important;
			border-radius: 0.25rem !important;
		}

		/* Center checkboxes */
		.form-check-input {
			margin: 0 auto !important;
		}

		/* Responsive table improvements */
		.table-responsive {
			border-radius: 0.5rem !important;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07) !important;
		}
	</style>

	@yield('style')

	<!-- Custom Permissions Styling -->
	<style>

	:root {
		--primary-brown: #8B4513 !important;
		--secondary-brown: #A0522D !important;
		--light-brown: #D2B48C !important;
		--dark-brown: #644220ff !important;
		--cream: #F5F5DC !important;
	}

	.permissionCard {
		border: 2px solid #e4e6ef !important;
		border-radius: 15px !important;
		margin-bottom: 1.5rem !important;
		transition: all 0.3s ease !important;
		box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1) !important;
		background: white !important;
		overflow: hidden !important;
		position: relative !important;
	}

	.permissionCard:hover {
		box-shadow: 0 8px 25px rgba(139, 69, 19, 0.2) !important;
		transform: translateY(-3px) !important;
		border-color: var(--primary-brown) !important;
	}

	.role-title {
		background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%) !important;
		border-radius: 0 !important;
		padding: 1.2rem !important;
		font-weight: 700 !important;
		font-size: 1rem !important;
		color: white !important;
		text-shadow: 0 1px 2px rgba(0,0,0,0.3) !important;
		border-bottom: 3px solid var(--secondary-brown) !important;
		position: relative !important;
	}

	.permissionCard .card {
		border: none !important;
		box-shadow: none !important;
		margin-bottom: 0 !important;
		border-radius: 0 !important;
	}

	.permissionCard ul {
		padding: 1.2rem !important;
		margin: 0 !important;
		max-height: 320px !important;
		overflow-y: auto !important;
		background: linear-gradient(to bottom, #fefefe 0%, #f9f9f9 100%) !important;
	}

	.permissionCard ul::-webkit-scrollbar {
		width: 6px !important;
	}

	.permissionCard ul::-webkit-scrollbar-track {
		background: var(--cream) !important;
		border-radius: 3px !important;
	}

	.permissionCard ul::-webkit-scrollbar-thumb {
		background: var(--primary-brown) !important;
		border-radius: 3px !important;
		transition: background 0.3s ease !important;
	}

	.permissionCard ul::-webkit-scrollbar-thumb:hover {
		background: var(--dark-brown) !important;
	}

	.permissionCard li {
		padding: 0.7rem 0 !important;
		border-bottom: 1px solid #f0f0f0 !important;
		transition: all 0.2s ease !important;
	}

	.permissionCard li:last-child {
		border-bottom: none !important;
	}

	.permissionCard li:hover {
		background: rgba(139, 69, 19, 0.05) !important;
		padding-left: 0.3rem !important;
		border-radius: 5px !important;
	}

	.title_lable {
		font-size: 0.9rem !important;
		font-weight: 500 !important;
		color: #4a4a4a !important;
		margin-left: 0.7rem !important;
		cursor: pointer !important;
		transition: all 0.2s ease !important;
		line-height: 1.4 !important;
	}

	.title_lable:hover {
		color: var(--primary-brown) !important;
		font-weight: 600 !important;
	}

	.form-check-input:checked {
		background-color: var(--primary-brown) !important;
		border-color: var(--primary-brown) !important;
		box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25) !important;
	}

	.form-check-input:focus {
		border-color: var(--secondary-brown) !important;
		box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.25) !important;
	}

	.form-check-input {
		width: 1.2em !important;
		height: 1.2em !important;
		border: 2px solid #dee2e6 !important;
		transition: all 0.2s ease !important;
	}

	.selectP {
		margin: 0 !important;
		font-size: 0.95rem !important;
		font-weight: 700 !important;
		text-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
	}

	.permissions-container {
		background: linear-gradient(135deg, #faf9f7 0%, #f5f4f2 100%) !important;
		border-radius: 15px !important;
		padding: 2.5rem !important;
		margin-top: 1.5rem !important;
		border: 1px solid var(--light-brown) !important;
	}

	.permissions-header {
		background: white !important;
		border-radius: 12px !important;
		padding: 2rem !important;
		margin-bottom: 2.5rem !important;
		box-shadow: 0 6px 20px rgba(139, 69, 19, 0.1) !important;
		border: 2px solid var(--light-brown) !important;
	}

	.select-all-container {
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		flex-wrap: wrap !important;
		gap: 1.5rem !important;
	}

	.select-all-container .fas {
		color: var(--primary-brown) !important;
		font-size: 2.2rem !important;
	}


	.btn-primary {
		background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%) !important;
		border-color: var(--primary-brown) !important;
		border: none !important;
		padding: 0.8rem 2rem !important;
		font-weight: 600 !important;
		border-radius: 8px !important;
		transition: all 0.3s ease !important;
	}

	.btn-primary:hover, .btn-primary:focus, .btn-primary:active {
		background: linear-gradient(135deg, var(--dark-brown) 0%, var(--primary-brown) 100%) !important;
		border-color: var(--dark-brown) !important;
		transform: translateY(-1px) !important;
		box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3) !important;
	}


	.permissionCard::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(to right, var(--primary-brown), var(--secondary-brown));
		border-radius: 15px 15px 0 0;
	}

	.role-title::after {
		content: '';
		position: absolute;
		bottom: -3px;
		left: 0;
		right: 0;
		height: 3px;
		background: linear-gradient(to right, var(--secondary-brown), var(--primary-brown));
	}
	.iti.iti--container {
		top: 90% !important;
		left: 66% !important;
	}

	</style>

	<!-- Include intl-tel-input from CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const phoneInput = document.querySelector('input[type="tel"]'); // support both "phone" and "mobile_phone"
			// Expose instance globally so other scripts (e.g. sending-forms.js) can access it
			window.ini = window.ini || null;
					if (phoneInput && typeof window.intlTelInput === 'function') {
				try {
					var dropdownContainer = phoneInput.closest('div');


					window.ini = window.intlTelInput(phoneInput, {
						utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js',
						initialCountry: 'ae',
						separateDialCode: true,
						allowDropdown: true,
						autoHideDialCode: false,
						dropdownContainer: dropdownContainer,
					});
				} catch (err) {
					console.error('intlTelInput init error:', err);
				}
			} else {
				// Helpful debug info when ini is not defined
				if (!phoneInput) console.warn('Phone input not found: selector input[name="phone"]');
				if (typeof window.intlTelInput !== 'function') console.warn('intlTelInput library not loaded');
			}
		});
	</script>
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
	
	<!-- Global Delete Confirmation Translations -->
	<script>
		// Set global translations for delete confirmation (available on all dashboard pages)
		window.deleteTranslations = {
			confirm_delete: '{{ __("dashboard.confirm_delete") }}',
			delete_warning_message: '{{ __("dashboard.delete_warning_message") }}',
			yes_delete: '{{ __("dashboard.yes_delete") }}',
			cancel: '{{ __("dashboard.cancel") }}'
		};
	</script>
	
	<!-- Global Select Validation Script -->
	<script>
	$(document).ready(function() {
		// Global function to validate select fields with default values
		window.validateSelectFields = function(form) {
			let isValid = true;
			let firstInvalidField = null;
			
			// Find all select fields in the form that have required attribute or data-required
			$(form).find('select[required], select[data-required="true"]').each(function() {
				let $select = $(this);
				let value = $select.val();
				let label = $select.closest('.form-group').find('label').text().replace('*', '').trim();
				
				// Check if value is empty or is a default "choose" option
				if (!value || value === '' || value === '0' || 
					$select.find('option:selected').text().toLowerCase().includes('{{ strtolower(__("dashboard.choose")) }}') ||
					$select.find('option:selected').text().toLowerCase().includes('choose') ||
					$select.find('option:selected').text().toLowerCase().includes('اختر')) {
					
					isValid = false;
					
					// Mark field as invalid
					$select.addClass('is-invalid');
					
					// Store first invalid field for focus
					if (!firstInvalidField) {
						firstInvalidField = $select;
					}
				} else {
					// Remove invalid class if field is now valid
					$select.removeClass('is-invalid');
				}
			});
			
			if (!isValid) {
				// Show validation message
				Swal.fire({
					icon: 'warning',
					title: '{{ __("dashboard.error") }}',
					text: '{{ __("dashboard.please_select_required_fields") }}',
					confirmButtonText: '{{ __("dashboard.ok") }}'
				});
				
				// Focus on first invalid field
				if (firstInvalidField) {
					firstInvalidField.focus();
				}
			}
			
			return isValid;
		};
		
		// Auto-apply validation to all forms with class 'validate-selects'
		$('form.validate-selects').on('submit', function(e) {
			if (!window.validateSelectFields(this)) {
				e.preventDefault();
				return false;
			}
		});
		
		// Auto-apply validation to forms with select fields that have required attribute
		$('form').on('submit', function(e) {
			let $form = $(this);
			
			// Skip if form already has validation or is marked to skip
			if ($form.hasClass('validate-selects') || $form.hasClass('skip-select-validation')) {
				return;
			}
			
			// Check if form has required select fields
			if ($form.find('select[required]').length > 0) {
				if (!window.validateSelectFields(this)) {
					e.preventDefault();
					return false;
				}
			}
		});
		
		// Remove invalid class when user changes selection
		$(document).on('change', 'select.is-invalid', function() {
			let $select = $(this);
			let value = $select.val();
			
			if (value && value !== '' && value !== '0' && 
				!$select.find('option:selected').text().toLowerCase().includes('{{ strtolower(__("dashboard.choose")) }}') &&
				!$select.find('option:selected').text().toLowerCase().includes('choose') &&
				!$select.find('option:selected').text().toLowerCase().includes('اختر')) {
				$select.removeClass('is-invalid');
			}
		});
	});
	</script>
	
	@yield('scripts')
	@stack('scripts')

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
	<script>
		// keep existing session danger toast
	</script>
	@endif



</body>
<!--end::Body-->

</html>
