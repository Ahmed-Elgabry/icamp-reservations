{{-- resources/views/surveys/thankyou.blade.php --}}
@extends('layouts.app')

@section('pageTitle', __('dashboard.thankyou'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="mdi mdi-check-circle text-success" style="font-size: 5rem;"></i>
            </div>
            <h2 class="mb-3">{{ __('dashboard.thankyou_title') }}</h2>
            <p class="lead mb-4">
                {{ __('dashboard.thankyou_message') }}
            </p>
            <a href="{{ url('https://www.funcamp.ae') }}" class="btn btn-success">
                {{ __('dashboard.back_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
