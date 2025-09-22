@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.task_type') . ': ' . $taskType->name)

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                     data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                        @lang('dashboard.task_type'): {{ $taskType->name }}
                    </h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('home') }}" class="text-muted text-hover-primary">@lang('dashboard.home')</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('task-types.index') }}" class="text-muted text-hover-primary">@lang('dashboard.task_types')</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-dark">{{ $taskType->name }}</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('task-types.edit', $taskType->id) }}" class="btn btn-sm btn-primary">@lang('dashboard.edit')</a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Task Type Details-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">@lang('dashboard.task_type') @lang('dashboard.details')</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center mb-8">
                                    <!--begin::Title-->
                                    <span class="fs-6 fw-bolder text-gray-800 flex-grow-1">@lang('dashboard.task_type_name'):</span>
                                    <!--end::Title-->
                                    <!--begin::Value-->
                                    <span class="fs-6 text-gray-600">{{ $taskType->name }}</span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mb-8">
                                    <!--begin::Title-->
                                    <span class="fs-6 fw-bolder text-gray-800 flex-grow-1">@lang('dashboard.task_type_status'):</span>
                                    <!--end::Title-->
                                    <!--begin::Value-->
                                    <span class="badge badge-light-{{ $taskType->status == 'active' ? 'success' : 'danger' }}">
                                        @lang('dashboard.' . $taskType->status)
                                    </span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-start mb-8">
                                    <!--begin::Title-->
                                    <span class="fs-6 fw-bolder text-gray-800 flex-grow-1">@lang('dashboard.task_type_description'):</span>
                                    <!--end::Title-->
                                    <!--begin::Value-->
                                    <span class="fs-6 text-gray-600 text-end" style="max-width: 300px;">
                                        {{ $taskType->description ?: __('dashboard.no_description') }}
                                    </span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mb-8">
                                    <!--begin::Title-->
                                    <span class="fs-6 fw-bolder text-gray-800 flex-grow-1">@lang('dashboard.created_at'):</span>
                                    <!--end::Title-->
                                    <!--begin::Value-->
                                    <span class="fs-6 text-gray-600">{{ $taskType->created_at->format('Y-m-d H:i') }}</span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Title-->
                                    <span class="fs-6 fw-bolder text-gray-800 flex-grow-1">@lang('dashboard.updated_at'):</span>
                                    <!--end::Title-->
                                    <!--begin::Value-->
                                    <span class="fs-6 text-gray-600">{{ $taskType->updated_at->format('Y-m-d H:i') }}</span>
                                    <!--end::Value-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Task Type Details-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Associated Tasks-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">@lang('dashboard.tasks') ({{ $taskType->tasks->count() }})</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                @if($taskType->tasks->count() > 0)
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed gy-5">
                                            <thead>
                                                <tr class="fw-bolder fs-6 text-gray-800">
                                                    <th>@lang('dashboard.title')</th>
                                                    <th>@lang('dashboard.assigned_to')</th>
                                                    <th>@lang('dashboard.task_status')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($taskType->tasks->take(10) as $task)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-gray-800 text-hover-primary">
                                                                {{ Str::limit($task->title, 30) }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $task->assignedUser->name }}</td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $task->status == 'completed' ? 'success' : ($task->status == 'failed' ? 'danger' : ($task->status == 'in_progress' ? 'info' : 'warning')) }}">
                                                                {{ str_replace('_', ' ', ucfirst( __('dashboard.' . $task->status) )) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                    @if($taskType->tasks->count() > 10)
                                        <div class="text-center mt-5">
                                            <span class="text-muted">{{ __('dashboard.showing') }} 10 {{ __('dashboard.of') }} {{ $taskType->tasks->count() }} {{ __('dashboard.tasks') }}</span>
                                        </div>
                                    @endif
                                @else
                                    <!--begin::Empty state-->
                                    <div class="text-center py-10">
                                        <div class="mb-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-gray-400" viewBox="0 0 16 16">
                                                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM7.5 3.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 7.293V3.5z"/>
                                            </svg>
                                        </div>
                                        <h5 class="text-gray-600 mb-0">@lang('dashboard.no_tasks_found')</h5>
                                        <span class="text-gray-500">@lang('dashboard.no_tasks_associated_with_this_type')</span>
                                    </div>
                                    <!--end::Empty state-->
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Associated Tasks-->
                    </div>
                    <!--end::Col-->
                </div>
            </div>
            <!--end::Container-->
        </div>
    </div>
@endsection
