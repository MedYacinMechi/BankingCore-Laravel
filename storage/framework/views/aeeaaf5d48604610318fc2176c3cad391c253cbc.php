
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transactions')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('All Transactions')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="">
      <div class="">
          <div class="row">
            <div class="col-sm">
              <form action="#">
                <div class="row admin-search-tx">
                  <div class="col-lg-5 form-group">
                      <label><?php echo e(__('Start Date')); ?></label>
                      <input type="date" class="form-control" name="start_date" required>
                  </div>
                  <div class="col-lg-5 form-group">
                      <label><?php echo e(__('End Date')); ?></label>
                      <input type="date" class="form-control" name="end_date" required>
                  </div>
                  <div class="sr-bydate-btn col-lg-2 mt-24">
                    <button class="btn ripple btn-outline-light btn-rounded" type="submit"><i class="fas fa-search"></i></button>
                    <button class="w-100 d-none btn ripple btn-primary btn-rounded" type="submit"><i class="fas fa-search"></i>    Search</button>
                  </div>  
                </div>
              </form>
            </div>
            <div class="col-sm trx-search-by-field">
               <form action="#">
                  <div class="row admin-search-tx">
                    <div class="col-lg-5 form-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Keyword" required>
                    </div>
                    <div class="col-lg-5 form-group trx-search-field-name">
                      <select class="form-control" name="type">
                        <option value="trxid"><?php echo e(__('Transaction No')); ?></option>
                        <option value="name"><?php echo e(__('Name')); ?></option>
                      </select>
                    </div>
                    <div class="sr-byfield-btn col-lg-2">
                      <button class="btn ripple btn-outline-light btn-rounded" type="submit"><i class="fas fa-search"></i></button>
                      <button class="w-100 d-none btn ripple btn-primary btn-rounded" type="submit"><i class="fas fa-search"></i>    Search</button>
                    </div>  
                  </div>
                </form>
            </div>
          </div>
          <?php if(Session::has('message')): ?>
            <div class="alert alert-danger"><?php echo e(Session::get('message')); ?></div>
          <?php endif; ?>
          <div class="mg-t-15 table-responsive">
              <table class="main-default-table col-md-12 table table-bordered">
                <thead>
                  <tr>
                      <th scope="col"><?php echo e(__('User')); ?></th>
                      <th scope="col"><?php echo e(__('Value')); ?></th>
                      <th scope="col"><?php echo e(__('Fee')); ?></th>
                      <th scope="col"><?php echo e(__('Currency')); ?></th>
                      <th scope="col"><?php echo e(__('Type')); ?></th>
                      <th scope="col"><?php echo e(__('Status')); ?></th>
                      <th scope="col"><?php echo e(__('DateTime')); ?></th>
                      <th class="wd-5p no-sort btn-col border-t-r-0" scope="col"></th>
                  </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($transaction->originid != null): ?>
                <tr>
                  <td>
                    <a href="<?php echo e(route('admin.users.show', $transaction->origintx->user->id)); ?>">
                      <?php echo e($transaction->origintx->user->firstname." ".$transaction->origintx->user->lastname); ?>

                    </a>
                    <?php if($transaction->user_id != $transaction->origintx->user_id): ?>
                    <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                    <a href="<?php echo e(route('admin.users.show', $transaction->user->id)); ?>">
                      <?php echo e($transaction->user->firstname." ".$transaction->user->lastname); ?>

                    </a>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if($transaction->origintx->currency()->ISOcode == $transaction->currency()->ISOcode): ?>
                    <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                    <?php else: ?>
                    <?php echo e($transaction->origintx->currencycode().number_format($transaction->origintx->value, 2, '.', ',')); ?>

                    <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                    <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                    <?php endif; ?>
                  </td>
                  <td><?php echo e($transaction->origintx->currencycode().number_format($transaction->origintx->feevalue(), 2, '.', ',')); ?></td>
                  <td>
                    <?php if($transaction->currency()->ISOcode == $transaction->origintx->currency()->ISOcode): ?>
                    <?php echo e($transaction->currency()->ISOcode); ?>

                    <?php else: ?>
                    <?php echo e($transaction->origintx->currency()->ISOcode.$transaction->currency()->ISOcode); ?>

                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="tag-pill mt-1 mb-1 tag tag-dark">
                      <?php if($transaction->user_id == $transaction->origintx->user_id): ?>
                      <?php echo e(__("SAMEUSER")); ?>

                      <?php else: ?>
                      <?php echo e(__("CROSSUSER")); ?>

                      <?php endif; ?>
                    </span>
                  </td>
                  <td>
                    <span class="all-small-caps text-<?php echo e($transaction->status == 1 ? 'success' : ($transaction->status == 2 ? 'danger' : 'warning')); ?>">
                      <span class="dot-label bg-<?php echo e($transaction->status == 1 ? 'success' : ($transaction->status == 2 ? 'danger' : 'warning')); ?> me-1"></span>
                      <?php echo e($transaction->status == 1 ? 'EXECUTED' : ($transaction->status == 2 ? 'CANCELED' : 'PENDING')); ?>

                    </span>
                  </td>
                  <td><?php echo e($transaction->created_at); ?></td>
                  <td class="btncol no-border">
                    <a class="btn btn-light" href="<?php echo e(route('admin.transactions.view', $transaction->origintx->id)); ?>">
                      <span class="iconify" data-icon="carbon:view-filled" data-inline="false"></span>
                    </a>
                  </td>
                </tr>
                <?php endif; ?>
                <?php if($transaction->wireid != null): ?>
                <tr>
                  <td>
                    <?php if($transaction->type == 0): ?>
                    <a href="<?php echo e(route('admin.users.show', $transaction->user->id)); ?>">
                      <?php echo e($transaction->user->firstname." ".$transaction->user->lastname); ?>

                    </a>
                    <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                    <?php echo e($transaction->wire->_57a_name.": ".$transaction->wire->_59_name); ?>

                    <?php else: ?>
                    <a href="<?php echo e(route('admin.users.show', $transaction->user->id)); ?>">
                      <?php echo e($transaction->wire->_56a_name.": ".$transaction->wire->_59_name); ?>

                    </a>
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?></td>
                  <td><?php echo e($transaction->currencycode().number_format($transaction->feevalue(), 2, '.', ',')); ?></td>
                  <td><?php echo e($transaction->currency()->ISOcode); ?></td>
                  <td>
                    <span class="tag-pill mt-1 mb-1 tag tag-dark">
                      <?php echo e(__("WIRE:").($transaction->type == 0 ? __("DEBIT") : __("CREDIT"))); ?>

                    </span>
                  </td>
                  <td>
                    <span class="all-small-caps text-<?php echo e($transaction->status == 1 ? 'success' : ($transaction->status == 2 ? 'danger' : 'warning')); ?>">
                      <span class="dot-label bg-<?php echo e($transaction->status == 1 ? 'success' : ($transaction->status == 2 ? 'danger' : 'warning')); ?> me-1"></span>
                      <?php echo e($transaction->status == 1 ? 'EXECUTED' : ($transaction->status == 2 ? 'CANCELED' : 'PENDING')); ?>

                    </span>
                  </td>
                  <td><?php echo e($transaction->created_at); ?></td>
                  <td class="btncol no-border">
                    <a class="btn btn-light" href="<?php echo e(route('admin.transactions.view', $transaction->id)); ?>">
                      <span class="iconify" data-icon="carbon:view-filled" data-inline="false"></span>
                    </a>
                  </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
              </table>
            <?php echo e($transactions->links('vendor.pagination.bootstrap-4')); ?>

          </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>