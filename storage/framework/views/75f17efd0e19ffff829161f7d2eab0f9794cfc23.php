
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="pd-l-15 main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transaction Details')); ?></h2>
<ol class="pd-l-15 breadcrumb">
    <li class="pd-l-15 breadcrumb-item"><a href="#"><?php echo e(__('Transactions')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($transaction->id); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="pd-l-15 row">
  <div class="col-12">
    <div class="">
      <div class="">
          <?php echo $__env->make('components.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <div class="table-responsive mg-t-5">
              <table class="main-default-table col-md-12 table table-bordered">
                  <thead>
                    <tr>
                        <th scope="col"><?php echo e(__('Title')); ?></th>
                        <th scope="col"><?php echo e(__('Value')); ?></th>
                    </tr>
                  </thead>
                  <tbody class="bg-fafafa">
                    <!-- ID -->
                    <tr>
                      <td class="col-3"><?php echo e(__('ID')); ?></td>
                      <td><?php echo e($transaction->id); ?></td>
                    </tr>
                    <!-- Datetime -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Datetime')); ?></td>
                      <td><?php echo e($transaction->created_at); ?></td>
                    </tr>
                    <!-- Type -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Type')); ?></td>
                      <td>
                        <?php if($transaction->wireid != null): ?>
                        <span data-toggle="tooltip
                        " data-placement="top" title="<?php echo e(__('WIRE')); ?>" class="iconify valign-middle text-gray-300" data-icon="carbon:license-global" data-width="20" data-height="20"></span>
                        <span class="mg-l-5 badge bg-pill bg-primary-light" googl="true"><?php echo e($transaction->type == 0 ? __("DEBIT") : __("CREDIT")); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-primary-light" googl="true"><?php echo e($transaction->type == 0 ? __("DEBIT") : __("CREDIT")); ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <!-- Origin -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Origin')); ?></td>
                      <td>
                        <?php if($transaction->type == 0): ?>
                          <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                          <?php echo e($transaction->account->identifier); ?>

                          </a>
                        <?php else: ?>
                          <?php if($transaction->wireid != null): ?>
                            <?php echo e($transaction->wire->_56a_name." (".$transaction->wire->_56a_iban.")"); ?>

                          <?php else: ?>
                            <?php if($transaction->origintx->user_id != \Auth::user()->id): ?>
                            <?php echo e($transaction->origintx->user->firstname." ".$transaction->origintx->user->lastname); ?>

                            (<?php echo e($transaction->origintx->account->identifier); ?>)
                            <?php else: ?>
                            <a href="<?php echo e(route('user.account.view', $transaction->origintx->account->id)); ?>">
                              <?php echo e($transaction->origintx->account->identifier); ?>

                            </a>
                            <?php endif; ?>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <!-- Destination -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Destination')); ?></td>
                      <td>
                        <?php if($transaction->type == 0): ?>
                          <?php if($transaction->wireid != null): ?>
                          <?php echo e($transaction->wire->_59_name." (".$transaction->wire->_59_iban.")"); ?>

                          <?php else: ?>
                            <?php if($transaction->destinationtx()->user_id != \Auth::user()->id): ?>
                            <?php echo e($transaction->destinationtx()->user->firstname." ".$transaction->destinationtx()->user->lastname); ?>

                            (<?php echo e($transaction->destinationtx()->account->identifier); ?>)
                            <?php else: ?>
                            <a href="<?php echo e(route('user.account.view', $transaction->destinationtx()->account->id)); ?>">
                              <?php echo e($transaction->destinationtx()->account->identifier); ?>

                            </a>
                            <?php endif; ?>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php if($transaction->user_id != \Auth::user()->id): ?>
                          <?php echo e($transaction->user->firstname." ".$transaction->user->lastname); ?>

                          (<?php echo e($transaction->account->identifier); ?>)
                          <?php else: ?>
                          <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                          <?php echo e($transaction->account->identifier); ?>

                          </a>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <!-- Value -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Value')); ?></td>
                      <td>
                        <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                      </td>
                    </tr>
                    <!-- Fee -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Fee')); ?></td>
                      <td>
                        <?php echo e($transaction->currencycode().number_format($transaction->feevalue(), 2, '.', ',')); ?>

                      </td>
                    </tr>
                    <!-- Status -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Status')); ?></td>
                      <td data-status="<?php echo e($transaction->status == 0 ? 'PENDING' : ($transaction->status == 1 ? 'EXECUTED' : 'CANCELED')); ?>">
                        <?php if($transaction->status == 0): ?>
                        <i data-status="PENDING" class="si si-clock text-warning" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Pending')); ?>"></i>
                        <?php elseif($transaction->status == 1): ?>
                        <i data-status="EXECUTED" class="si si-check text-success" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Approved')); ?>"></i>
                        <?php elseif($transaction->status == 2): ?>
                        <i data-status="CANCELED" class="si si-close text-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Canceled')); ?>"></i>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transaction/view.blade.php ENDPATH**/ ?>