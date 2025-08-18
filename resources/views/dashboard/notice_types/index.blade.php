@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.notice_types'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2>@lang('dashboard.notice_types')</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('notice-types.create') }}" class="btn btn-primary">
                    @lang('dashboard.add_notice_type')
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                <tr>
                    <th>@lang('dashboard.name')</th>
                    <th>@lang('dashboard.status')</th>
                    <th>@lang('dashboard.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($noticeTypes as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>
                                <span class="badge badge-{{ $type->is_active ? 'success' : 'danger' }}">
                                    {{ $type->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                                </span>
                        </td>
                        <td>
                            <a href="{{ route('notice-types.edit', $type->id) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('notice-types.destroy', $type->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('dashboard.are_you_sure')')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $noticeTypes->links() }}
        </div>
    </div>
@endsection
