@extends('dashboard.layouts.app')
@section('pageTitle', $equipmentDirectory->name . ' - ' . __('dashboard.items'))

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3>{{ $equipmentDirectory->name }}</h3>
            <div class="d-flex align-items-center position-relative my-1">
                <input type="text" class="form-control form-control-solid w-250px ps-14"
                    placeholder="@lang('dashboard.search_items')" id="search-input" />
            </div>
        </div>
        <div class="card-toolbar">
            @can('equipment.export')
            <a href="{{ route('equipment-directories.items.export', $equipmentDirectory) }}"
                class="btn btn-success me-2" title="@lang('dashboard.export_pdf')">
                <i class="fas fa-file-pdf"></i> @lang('dashboard.export_pdf')
            </a>
            @endcan
            @if(Gate::allows('equipment.create'))
            <a href="{{ route('equipment-directories.items.create', $equipmentDirectory) }}" class="btn btn-primary">
                @lang('dashboard.add_item')
            </a>
            @endif
            <a href="{{ route('equipment-directories.index') }}" class="btn btn-secondary ms-2">
                @lang('dashboard.back_to_directories')
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th class="min-w-100px">@lang('dashboard.item_type')</th>
                        <th class="min-w-100px">@lang('dashboard.item_name')</th>
                        <th class="min-w-100px">@lang('dashboard.location')</th>
                        <th class="min-w-100px">@lang('dashboard.quantity')</th>
                        <th class="min-w-100px">@lang('dashboard.notes')</th>
                        <th class="min-w-100px">@lang('dashboard.creator') / @lang('dashboard.date')</th>
                        <th class="min-w-100px">@lang('dashboard.media')</th>
                        <th class="min-w-100px">@lang('dashboard.directory_status')</th>
                        <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->location }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ Str::limit($item->notes, 30) }}</td>
                        <td>
                            {{ $item->creator->name }}<br>
                            {{ $item->created_at->format('Y-m-d h:i A') }}
                        </td>
                        <td>
                            @if($item->media->count() > 0)
                            <button class="btn btn-sm btn-icon btn-light-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#mediaModal{{ $item->id }}">
                                <i class="fas fa-images"></i> ({{ $item->media->count() }})
                            </button>
                            @else
                            <span class="text-muted">@lang('dashboard.no_media')</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}">
                                {{ $item->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                            </span>
                        </td>
                        <td>
                            @if(Gate::allows('equipment.edit'))
                            <a href="{{ route('equipment-directories.items.edit', [$equipmentDirectory,$item]) }}"
                                class="btn btn-sm btn-primary" title="@lang('dashboard.edit')">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(Gate::allows('equipment.destroy'))
                            <form action="{{ route('equipment-directories.items.destroy',  [$equipmentDirectory,$item]) }}"
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

                    <!-- Media Modal -->
                    <div class="modal fade" id="mediaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $item->name }} - @lang('dashboard.media')</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        @foreach($item->media as $media)
                                        <div class="col-md-4 mb-3">
                                            @if($media->file_type === 'image')
                                            <img src="{{ $media->file_url }}" class="img-fluid rounded" alt="">
                                            @else
                                            <video controls class="w-100">
                                                <source src="{{ $media->file_url }}" type="video/mp4">
                                            </video>
                                            @endif
                                            <form action="{{ route('equipment-directories.media.destroy', $media) }}"
                                                method="POST" class="mt-2 text-center">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                                    <i class="fas fa-trash"></i> @lang('dashboard.delete')
                                                </button>
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
