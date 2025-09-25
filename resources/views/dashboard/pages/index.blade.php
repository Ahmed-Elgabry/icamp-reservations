@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.pages'))
@section('content')
<div class="container-xxl">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="fw-bolder m-0">@lang('dashboard.pages')</h3>
        @can('pages.create')
        <a href="{{ route('pages.create') }}" class="btn btn-primary">@lang('dashboard.create_item')</a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="kt_ecommerce_category_table" class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th data-kt-ecommerce-category-filter="category_name">@lang('dashboard.name')</th>
                            <th>@lang('dashboard.url')</th>
                            <th>@lang('dashboard.is_available')</th>
                            <th>@lang('dashboard.is_authenticated')</th>
                            <th>@lang('dashboard.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($pages) && $pages->count())
                            @foreach($pages as $page)
                            <tr>
                                <td data-kt-ecommerce-category-filter="category_name">{{ $page->name }}</td>
                                <td><code>{{ $page->url }}</code></td>
                                <td>
                                    <span class="badge {{ $page->is_available ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $page->is_available ? __('dashboard.yes') : __('dashboard.no') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $page->is_authenticated ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $page->is_authenticated ? __('dashboard.yes') : __('dashboard.no') }}
                                    </span>
                                </td>
                                <td>
                                    @can('pages.edit')
                                    <a href="{{ route('pages.edit', $page) }}" class="btn btn-sm btn-light-primary">@lang('dashboard.edit')</a>
                                    @endcan
                                    @can('pages.destroy')
                                    <button type="button" class="btn btn-sm btn-light-danger" data-kt-ecommerce-category-filter="delete_row" data-id="{{ $page->id }}" data-url="{{ route('pages.destroy', $page) }}">@lang('dashboard.delete')</button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center">@lang('dashboard.no_data_found')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




