
<?php $__env->startPush("css"); ?>
<link href="/frontend/assets/js/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transaction History')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item active"><?php echo e(__('Account')); ?></li>
    <li class="breadcrumb-item active"><?php echo e(__('Transactions')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<?php if(Session::has('message')): ?>
<p class="alert alert-danger">
    <?php echo e(Session::get('message')); ?>

</p>
<?php endif; ?>
<div class="table-responsive">
    <table class="main-default-table col-md-12 table table-bordered">
        <?php echo $__env->make("user.transaction.transactions", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </table>
    <div class="float-right">
    <?php echo e($transactions->links('vendor.pagination.bootstrap-4')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontentop'); ?>
<div class="d-flex">
    <div class="justify-content-center">
        <a href="<?php echo e(route('user.transaction.pdf')); ?>" class="btn btn-primary btn-icon-text my-2 me-2">
            <i class="fe fe-download me-2">
            </i> <?php echo e(__('PDF download')); ?>

        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/datatable/js/jquery.dataTables.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transaction/history.blade.php ENDPATH**/ ?>