<div id="kt_header" style="" class="header align-items-stretch">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
                <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
                        <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </div>
        </div>
        <!--end::Aside mobile toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{route('home')}}" class="d-lg-none">
                <img alt="Logo" src="{{asset('images/logo.png')}}" class="h-30px" />
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <!--begin::Menu wrapper-->
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <!--begin::Menu-->
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" data-kt-menu="true">
                        <div  data-kt-menu-placement="bottom-start" class="menu-item here show menu-lg-down-accordion me-lg-1">
                            <span class="menu-link py-3">
                                <span class="menu-title"><a href="{{route('home')}}">@lang('dashboard.back_to_home')</a></span>
                                <span class="menu-arrow d-lg-none"></span>
                            </span>
                        </div>
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::Navbar-->

            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">
                <!--begin::Notifications-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <!--begin::Menu-wrapper-->
                    <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <i class="fas fa-bell fs-2"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </div>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" dir="rtl">
                        <!--begin::Heading-->
                        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-color: #f8f5ff">
                            <!--begin::Title-->
                            <h3 class="text-dark fw-bold px-9 mt-10 mb-6 text-end">
                                @lang('dashboard.notifications')
                                <span class="fs-8 opacity-75 pe-3">{{ auth()->user()->unreadNotifications->count() }} @lang('dashboard.unread')</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab panel-->
                            <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
                                <!--begin::Items-->
                                <div class="scroll-y mh-325px my-5 px-8">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        @php
                                            $data = $notification->data;
                                        @endphp
                                            <!--begin::Item-->
                                        <div class="d-flex flex-stack py-4">
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-35px ms-4">
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="fas fa-tasks text-primary"></i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Title-->
                                                <div class="mb-0 ms-2 text-end">
                                                    <a href="{{ $data['url'] ?? '#' }}"
                                                       class="text-gray-800 text-hover-primary fw-bold"
                                                       onclick="event.preventDefault(); markNotificationAsRead('{{ $notification->id }}', '{{ $data['url'] }}')">
                                                        {{ trim($data['title']) }}
                                                    </a>
                                                    <div class="text-gray-500 fs-7">{{ $data['message'] }}</div>
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                        <!--end::Item-->
                                    @empty
                                        <div class="text-center py-10">
                                            <i class="fas fa-bell-slash fs-2x text-gray-400 mb-4"></i>
                                            <div class="text-gray-600">@lang('dashboard.no_new_notifications')</div>
                                        </div>
                                    @endforelse
                                </div>
                                <!--end::Items-->
                                <!--begin::View more-->
{{--                                <div class="py-3 text-center border-top">--}}
{{--                                    <a href="{{ route('employee.tasks') }}" class="btn btn-color-gray-600 btn-active-color-primary">--}}
{{--                                        @lang('dashboard.view_all_notifications')--}}
{{--                                        <i class="fas fa-arrow-left me-2"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
                                <!--end::View more-->
                            </div>
                            <!--end::Tab panel-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Notifications-->
                <!--begin::User menu-->
                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <img src="{{ auth()->user()->image ? auth()->user()->image : asset('images/logo.png') }}" alt="user" />
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="{{ auth()->user()->image ? auth()->user()->image : asset('images/logo.png') }}" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                    </div>
                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
                            <a href="#" class="menu-link px-5">
                                <span class="menu-title position-relative">@lang('dashboard.language')
                                <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">{{session()->get( 'lang' ) == "en" ? "English" : "العربية"}}
                            </a>
                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                <!--begin::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 langauge-switcher">
                                    <a  href="{{route('set-lang','ps')}}"  switchToLang="ps" class="menu-link d-flex px-5  {{ session()->get( 'lang' ) == "ps" ? "active" : ""}}">
                                    <span class="symbol symbol-20px me-4">
                                        <img class="rounded-1" src="{{asset('dashboard/assets/media/flags/afghanistan.svg')}}" alt="" />
                                    </span>البشتو </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 langauge-switcher">
                                    <a  href="{{route('set-lang','ar')}}"  switchToLang="ar" class="menu-link d-flex px-5  {{ session()->get( 'lang' ) == "ar" ? "active" : ""}}">
                                    <span class="symbol symbol-20px me-4">
                                        <img class="rounded-1" src="{{asset('dashboard/assets/media/flags/saudi-arabia.svg')}}" alt="" />
                                    </span>العربية</a>
                                </div>
                                <!--end::Menu item-->
                                <div class="menu-item px-3 langauge-switcher">
                                    <a  switchToLang="en" href="{{route('set-lang','en')}}" class="menu-link d-flex px-5 {{ session()->get( 'lang' ) == "en" ? "active" : ""}}">
                                    <span class="symbol symbol-20px me-4">
                                        <img class="rounded-1" src="{{asset('dashboard/assets/media/flags/united-states.svg')}}" alt="" />
                                    </span>English</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5 my-1">
                            <a href="{{ route('edit-profile') }}" class="menu-link px-5">@lang('dashboard.account_settings')</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="{{ route('logout') }}" class="menu-link px-5">@lang('dashboard.sign_out')</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                    </div>
                    <!--end::User account menu-->
                    <!--end::Menu wrapper-->
                </div>
                <!--end::User menu-->
                <!--begin::Header menu toggle-->
                <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
                    <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                        <!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor" />
                                <path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                </div>
                <!--end::Header menu toggle-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Container-->
</div>
</div>
<script>
    function markNotificationAsRead(notificationId, url) {
        fetch('/notifications/' + notificationId + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    window.location.href = url;
                }
            });
    }
</script>
