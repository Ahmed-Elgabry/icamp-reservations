<?php $__env->startSection('pageTitle', __('dashboard.orders')); ?>


<?php $__env->startSection('content'); ?>
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">

                        <!-- زر فتح النافذة المنبثقة -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            <?php echo app('translator')->get('dashboard.price_fillter'); ?>
                        </button>

                        <?php echo $__env->make('dashboard.layouts.popUpSearch.orderSearchPopup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    </div>
                    <!--end::Search-->

                    <!--QR Scanner:Start-->
                    <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal"
                            data-bs-target="#qrScannerModal">
                        <i class="fas fa-qrcode me-2"></i><?php echo app('translator')->get('dashboard.scan_qr'); ?>
                    </button>
                    <!--QR Scanner:End-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Add customer-->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.create')): ?>
                        <a href="<?php echo e(route('orders.create')); ?>"
                            class="btn btn-primary"><?php echo app('translator')->get('dashboard.create_title', ['page_title' => __('dashboard.orders')]); ?></a>
                    <?php endif; ?>
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.destroy')): ?>
                        <button type="button" data-route="<?php echo e(route('orders.deleteAll')); ?>"
                            class="btn btn-danger delete_all_button">
                            <i class="feather icon-trash"></i><?php echo app('translator')->get('dashboard.delete_selected'); ?></button>
                    <?php endif; ?>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <?php echo e($orders->links()); ?>

                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class=""><?php echo app('translator')->get('dashboard.sequence'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.reservation_number'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.date'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.service'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.customer'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.Duration of hours'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.time_from'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.paied'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.status'); ?></th>
                            <?php if(request('status') == 'delayed'): ?>
                                <th><?php echo app('translator')->get('dashboard.old_date_vs_new_date'); ?></th>
                                <th><?php echo app('translator')->get('dashboard.delayed_reson'); ?></th>
                            <?php endif; ?>
                            <th class=""><?php echo app('translator')->get('dashboard.insurance_status'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.rate link'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.created_by'); ?></th>
                            <th class=""><?php echo app('translator')->get('dashboard.Agree_to_the_terms'); ?></th>
                            <th class="text-end min-w-70px"><?php echo app('translator')->get('dashboard.actions'); ?></th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->

                    <!--begin::Table body-->
                    <tbody class="fw-bold text-gray-600">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--begin::Table row-->
                            <tr data-id="<?php echo e($order->id); ?>">
                                <td><?php echo e($orders->firstItem() + $loop->index); ?></td>
                                <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.show')): ?>
                                        <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="badge bg-primary"><?php echo e($order->id); ?></a>
                                        <?php else: ?>
                                        <span><?php echo e($order->id); ?></span>
                                        <?php endif; ?>
                                        
                                    </td>
                                <!--begin::Order Date-->
                                <td>
                                    <span ><?php echo e($order->date); ?></span>
                                </td>
                                <!--end::Order Date-->

                                <!--begin::Services-->
                                <td>
                                    <span>
                                        <ul>
                                            <?php $__currentLoopData = $order->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($service->name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </span>
                                </td>
                                <!--end::Services-->

                                <!--begin::Customer Name-->
                                <td>
                                    <div class="d-flex">
                                        <div class="ms-5">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.show')): ?>
                                                <a href="<?php echo e(route('orders.show', $order->id)); ?>"
                                                    class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                                    <?php echo e($order->customer->name); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-gray-800 fs-5 fw-bolder mb-1">
                                                    <?php echo e($order->customer->name); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <!--end::Customer Name-->

                                <!--begin::Order Hours-->
                                <td><?php echo e($order->addHoursCount()); ?></td>
                                <!--end::Order Hours-->

                                <!--begin::Time From-->
                                <td><?php echo e($order->time_from ? \Carbon\Carbon::createFromFormat('H:i:s', $order->time_from)->format('h:i A') : ''); ?></td>
                                <!--end::Time From-->

                                <!--begin::Payments-->
                                <?php $totalPrice = ($order->price + $order->deposit + $order->insurance_amount + $order->addons->sum('price')) ?>
                                <td>
                                    <span class="text-success">
                                        <?php echo e(__('dashboard.paied')); ?>

                                        <?php echo e(number_format($order->verified_payments_sum)); ?>

                                    </span>
                                    <?php echo e(__('dashboard.out of')); ?>

                                    <?php echo e(number_format($totalPrice)); ?>


                                    <span class="text-danger">
                                        <?php echo e(__('dashboard.remaining')); ?>

                                        <?php if($order->insurance_status == 'returned'): ?>
                                            <?php echo e(number_format($order->insurance_amount)); ?>

                                        <?php else: ?>
                                            <?php echo e(number_format($totalPrice - $order->payments->sum('price'))); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                                <!--end::Payments-->

                                <!--begin::Order Status-->
                                <td><?php echo e(__('dashboard.' . $order->status)); ?></td>
                                <!--end::Order Status-->

                                <?php if(request('status') == 'delayed'): ?>
                                    <td class="text-nowrap">
                                        <?php echo e($order->date . ' / '  . $order->expired_price_offer); ?>

                                    </td>
                                    <td>
                                        <?php echo e(Str::limit($order->delayed_reson , 50)); ?>

                                    </td>
                                <?php endif; ?>

                                <!--begin::Order Status-->

                                <td>
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['badge text-white' , 'bg-success' => $order->insurance_status == 'returned' , 'bg-danger' => $order->insurance_status == null , 'bg-secondary' => $order->insurance_status == 'confiscated_full' , 'bg-primary' => $order->insurance_status == 'confiscated_partial' ]) ?>">
                                        <?php if($order->insurance_status): ?>
                                            <?php echo e(__('dashboard.' . $order->insurance_status)); ?>

                                        <?php else: ?>
                                            <?php echo e(__('dashboard.no_result')); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                                <!--begin::Order Status-->


                                <!--begin::Rate Link-->
                                <td>
                                    <a href="<?php echo e(route('surveys.public', $order->id)); ?>" target="_blank"
                                        class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1">
                                        Click Rate <i class="fa fa-link"></i>
                                    </a>
                                </td>
                                <!--end::Rate Link-->

                                <!--begin::Created By-->
                                <td>
                                    <?php if($order->created_by): ?>
                                        <?php echo e(__($order->user->name)); ?>

                                    <?php else: ?>
                                        <?php echo e(__('dashboard.no_creator')); ?>

                                    <?php endif; ?>
                                </td>
                                <!--end::Created By-->

                                <!--begin::Agree_to_the_terms-->
                                <td>
                                    <?php if($order->signature_path): ?>
                                        <?php echo e(__('dashboard.Done_agree_to_the_terms')); ?>

                                    <?php else: ?>
                                        <?php echo e(__('dashboard.no')); ?>

                                    <?php endif; ?>
                                </td>
                                <!--end::Agree_to_the_terms-->

                                <!--begin::Actions-->
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <?php echo e(__('dashboard.actions')); ?>

                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                    </a>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                        data-kt-menu="true">

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.show')): ?>
                                            <div class="menu-item px-3">
                                                <a href="<?php echo e(route('orders.show', $order->id)); ?>"
                                                    class="menu-link px-3"><?php echo e(__('dashboard.show')); ?></a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.edit')): ?>
                                            <div class="menu-item px-3">
                                                <a href="<?php echo e(route('orders.edit', $order->id)); ?>"
                                                    class="menu-link px-3"><?php echo e(__('dashboard.edit')); ?></a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bookings.destroy')): ?>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    data-kt-ecommerce-category-filter="delete_row"
                                                    data-url="<?php echo e(route('orders.destroy', $order->id)); ?>"
                                                    data-id="<?php echo e($order->id); ?>"><?php echo e(__('dashboard.delete')); ?></a>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </td>
                                <!--end::Actions-->
                            </tr>
                            <!--end::Table row-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->get('dashboard.scan_qr_code'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="qr-scanner-container" class="mb-3">
                        <video id="qr-video" width="100%" style="border-radius: 8px;"></video>
                    </div>
                    <div id="qr-result" class="alert alert-info d-none"></div>
                    <button id="start-scan-btn" class="btn btn-primary">
                        <i class="fas fa-camera me-2"></i><?php echo app('translator')->get('dashboard.start_scanning'); ?>
                    </button>
                    <button id="stop-scan-btn" class="btn btn-secondary d-none">
                        <i class="fas fa-stop me-2"></i><?php echo app('translator')->get('dashboard.stop_scanning'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <!-- Include the QR scanning library -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scannerModal = document.getElementById('qrScannerModal');
            const video = document.getElementById('qr-video');
            const startScanBtn = document.getElementById('start-scan-btn');
            const stopScanBtn = document.getElementById('stop-scan-btn');
            const resultContainer = document.getElementById('qr-result');
            let scanning = false;
            let stream = null;

            // Function to start scanning
            function startScanning() {
                resultContainer.classList.add('d-none');
                startScanBtn.classList.add('d-none');
                stopScanBtn.classList.remove('d-none');

                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                    .then(function(s) {
                        stream = s;
                        video.srcObject = stream;
                        video.setAttribute("playsinline", true);
                        video.play();
                        scanning = true;
                        requestAnimationFrame(tick);
                    })
                    .catch(function(err) {
                        console.error("Error accessing camera: ", err);
                        alert("<?php echo app('translator')->get('dashboard.camera_access_error'); ?>");
                        resetScanner();
                    });
            }

            // Function to stop scanning
            function stopScanning() {
                scanning = false;
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                resetScanner();
            }

            // Function to reset scanner UI
            function resetScanner() {
                startScanBtn.classList.remove('d-none');
                stopScanBtn.classList.add('d-none');
                video.srcObject = null;
            }

            // Function to process each video frame
            function tick() {
                if (!scanning) return;

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        // QR code detected!
                        stopScanning();
                        resultContainer.textContent = "<?php echo app('translator')->get('dashboard.qr_detected_redirecting'); ?>";
                        resultContainer.classList.remove('d-none');

                        // Check if the URL is a valid order edit URL
                        // if (code.data.includes('orders.edit')) {
                            setTimeout(() => {
                                window.location.href = code.data;
                            }, 1500);
                        
                        
                        
                        

                        
                        
                        
                        
                        
                    }
                }

                if (scanning) {
                    requestAnimationFrame(tick);
                }
            }

            // Event listeners
            startScanBtn.addEventListener('click', startScanning);
            stopScanBtn.addEventListener('click', stopScanning);

            // Reset scanner when modal is closed
            scannerModal.addEventListener('hidden.bs.modal', function() {
                stopScanning();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('css'); ?>
    <style>
        #qr-scanner-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        #qr-video {
            background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/orders/index.blade.php ENDPATH**/ ?>