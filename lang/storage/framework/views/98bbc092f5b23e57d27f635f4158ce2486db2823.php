
<?php $__env->startSection('pageTitle', __('dashboard.camp_reports')); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <input type="text" class="form-control form-control-solid w-250px ps-14"
                           placeholder="<?php echo app('translator')->get('dashboard.search_reports'); ?>" id="search-input"/>
                </div>
            </div>
            <div class="card-toolbar">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('camp-reports.export')): ?>
                    <a href="<?php echo e(route('camp-reports.export')); ?>" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-pdf"></i> <?php echo app('translator')->get('dashboard.export_pdf'); ?>
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('camp-reports.create')); ?>" class="btn btn-primary">
                    <?php echo app('translator')->get('dashboard.create_report'); ?>
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th><?php echo app('translator')->get('dashboard.report_date'); ?></th>
                    <th><?php echo app('translator')->get('dashboard.service'); ?></th>
                    <th><?php echo app('translator')->get('dashboard.camp_name'); ?></th>
                    <th><?php echo app('translator')->get('dashboard.report_created_by'); ?></th>
                    <th><?php echo app('translator')->get('dashboard.actions'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($report->report_date->format('Y-m-d')); ?></td>
                        <td><?php echo e($report->service->name ?? ''); ?></td>
                        <td><?php echo e($report->camp_name ?? ''); ?></td>
                        <td><?php echo e($report->creator->name); ?></td>
                        <td>
                            <a href="<?php echo e(route('camp-reports.show', $report)); ?>" class="btn btn-sm btn-info">
                                <?php echo app('translator')->get('dashboard.view'); ?>
                            </a>
                            <a href="<?php echo e(route('camp-reports.edit', $report)); ?>" class="btn btn-sm btn-primary">
                                <?php echo app('translator')->get('dashboard.edit'); ?>
                            </a>
                            <form action="<?php echo e(route('camp-reports.destroy', $report)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <?php echo app('translator')->get('dashboard.delete'); ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/camp_reports/index.blade.php ENDPATH**/ ?>