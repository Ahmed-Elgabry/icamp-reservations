@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="">
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
            <a href="#" class="mb-12">
                <img alt="Logo" src="{{asset('images/logo.png')}}" class="h-200px" />
            </a>
            <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                <form class="form w-100" novalidate="novalidate"  method="POST"  action="{{ route('admin-login')}}">
                    @csrf
                     
                  
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bolder text-dark">البريد الالكتروني</label>
                        <input class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off" />
                    </div>
                   

                    @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                    <div class="fv-row mb-10">
                        <div class="d-flex flex-stack mb-2">
                            <label class="form-label fw-bolder text-dark fs-6 mb-0">كلمة المرور</label>
                        </div>
                        <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
                    </div>
                   
                    <div class="text-center">
                        <button type="submit" id="" class="btn btn-lg btn-primary w-100 mb-5">
                            <span class="indicator-label">تسجيل الدخول <i class="fa fa-door-open"></i></span>
                            <span class="indicator-progress">جاري التسجيل...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Content-->
        <!--begin::Footer-->
        
    </div>
</div>
	
@endsection
@section('scripts')

@endsection