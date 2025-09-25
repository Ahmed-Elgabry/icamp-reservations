@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.equipment_directories'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <input type="text" class="form-control form-control-solid w-250px ps-14"
                           placeholder="@lang('dashboard.search_directories')" id="search-input"/>
                </div>
            </div>
            <div class="card-toolbar">
                @can('equipment.export')
                <a href="{{ route('equipment-directories.export') }}"
                   class="btn btn-success me-2" title="@lang('dashboard.export_pdf')">
                    <i class="fas fa-file-pdf"></i> @lang('dashboard.export_pdf')
                </a>
                @endcan
                @if(Gate::allows('equipment.create'))
                <a href="{{ route('equipment-directories.create') }}" class="btn btn-primary">
                    @lang('dashboard.create_directory')
                </a>
                @endif
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-100px">@lang('dashboard.directory_name')</th>
                        <th class="min-w-50px">@lang('dashboard.sub_items_count')</th>
                        <th class="min-w-50px">@lang('dashboard.media_count')</th>
                        <th class="min-w-100px">@lang('dashboard.creator') / @lang('dashboard.date')</th>
                        <th class="min-w-100px">@lang('dashboard.directory_status')</th>
                        <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($directories as $directory)
                        <tr>
                            <td>{{ $directory->name }}</td>
                            <td>{{ $directory->items_count }}</td>
                            <td>{{ $directory->media_count }}</td>
                            <td>
                                {{ $directory->creator->name }}<br>
                                {{ $directory->created_at->format('Y-m-d h:i A') }}
                            </td>
                            <td>
                            <span class="badge badge-{{ $directory->is_active ? 'success' : 'danger' }}">
                                {{ $directory->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                            </span>
                            </td>
                            <td>
                                @if(Gate::allows('equipment.index'))
                                <a href="{{ route('equipment-directories.items.index', $directory) }}"
                                   class="btn btn-sm btn-info" title="@lang('dashboard.manage_items')">
                                    <i class="fas fa-boxes"></i>
                                </a>
                                @endif
                                @if(Gate::allows('equipment.edit'))
                                <a href="{{ route('equipment-directories.edit', $directory) }}"
                                   class="btn btn-sm btn-primary" title="@lang('dashboard.edit')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                {{--                            <a href="{{ route('equipment-directories.export', $directory) }}"--}}
                                {{--                               class="btn btn-sm btn-success" title="@lang('dashboard.export_pdf')">--}}
                                {{--                                <i class="fas fa-file-pdf"></i>--}}
                                {{--                            </a>--}}
                                @if(Gate::allows('equipment.destroy'))
                                <form action="{{ route('equipment-directories.destroy', $directory) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            title="@lang('dashboard.delete')"
                                            onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
