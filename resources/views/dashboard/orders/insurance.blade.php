@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.insurance'))
@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

        @include('dashboard.orders.nav')

        <!--begin::Category-->
        <div class="card card-flush">
             <!-- customer information -->
                  <div class="pt-5 px-9 gap-2 gap-md-5">
                    <div class="row g-3 small">
                        <div class="col-md-1 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                            <div class="fw-bold">{{ $order->id }}</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                            <div class="fw-bold">{{ $order->customer->name }}</div>
                        </div>
                    </div>
                </div>
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                {{ __('dashboard.insurance') }}
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('orders.updateInsurance', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="insurance_status">{{ __('dashboard.insurance_status') }}</label>
                        <select name="insurance_status" id="insurance_status" class="form-control">
                            <option value="">{{ __('dashboard.choose') }} {{ __('dashboard.insurance_status') }}
                            </option>
                            <option value="returned" {{ old('insurance_status', $order->insurance_status) == 'returned' ? 'selected' : '' }}>{{ __('dashboard.returned') }}</option>
                            <option value="confiscated_full" {{ old('insurance_status', $order->insurance_status) == 'confiscated_full' ? 'selected' : '' }}>
                                {{ __('dashboard.confiscated_full') }}
                            </option>
                            <option value="confiscated_partial" {{ old('insurance_status', $order->insurance_status) == 'confiscated_partial' ? 'selected' : '' }}>
                                {{ __('dashboard.confiscated_partial') }}
                            </option>
                        </select>
                        @error('insurance_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mt-3" id="confiscation_description_group" style="display: none;">
                        <label for="confiscation_description">{{ __('dashboard.confiscation_description') }}</label>
                        <textarea name="confiscation_description" id="confiscation_description"
                            class="form-control">{{ old('confiscation_description', $order->confiscation_description) }}</textarea>
                    </div>

                    <div class="form-group mt-3" id="partial_confiscation_amount_group" style="display: none;">
                        <label for="partial_confiscation_amount">{{ __('dashboard.insurance_amount') }}</label>
                        <input type="number" name="partial_confiscation_amount" id="partial_confiscation_amount"
                            value="{{ old('partial_confiscation_amount', $order->insurance_amount) }}"
                            class="form-control" step="0.01">
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>
                    </div>
                </form>

                @if($order->insurance_status === 'confiscated_partial' || $order->insurance_status === 'confiscated_full' || $order->insurance_status === 'returned')
                    <div class="mt-4">
                        <h5>{{ __('dashboard.confiscation_details') }}</h5>

                        @if ($order->confiscation_description)
                            <p><strong>{{ __('dashboard.confiscation_description') }}:</strong>
                                {{ $order->confiscation_description }}</p>
                        @endif

                        @if($order->insurance_status === 'confiscated_full')
                            <p><strong>تمت مصاردة التامين كلياً</strong>
                                {{ $order->partial_confiscation_amount }}</p>
                        @endif

                        @if($order->insurance_status === 'confiscated_partial')
                            <p><strong>تمت مصاردة التامين جزئياَ والمتبقي من مبلغ التأمين : </strong>
                                {{$order->insurance_amount - $order->partial_confiscation_amount }} AED</p>
                        @endif

                        @if($order->insurance_status === 'returned')
                            <p><strong> تم رد مبلغ التامين</strong>
                        @endif
                    </div>
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        function renderStatus() {
            var insuranceStatus = $("#insurance_status").val();

            // عرض/إخفاء وصف المصادرة بناءً على الحالة المختارة
            if (insuranceStatus === 'confiscated_full' || insuranceStatus === 'confiscated_partial') {
                $('#confiscation_description_group').show();
            } else {
                $('#confiscation_description_group').hide();
            }

            // عرض/إخفاء مبلغ المصادرة الجزئية إذا كانت الحالة هي "مصادرة جزئية"
            if (insuranceStatus === 'confiscated_partial') {
                $('#partial_confiscation_amount_group').show();
            } else {
                $('#partial_confiscation_amount_group').hide();
            }
        }

        // استدعاء الدالة عند تحميل الصفحة لتعيين الحقول المناسبة
        renderStatus();

        // استدعاء الدالة عند تغيير حالة التأمين
        $('#insurance_status').change(function () {
            renderStatus();
        });
    });
</script>

@endsection