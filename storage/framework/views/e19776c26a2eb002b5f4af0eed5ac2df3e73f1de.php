
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Transfer')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('Transfer')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Wire')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="card">
    <div class="card-body">
       <form action="<?php echo e(route('user.wire.create')); ?>" method="post">
            <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo csrf_field(); ?>
            <?php if(count($accounts) == 0): ?>
            <a href="#" class="noaccs-overlay"><span><?php echo e(__("Create Account")); ?></span></a>
            <div style="<?php echo e(count($accounts) == 0 ? 'filter: blur(2px)' : ''); ?>">
            <?php endif; ?>
            <h4 class="mg-t-10"><?php echo e(__("Transaction Details")); ?></h4>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label><?php echo e(__('Debit Account:')); ?></label>
                        <br>
                        <select name="accid" class="form-group text-start my-accs">
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $cur = \App\Models\Currency::where("id", $acc->currencyid)->first();
                            $val = $cur->symbol.$acc->balance();
                            $val = strlen($cur->symbol) > 0 ? $val : $acc->balance().$cur->ISOcode;
                            ?>
                            <option value="<?php echo e($acc->id); ?>">
                                <?php echo e($acc->identifier." - ".$val); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo e(__('Currency:')); ?></label>
                        <br>
                        <select name="currency" class="form-group text-start my-accs">
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cur->id); ?>">
                                <?php echo e($cur->ISOcode); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Amount:')); ?></label>
                        <input type="text" name="amount" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Transaction Key:')); ?></label>
                        <input type="text" name="trkey" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Supporting Document')); ?></label>
                        <div class="input-group file-browser">
                            <input type="text" class="form-control border-right-0 browse-file" placeholder="Image/PDF Fil" readonly>
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                    Browse <input name="doc" type="file" style="display: none;">
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <h4><?php echo e(__("SWIFT Field 56A (Intermediary)")); ?></h4>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('SWIFT / BIC:')); ?></label>
                        <input type="text" name="f56a_swift_bic" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Name:')); ?></label>
                        <input type="text" name="f56a_name" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Address:')); ?></label>
                        <input type="text" name="f56a_address" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('City:')); ?></label>
                        <input type="text" name="f56a_city" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Country:')); ?></label>
                        <input type="text" name="f56a_country" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('NCS Number:')); ?></label>
                        <input type="text" name="f56a_ncs" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('ABA / RTN:')); ?></label>
                        <input type="text" name="f56a_aba_rtn" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Account Number / IBAN:')); ?></label>
                        <input type="text" name="f56a_iban" class="form-control">
                    </div>
                </div>
            </div> -->
            <h4><?php echo e(__("SWIFT Field 57A (Beneficiary Bank)")); ?></h4>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('SWIFT / BIC:')); ?></label>
                        <input type="text" name="f57a_swift_bic" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Name:')); ?></label>
                        <input type="text" name="f57a_name" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Address:')); ?></label>
                        <input type="text" name="f57a_address" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('City:')); ?></label>
                        <input type="text" name="f57a_city" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Country:')); ?></label>
                        <input type="text" name="f57a_country" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('NCS Number:')); ?></label>
                        <input type="text" name="f57a_ncs" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('ABA / RTN:')); ?></label>
                        <input type="text" name="f57a_aba_rtn" class="form-control">
                    </div>
                </div>
            </div>
            <h4><?php echo e(__("SWIFT Field 59 (Beneficiary Client)")); ?></h4>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Name:')); ?></label>
                        <input type="text" name="f59_name" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Address:')); ?></label>
                        <input type="text" name="f59_address" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Account Number / IBAN:')); ?></label>
                        <input type="text" name="f59_iban" class="form-control">
                    </div>
                </div>
            </div>
            <h4><?php echo e(__("SWIFT Field 70 (Information)")); ?></h4>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label for=""><?php echo e(__('Message Reference:')); ?></label>
                        <input type="text" name="f70_msgref" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center mt-3">
                    <div class="button-btn">
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
<?php echo $__env->make('user.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transfer/wire.blade.php ENDPATH**/ ?>