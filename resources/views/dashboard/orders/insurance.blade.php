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
                <div class="d-flex flex-column align-items-center gap-1">
                    @can('bookings.insurance.approve')
                    <a href="{{ route('order.verified', ['Id' => $order->id, 'type' => 'insurance']) }}"
                        class="btn btn-sm {{ $order->is_insurance_verified ? 'btn btn-sm btn-danger' : 'btn btn-sm btn-success' }}">
                        {{ $order->is_insurance_verified ? __('dashboard.mark_unverify') : __('dashboard.mark_verify') }}
                    </a>
                    @endcan
                </div>

            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('bookings.updateInsurance', $order->id) }}" method="POST">
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
                        <label for="partial_confiscation_amount">{{ __('dashboard.confiscated_amount') }}</label>
                        <input type="number" name="partial_confiscation_amount" id="partial_confiscation_amount"
                            value="{{ $order->insurance_status === 'confiscated_partial' ? $order->partial_confiscation_amount : '' }}"
                            class="form-control" step="0.01" min="0" {{ $order->insurance_status === 'confiscated_partial' ? 'required' : '' }}>
                    </div>
                    @if($order->insurance_status === 'confiscated_partial' || $order->insurance_status === 'confiscated_full' || $order->insurance_status === 'returned')
                    <div class="mt-6">
                        <div class="alert alert-primary d-flex align-items-center p-5">
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-primary">{{ __('dashboard.confiscation_details') }}</h4>

                                @if ($order->confiscation_description)
                                <div class="mb-2">
                                    <span class="fw-semibold text-gray-800">{{ __('dashboard.confiscation_description') }}:</span>
                                    <span class="text-gray-700">{{ $order->confiscation_description }}</span>
                                </div>
                                @endif

                                @if($order->insurance_status === 'confiscated_full')
                                <div class="mb-2">
                                    <span class="fw-bold text-danger">{{ __('dashboard.insurance_confiscated_full') }}</span>
                                    @if($order->partial_confiscation_amount)
                                    <span class="text-gray-700">- {{ number_format($order->insuranceFromTransaction(), 2) }} AED</span>
                                    @endif
                                </div>
                                @endif

                                @if($order->insurance_status === 'confiscated_partial')
                                <div class="mb-2">
                                    <span class="fw-bold text-warning">{{ __('dashboard.insurance_confiscated_partial') }}</span>
                                    <br>
                                    @if($order->insuranceFromTransaction())
                                    <span class="text-gray-700">{{ __('dashboard.remaining_insurance') }}: </span>
                                    <span class="fw-bold text-success">{{ number_format($order->verifiedInsuranceAmount() - ($order->partial_confiscation_amount ??$order->insuranceFromTransaction), 2) }} AED</span>
                                    @endif
                                </div>
                                @endif

                                @if($order->insurance_status === 'returned')
                                <div class="mb-2">
                                    <span class="fw-bold text-success">{{ __('dashboard.insurance_returned') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @can('bookings.updateInsurance')
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.save_changes') }}</button>

                    </div>
                    @endcan
                </form>
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
    $(document).ready(function() {
        function renderStatus() {
            var insuranceStatus = $("#insurance_status").val();
            // Show/hide confiscation description based on selected status
            if (insuranceStatus === 'confiscated_full' || insuranceStatus === 'confiscated_partial') {
                $('#confiscation_description_group').show();
            } else {
                $('#confiscation_description_group').hide();
            }

            // Show/hide partial confiscation amount field
            var partialAmountInput = $('#partial_confiscation_amount');
            if (insuranceStatus === 'confiscated_partial') {
                $('#partial_confiscation_amount_group').show();
                partialAmountInput.prop('required', true);
            } else {
                $('#partial_confiscation_amount_group').hide();
                partialAmountInput.prop('required', false);
                partialAmountInput.val(''); // Clear the value when hidden
            }
        }

        // Call the function when the page loads
        renderStatus();

        // Call the function when insurance status changes
        $('#insurance_status').change(function() {
            renderStatus();
        });


    });
</script>

@endsection