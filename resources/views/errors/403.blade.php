@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.questions'))
@push('css')
<style>
    .card-body i {
        font-size: 120px !important;
    }
</style>
@endpush
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">

        <h2 class="fw-bolder mb-5 text-center">@lang('auth.not_authorized')</h2>

        <div class="card mb-5 mb-xl-10">
            <div class="card-body my-5 text-center">
                <i class="fa-solid fa-triangle-exclamation text-danger"></i>
            </div>
        </div>


    </div>
</div>
@endsection