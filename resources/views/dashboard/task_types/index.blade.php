@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.task_types'))

@section('content')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Task Types-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-task-type-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search')" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Add task type-->
                        <a href="{{ route('task-types.create') }}" class="btn btn-primary">@lang('dashboard.create_task_type')</a>
                        <!--end::Add task type-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_task_type_table">
                        <!--begin::Table head-->
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-100px">@lang('dashboard.task_type_name')</th>
                            <th class="min-w-150px">@lang('dashboard.task_type_description')</th>
                            <th class="min-w-100px">@lang('dashboard.task_type_status')</th>
                            <th class="min-w-100px">@lang('dashboard.tasks') @lang('dashboard.count')</th>
                            <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                        </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                        @foreach ($taskTypes as $taskType)
                            <tr>
                                <td>
                                    <a href="{{ route('task-types.show', $taskType->id) }}" class="text-gray-800 text-hover-primary mb-1">{{ $taskType->name }}</a>
                                </td>
                                <td>{{ Str::limit($taskType->description, 50) }}</td>
                                <td>
                                <span class="badge badge-light-{{ $taskType->status == 'active' ? 'success' : 'danger' }}">
                                    @lang('dashboard.' . $taskType->status)
                                </span>
                                </td>
                                <td>{{ $taskType->tasks_count ?? $taskType->tasks()->count() }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        @lang('dashboard.actions')
                                        <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
{{--                                        <div class="menu-item px-3">--}}
{{--                                            <a href="{{ route('task-types.show', $taskType->id) }}" class="menu-link px-3">@lang('dashboard.view')</a>--}}
{{--                                        </div>--}}
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('task-types.edit', $taskType->id) }}" class="menu-link px-3">@lang('dashboard.edit')</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <form action="{{ route('task-types.destroy', $taskType->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="menu-link px-3 bg-transparent border-0 w-100 text-start"
                                                        onclick="return confirm('@lang('dashboard.confirm_delete')')"
                                                        @if($taskType->tasks()->count() > 0) disabled title="@lang('dashboard.cannot_delete_task_type_with_tasks')" @endif>
                                                    @lang('dashboard.delete')
                                                </button>
                                            </form>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Task Types-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
@endsection
