
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Account Types')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/requests"><?php echo e(__('Settings')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Account Type')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="">
      <div class="">
      <div class="col-12">
      <div class="row mb-4">
          <div class="col-lg-6">
            <div class="add-new-btn">
              <a href="<?php echo e(route('admin.accounts.newtype')); ?>" class="btn btn-primary ripple">
              <?php echo e(__('New Account Type')); ?>

              </a>
            </div>
          </div>
      </div>
          <div class="table-responsive">
              <table class="main-default-table col-md-12 table table-bordered">
                  <thead>
                      <tr>
                          <th scope="col"><?php echo e(__('Name')); ?></th>
                          <th scope="col"><?php echo e(__('Currency')); ?></th>
                          <th scope="col"><?php echo e(__('Type')); ?></th>
                          <th scope="col"><?php echo e(__('Annual Interest Rate')); ?></th>
                          <th scope="col"><?php echo e(__('Compounding')); ?></th>
                          <th scope="col" class="no-sort"><?php echo e(__('Interval')); ?></th>
                          <th class="wd-5p row-data-view no-sort btn-col border-t-r-0" scope="col"></th>
                      </tr>
                  </thead>
                  <tbody class="bg-fafafa">
                    <?php $__currentLoopData = $acctypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $acctype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><?php echo e($acctype->name); ?></td>
                      <td><?php echo e($acctype->currency->ISOcode); ?></td>
                      <td><?php echo e(\App\Lib\Common::acctypedescriptor($acctype->type)); ?></td>
                      <td><?php echo e($acctype->annualrate); ?>%</td>
                      <td><?php echo e(\App\Lib\Common::periodescriptor($acctype->compounding)); ?></td>
                      <td><?php echo e(\App\Lib\Common::periodescriptor($acctype->interval)); ?></td>
                      <td class="btncol no-border">
                          <a class="btn btn-light" href="<?php echo e(route('admin.accounts.editacctype', $acctype->id)); ?>">
                          <span class="iconify" data-icon="bx:edit" data-inline="false"></span>
                          </a>
                      </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tbody>
              </table>
              <div class="float-right">
                  <?php echo e($acctypes->links('vendor.pagination.bootstrap-4')); ?>

              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/accounts/types.blade.php ENDPATH**/ ?>