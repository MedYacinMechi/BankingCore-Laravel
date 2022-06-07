
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transfer')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('Transfer')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Multiple Users')); ?></li>
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
        <form action="<?php echo e(route('user.transaction.multipleTransfer')); ?>" method="post" enctype="multipart/form-data">
        <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo csrf_field(); ?>
        <?php if(count($accounts) == 0): ?>
            <a href="#" class="noaccs-overlay"><span><?php echo e(__("Create Account")); ?></span></a>
            <div style="<?php echo e(count($accounts) == 0 ? 'filter: blur(2px)' : ''); ?>">
        <?php endif; ?>
            <div class="row mg-t-10">
                <div class="col-lg-6 col-sm-12 col-md-12 form-group">
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
                <div class="col-lg-6 col-sm-12 col-md-12 form-group">
                    <label><?php echo e(__('CSV File')); ?> <span class="red">*</span></label>
                    <div class="form-group">
                        <div class="input-group file-browser">
                         <input type="text" class="form-control border-right-0 browse-file height-38" placeholder="<?php echo e(__('Choose file')); ?>" readonly>
                          <label class="input-group-btn">
                               <span class="btn ripple btn-primary">
                                 Browse <input accept=".csv, text/plain" name="csvdata" type="file" style="display: none;">
                               </span>
                          </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 col-md-12 float-right">
                <div class="alert alert-light fee-calc w-90 pd-l-0">
                    <label class="w-100 pd-l-0">
                        <?php echo e(__("Use the comma-separated values file format only. This file must have three columns: Payee Account Number, Transfer Amount and Transfer Description. Use one row per transfer. Your CSV file should not have column headers.")); ?>

                    </label>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-3 pd-0">
                <div>
                    <button type="submit" class="btn ripple btn-main-primary d-block w-100"><?php echo e(__('Submit')); ?></button>
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
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transfer/multipleusers.blade.php ENDPATH**/ ?>