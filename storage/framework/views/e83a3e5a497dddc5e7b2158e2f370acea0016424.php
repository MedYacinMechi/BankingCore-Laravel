
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Accounts')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Accounts')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
<div class="col-12 row mb-4">
  <div class="col-12">
    <div class="add-new-btn">
      <a href="<?php echo e(route('admin.accounts.newaccount')); ?>" class="btn btn-primary ripple">
      <?php echo e(__('New Account')); ?>

      </a>
    </div>
  </div>
</div>
  <div class="col-12 row mg-r-0 mg-l-0">
    <div class="col-lg-6 col-sm-12 col-md-12 system-search">
      <form action="<?php echo e(route('admin.accounts.searchaccount')); ?>" class="w-100 row" method="GET">
        <?php if(isset($_GET['sortby'])): ?>
        <input type="hidden" name="sortby" value="<?php echo e($_GET['sortby']); ?>" /> 
        <?php endif; ?>
        <input value="<?php echo e(empty($_GET['keyword']) ? '' : $_GET['keyword']); ?>" placeholder="<?php echo e('Keyword'); ?>" class="col-4 form-control form-control-sm" type="text" name="keyword">
        <div class="col-4">
          <select class="col-4 form-group text-start search-type" name="column">
            <option value="0"><?php echo e(__('Currency')); ?></option>
            <option value="1"><?php echo e(__('User/Company Name')); ?></option>
            <option value="2"><?php echo e(__('Account Number')); ?></option>
            <option value="3"><?php echo e(__('Account Type')); ?></option>
          </select>
        </div>
        <button class="btn ripple btn-primary" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>
    <div class="col-lg-6 col-sm-12 col-md-12 ml-auto sorting-select2">
        <select class="col-4 form-group text-start" name="column">
          <option value="0"><?php echo e(__('Creation Date')); ?></option>
          <option value="1"><?php echo e(__('Client Name')); ?></option>
          <option value="2"><?php echo e(__('Account Number')); ?></option>
          <option value="3"><?php echo e(__('Account Type')); ?></option>
          <option value="4"><?php echo e(__('Currency')); ?></option>
        </select>
        <label class="float-right pd-r-10 lh-28"><?php echo e(__("Sort by")); ?></label>
    </div>
    <div class="table-responsive mg-t-10">
        <table class="main-default-table col-md-12 table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><?php echo e(__('Creation Date')); ?></th>
                    <th scope="col"><?php echo e(__('Client Name (Company)')); ?></th>
                    <th scope="col"><?php echo e(__('Account Number')); ?></th>
                    <th scope="col"><?php echo e(__('Currency')); ?></th>
                    <th scope="col"><?php echo e(__('Type')); ?></th>
                    <th scope="col"><?php echo e(__('Balance')); ?></th>
                </tr>
            </thead>
            <tbody class="bg-fafafa">
              <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($acc->created_at); ?></td>
                <td><?php echo e($acc->name_represent()); ?></td>
                <td><a href="<?php echo e(route('admin.accounts.view', $acc->id)); ?>"><?php echo e($acc->identifier); ?></a></td>
                <td><?php echo e($acc->currency->ISOcode); ?></td>
                <td><?php echo e($acc->acctype->name); ?></td>
                <td><?php echo e(number_format($acc->balance(), 2, '.', ',')); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="float-right">
            <?php echo e($accounts->withQueryString()->links('vendor.pagination.bootstrap-4')); ?>

        </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/accounts/index.blade.php ENDPATH**/ ?>