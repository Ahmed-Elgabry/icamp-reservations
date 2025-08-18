{{-- resources/views/surveys/thankyou.blade.php --}}
@extends('layouts.app')

@section('title', 'شكراً لك')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="mdi mdi-check-circle text-success" style="font-size: 5rem;"></i>
            </div>
            <h2 class="mb-3">شكراً لك!</h2>
            <p class="lead mb-4">لقد تم إرسال استجاباتك لاستبيان "{{ $survey->title }}" بنجاح.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>
</div>
@endsection
