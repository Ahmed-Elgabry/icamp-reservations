<!--begin::Table-->

<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">

    <!--begin::Table head-->

    <thead>

        <!--begin::Table row-->

        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">

            <th class="w-10px pe-2">

                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">

                    <input class="form-check-input" id="checkedAll" type="checkbox" data-kt-check="true"

                        data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />

                </div>

            </th>
            <th class=""><?php echo e(__('dashboard.process')); ?></th>

            <th class=""><?php echo e(__('dashboard.date')); ?></th>
            <?php if(isset($pageTitle)): ?>
                    <?php if($pageTitle === "transactions"): ?>
                        <th class=""><?php echo e(__('dashboard.bank_account')); ?></th>
                    <?php endif; ?>
            <?php else: ?>
                <th class=""><?php echo e(__('dashboard.transfer_from')); ?></th>
            <?php endif; ?>
    

            <th class=""><?php echo e(__('dashboard.receiver')); ?></th>

            <th class=""><?php echo e(__('dashboard.price')); ?></th>

            <th class=""><?php echo e(__('dashboard.source')); ?></th>

            <!-- <th class=""><?php echo e(__('dashboard.notes')); ?></th> -->

            <th class=""><?php echo e(__('dashboard.orders')); ?></th>

            <th class="text-end min-w-70px"><?php echo e(__('dashboard.actions')); ?></th>

        </tr>

        <!--end::Table row-->

    </thead>

    <!--end::Table head-->



    <!--begin::Table body-->

    <tbody class="fw-bold text-gray-600">

        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <!--begin::Table row-->

        <tr data-id="<?php echo e($transaction->id); ?>">

            <!--begin::Checkbox-->

            <td>

                <div class="form-check form-check-sm form-check-custom form-check-solid ">

                    <input class="form-check-input checkSingle" type="checkbox" value="1" id="<?php echo e($transaction->id); ?>" />

                </div>

            </td>

            <!--end::Checkbox-->

            <td>
                <?php if($transaction->type == 'transfer'): ?>
                    <span class="badge" style="background-color: orange; color: white;"><?php echo e(__("dashboard.transfer")); ?></span>
                   

                    <?php elseif($transaction->type == 'deposit'): ?>
                    <span class="badge badge-success"><?php echo e(__("dashboard.moneyDeposit")); ?></span>
                <?php elseif($transaction->type == 'debit'): ?>
                    <span class="badge badge-danger"><?php echo e(__("dashboard.debit")); ?></span>
                
                <?php endif; ?>
            </td>
            
            <!--begin::Date-->
            <td><a href="<?php echo e($transaction->editRoute); ?>"><?php echo e($transaction->created_at); ?></a></td>
            <?php if(isset($pageTitle)): ?>
                <?php if($pageTitle === "transactions"): ?>
                <td>
                    
                    <?php if($transaction->account): ?>
                    <span>
                        <a href="<?php echo e(route('bank-accounts.show', $transaction->account->id)); ?>" >
                            <?php echo e($transaction->account ? $transaction->account->name :""); ?>

                        </a>
                    </span>
                    <?php elseif($transaction->senderAccount->name): ?>
                    <span>
                        <a href="<?php echo e(route('bank-accounts.show', $transaction->senderAccount->id)); ?>" >
                            <?php echo e($transaction->senderAccount->name); ?>

                        </a>
                    </span>
                    <?php endif; ?>
                    
                    <?php if(!$transaction->order_id && $transaction->type != 'Payment'): ?>
                    <?php if($transaction->senderAccount): ?>
                        <span >

                            <?php echo e(__('dashboard.debit')); ?>.
                            
                        </span>
                    <?php endif; ?>
                    
                    <?php endif; ?>
                    
                    
                    
                    <?php if($transaction->account && $transaction->account->id && $transaction->order_id || $transaction->type == 'Payment'): ?>

                        <span>

                            <?php echo e(__('dashboard.Deposit')); ?>


                        </span>

                    <?php endif; ?>

                    
                    
                </td>
                <?php endif; ?>
            <?php endif; ?>

            <td>
                <?php if($transaction->senderAccount): ?>
                <span class="badge  badge-primary">
                            <a href="<?php echo e($transaction->senderAccount ? route('bank-accounts.show', $transaction->senderAccount->id) : '#'); ?>" class="text-light">
                                <?php echo e($transaction->senderAccount ? $transaction->senderAccount->name : ''); ?>

                            </a>
                </span>
                <?php endif; ?>
            <td>

                <?php if($transaction->receiver): ?>
                <span class="badge  badge-primary">
                    <a href="<?php echo e($transaction->receiver ? route('bank-accounts.show', $transaction->receiver->id) : '#'); ?>" class="text-light">
                        <?php echo e($transaction->receiver ? $transaction->receiver->name : ''); ?>

                    </a>
                </span>
                <?php endif; ?>



                <?php if($transaction->receiver): ?>

                    <?php if(isset($bank) && $transaction->receiver->id == $bank->id): ?>

                        <span class="badge  badge-success"> <?php echo e(__('dashboard.Deposit')); ?></span>

                    <?php endif; ?>

                <?php endif; ?>

            </td>

            <td data-kt-ecommerce-category-filter="category_name"><?php echo e($transaction->amount); ?> </td>

            <td>
                <?php if($transaction->type == 'transfer'): ?>

                        <?php echo e(__('dashboard.transfer')); ?>     
                <?php else: ?>     
                <?php echo e(__('dashboard.' . $transaction->source)); ?>

                <?php endif; ?>

            </td>

            <!-- <td><?php echo e($transaction->description); ?></td> -->

            <td><?php echo e($transaction->order_id ? $transaction->order_id : ''); ?></td>

            <td class="text-end">

                <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click"

                    data-kt-menu-placement="bottom-end">

                    <?php echo app('translator')->get('dashboard.actions'); ?>

                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->

                    <span class="svg-icon svg-icon-5 m-0">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">

                            <path

                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"

                                fill="currentColor" />

                        </svg>

                    </span>

                    <!--end::Svg Icon--></a>

                <!--begin::Menu-->

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"

                    data-kt-menu="true">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.edit')): ?>

                        <div class="menu-item px-3">

                            <a href="<?php echo e($transaction->editRoute); ?>" class="menu-link px-3"><?php echo app('translator')->get('dashboard.edit'); ?></a>

                        </div>

                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('payments.destroy')): ?>

                        <div class="menu-item px-3">

                            <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row"

                                data-url="<?php echo e($transaction->destroyRoute); ?>"

                                data-id="<?php echo e($transaction->id); ?>"><?php echo app('translator')->get('dashboard.delete'); ?></a>

                        </div>

                    <?php endif; ?>

                </div>

                <!--end::Menu-->

            </td>

            <!--end::Action=-->

        </tr>

        <!--end::Table row-->

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </tbody>

    <!--end::Table body-->

</table><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/banks/table.blade.php ENDPATH**/ ?>