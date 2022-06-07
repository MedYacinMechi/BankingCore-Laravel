
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Fee Structure')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Fees')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="col-lg-6 col-sm-12 col-md-12 system-search">
      <form action="" class="w-100 row" method="GET">
        <div class="col-4 pd-0">
          <select class="col-4 form-group text-start currency-select-sm" name="column">
            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($currency->id); ?>"><?php echo e($currency->ISOcode); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      </form>
    </div>
    <div class="table-responsive mg-t-10">
        <table class="main-default-table col-md-12 table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><?php echo e(__('Currency')); ?></th>
                    <th scope="col"><?php echo e(__('Type')); ?></th>
                    <th scope="col"><?php echo e(__('Value')); ?></th>
                    <th scope="col"><?php echo e(__('Application')); ?></th>
                    <th><?php echo e(__('Action')); ?></th>
                </tr>
            </thead>
            <tbody class="bg-fafafa">
              <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($fee->currency->ISOcode); ?></td>
                <td><?php echo e($fee->fixed != null ? __("Fixed") : __("Percent")); ?></td>
                <td><?php echo e($fee->fixed != null ? $fee->fixed : $fee->percent); ?></td>
                <td><?php echo e($fee->application()); ?></td>
                <td>
                  <div>
                    <a href="<?php echo e(route('admin.fees.edit', $fee->id)); ?>" class="btn ripple btn-light btn-sm fz-15">
                      <i class="fe fe-edit"></i>
                    </a>
                    <a href="#" class="btn ripple btn-light btn-sm fz-15">
                      <i class="fe fe-minus-circle"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="float-right">
            <?php echo e($fees->withQueryString()->links('vendor.pagination.bootstrap-4')); ?>

        </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/fees/index.blade.php ENDPATH**/ ?>