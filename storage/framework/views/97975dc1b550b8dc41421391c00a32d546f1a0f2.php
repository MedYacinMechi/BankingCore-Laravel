
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Confirm your password')); ?></h2>
<ol class="breadcrumb all-small-caps">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('Account')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Password')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <strong><?php echo e(__('woops!')); ?></strong> <?php echo e(__('There were some problems with your input.')); ?><br><br>
    <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>
<?php if(Session::has('success')): ?>
   <div class="alert alert-success"><?php echo e(Session::get('success')); ?></div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
<div class="alert alert-danger"><?php echo e(Session::get('error')); ?></div>
<?php endif; ?>
<div class="section-body">
    <div class="card">
        <div class="card-body">
        <form method="POST" action="<?php echo e(route('user.account.setting.confirmation')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><?php echo e(__('Enter Your Current Password')); ?></label>
                        <input type="password" placeholder="<?php echo e(__('Enter Your Current Password')); ?>" class="form-control" name="password">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="button-btn">
                        <button type="submit" class="basicbtn w-100"><?php echo e(__('Submit')); ?></button>
                    </div>
                </div>                                    
            </div>
        </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/accountsetting/password_confirmation.blade.php ENDPATH**/ ?>