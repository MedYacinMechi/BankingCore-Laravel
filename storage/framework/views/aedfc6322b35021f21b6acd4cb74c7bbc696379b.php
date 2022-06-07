
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('New Account')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/accounts"><?php echo e(__('Accounts')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('New Account')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="section-body">
    <div class="card">
        <div class="card-body">
        <form action="<?php echo e(route('admin.accounts.addnewaccount')); ?>" class="acctype-form" method="POST">
            <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo csrf_field(); ?>
            <div class="row mg-t-10">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Client name (Company)")); ?></label>
                    <select name="user" class="form-group text-start newacctype-select2">
                      <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($user->id_represent()); ?>">
                          <?php echo e($user->name_represent()); ?>

                      </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Account Type")); ?></label>
                    <select name="acctype" class="form-group text-start newacctype-select2">
                      <?php $__currentLoopData = $acctypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acctype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($acctype->id_represent()); ?>">
                          <?php echo e($acctype->name_represent()); ?>

                      </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Initial Balance")); ?></label>
                    <input type="text" class="form-control form-control-sm" value="0" name="initialBalance" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Currency")); ?></label>
                    <select name="currency" class="form-group text-start newacctype-select2">
                      <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($currency->ISOcode); ?>">
                          <?php echo e($currency->ISOcode); ?>

                      </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="mg-t-50 mg-l-15 mg-r-15 btn ripple btn-primary btn-lg btn-block"><?php echo e(__('Create New Account')); ?></button>
            </div>
        </form>
        </div>
    </div>
    <br>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/accounts/new_account.blade.php ENDPATH**/ ?>