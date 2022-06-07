
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transfer')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('Transfer')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Another User')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<script>
    const exchangeScheme = JSON.parse(`<?= json_encode($exhscheme) ?>`);
    const feeScheme      = JSON.parse(`<?= json_encode($fees) ?>`);
    const userAccs       = JSON.parse(`<?= json_encode($useraccs) ?>`);
</script>
<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('user.transaction.credit')); ?>" method="post">
        <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo csrf_field(); ?>
        <?php if(count($accounts) == 0): ?>
            <a href="#" class="noaccs-overlay"><span><?php echo e(__("Create Account")); ?></span></a>
            <div style="<?php echo e(count($accounts) == 0 ? 'filter: blur(2px)' : ''); ?>">
        <?php endif; ?>
            <input type="hidden" name="source" value="1">
            <h4 class="mg-t-10"><?php echo e(__('Account Details:')); ?></h4>
            <div class="row mg-t-10">
                <div class="col-lg-6 form-group">
                    <label><?php echo e(__('Debit Account:')); ?> <span class="red">*</span></label>
                    <div class="form-group">
                        <select name="accountnum" class="form-group text-start my-accs">
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $cur = \App\Models\Currency::where("id", $acc->currencyid)->first();
                            $val = $cur->symbol.number_format($acc->balance(), 2, ".", ",");
                            $val = strlen($cur->symbol) > 0 ? $val : $acc->balance().$cur->ISOcode;
                            ?>
                            <option data-currency="<?php echo e($cur->ISOcode); ?>" value="<?php echo e($acc->identifier); ?>">
                                <?php echo e($acc->identifier." - ".$val); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="transfer-arrow fa fa-arrow-right"></i>
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label><?php echo e(__('Credit Account:')); ?> <span class="red">*</span></label>
                    <div class="form-group">
                        <input type="text" name="payee_accountnum" class="manual-accnum fz-38 form-control" placeholder="<?php echo e(__('Enter recipient account ID')); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="alert alert-light fee-calc w-90 pd-l-0">
                        <label class="w-100 pd-l-0"><?php echo e(__('Post-Fee:')); ?> 0</label>
                        <label class="w-100 pd-l-0"><?php echo e(__('Fee:')); ?> 0</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label><?php echo e(__('Amount:')); ?> <span class="red">*</span></label>
                        <input id="tx-amount" type="text" name="amount" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><?php echo e(__('Transaction Key:')); ?><span class="mg-l-5 tag tag-default tag-pill" data-bs-container="body" data-bs-content="<?php echo e(__('If not empty, recipient will receive a notification about this transaction but it remains locked until recipient confirm this transaction key. You are able to cancel the transaction at anytime while its still not confirmed by the recipient. Note that you can only cancel the transfer after a timespan of 24 hours from transaction creation')); ?>" data-bs-placement="top" data-bs-popover-color="default" data-bs-toggle="popover" title="Details">?</span></label>
                        <input placeholder="<?php echo e(__('Recipient must confirm this key to credit his account')); ?>" type="text" name="trkey" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><?php echo e(__('Message Reference:')); ?><span class="mg-l-5 tag tag-default tag-pill" data-bs-container="body" data-bs-content="<?php echo e(__('Custom message reference, you can use this later to find desired transactions even more easily by searching transactions that contains a portion of-or-all this message')); ?>" data-bs-placement="top" data-bs-popover-color="default" data-bs-toggle="popover" title="Details">?</span></label>
                        <input placeholder="<?php echo e(__('Text reference to this transaction')); ?>" type="text" name="msgref" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center mt-3">
                    <div>
                        <button type="submit" class="btn ripple btn-main-primary d-block w-100"><?php echo e(__('Submit')); ?></button>
                    </div>
                </div>
            </div>
            <?php if(count($accounts) == 0): ?>
            </div>
            <?php endif; ?>
       </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transfer/anotheruser.blade.php ENDPATH**/ ?>