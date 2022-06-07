
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Edit Account Type')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/requests"><?php echo e(__('Settings')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Account Type')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="section-body">
    <div class="card">
        <div class="card-body">
        <form action="<?php echo e(route('admin.accounts.updateacctype', $acctype->id)); ?>" class="acctype-form" method="POST">
            <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo csrf_field(); ?>
            <input type="hidden" id="acctypecurrency"       value="<?php echo e($acctype->currency->ISOcode); ?>">
            <input type="hidden" id="acctypeid"             value="<?php echo e($acctype->type); ?>" name="acctypeid">
            <input type="hidden" id="acctypecompounding"    value="<?php echo e($acctype->compounding); ?>">
            <input type="hidden" id="acctypemethod"         value="<?php echo e($acctype->method); ?>">
            <input type="hidden" id="acctypeinterval"       value="<?php echo e($acctype->interval); ?>">
            <input type="hidden" id="acctypeinterests"      value="<?php echo e($acctype->interests); ?>">
            <div class="row mg-t-10">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Account Type Name")); ?></label>
                    <input value="<?php echo e($acctype->name); ?>" type="text" class="form-control form-control-sm" name="acctypename" required>
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
                <div class="col-12 mg-t-15">
                  <h5>
                  <?php echo e(_("Monthly Maintenance Fee")); ?>

                  </h5>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <input value="<?php echo e($acctype->monthlyfee); ?>" type="text" class="form-control form-control-sm" name="monthlyfee" required>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="ckbox">
                      <input class="height-15" type="checkbox" name="monthlyfee30d" <?php echo e($acctype->monthlyfee30d == 1 ? "checked" : ""); ?>>
                      <span><?php echo e(__("Activate after 30 days of Account Creation")); ?></span>
                    </label>
                  </div>
                </div>
                <?php if($acctype->type == 1): ?>
                <div class="col-12 mg-t-25">
                  <h5 class="inline-block"><?php echo e(_("Minimum Balance")); ?></h5>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Limit Amount")); ?></label>
                    <input value="<?php echo e($acctype->type == 1 ? $acctype->limit : ''); ?>" type="text" class="form-control form-control-sm" name="MB_limit" <?php echo e($acctype->type == 1 ? "" : "readonly"); ?> required>
                    <label class="ckbox mg-t-5">
                      <input class="height-15" type="checkbox" name="MB_force" <?php echo e($acctype->type == 1 ? "" : "disabled"); ?> <?php echo e($acctype->mbforce == 1 ? "checked" : ""); ?>>
                      <span><?php echo e(__("Force")); ?></span>
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Fee Amount")); ?></label>
                    <input value="<?php echo e($acctype->type == 1 ? $acctype->fee : ''); ?>" type="text" class="form-control form-control-sm" name="MB_fee" <?php echo e($acctype->type == 1 ? "" : "readonly"); ?> required>
                  </div>
                </div>
                <?php endif; ?>
                <?php if($acctype->type == 2): ?>
                <div class="col-12 mg-t-25">
                  <h5 class="inline-block"><?php echo e(_("Line of Credit")); ?></h5>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Limit Amount")); ?></label>
                    <input value="<?php echo e($acctype->type == 2 ? $acctype->limit : ''); ?>" type="text" class="form-control form-control-sm" name="CL_limit" <?php echo e($acctype->type == 2 ? "" : "readonly"); ?> required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Annual Interest Rate %")); ?></label>
                    <input value="<?php echo e($acctype->type == 2 ? $acctype->annualrate : ''); ?>" type="text" class="form-control form-control-sm" name="CL_rate" <?php echo e($acctype->type == 2 ? "" : "readonly"); ?> required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group select2-sm">
                    <label><?php echo e(__("Compounding Period")); ?></label>
                    <select name="CL_comperiod" class="compounding CL_comperiod form-select form-select-sm text-start newacctype-select2" <?php echo e($acctype->type == 2 ? "" : "readonly"); ?>>
                      <option value="0"><?php echo e(__("Daily")); ?></option>
                      <option value="1"><?php echo e(__("Monthly")); ?></option>
                      <option value="2"><?php echo e(__("Quartely")); ?></option>
                      <option value="3"><?php echo e(__("Half-Yearly")); ?></option>
                      <option value="4"><?php echo e(__("Yearly")); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Method")); ?></label>
                    <select name="CL_method" class="acctypemethod CL_method form-group text-start newacctype-select2" <?php echo e($acctype->type == 2 ? "" : "readonly"); ?>>
                      <option value="0">
                          <?php echo e(__("Average Daily Balance")); ?>

                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Charge Period")); ?></label>
                    <select name="CL_chargeperiod" class="acctypeinterval CL_chargeperiod form-group text-start newacctype-select2" <?php echo e($acctype->type == 2 ? "" : "readonly"); ?>>
                      <option value="0"><?php echo e(__("Daily")); ?></option>
                      <option value="1"><?php echo e(__("Monthly")); ?></option>
                      <option value="2"><?php echo e(__("Quartely")); ?></option>
                      <option value="3"><?php echo e(__("Half-Yearly")); ?></option>
                      <option value="4"><?php echo e(__("Yearly")); ?></option>
                    </select>
                  </div>
                </div>
                <?php endif; ?>
                <?php if($acctype->type == 3): ?>
                <div class="col-12 mg-t-25">
                  <h5 class="inline-block"><?php echo e(_("Interest Generating")); ?></h5>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Annual Interest Rate %")); ?></label>
                    <input value="<?php echo e($acctype->type == 3 ? $acctype->annualrate : ''); ?>" type="text" class="form-control form-control-sm" name="IG_rate" <?php echo e($acctype->type == 3 ? "" : "readonly"); ?> required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group select2-sm">
                    <label><?php echo e(__("Compounding Period")); ?></label>
                    <select name="IG_comperiod" class="compounding IG_comperiod form-select form-select-sm text-start newacctype-select2" <?php echo e($acctype->type == 3 ? "" : "readonly"); ?>>
                      <option value="0">
                          <?php echo e(__("Daily")); ?>

                      </option>
                      <option value="1">
                          <?php echo e(__("Monthly")); ?>

                      </option>
                      <option value="2">
                          <?php echo e(__("Quartely")); ?>

                      </option>
                      <option value="3">
                          <?php echo e(__("Half-Yearly")); ?>

                      </option>
                      <option value="4">
                          <?php echo e(__("Yearly")); ?>

                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Method")); ?></label>
                    <select name="IG_method" class="acctypemethod IG_method form-group text-start newacctype-select2" <?php echo e($acctype->type == 3 ? "" : "readonly"); ?>>
                      <option value="0">
                          <?php echo e(__("Average Daily Balance")); ?>

                      </option>
                      <option value="1">
                          <?php echo e(__("Minimum Balance")); ?>

                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Payout Period")); ?></label>
                    <select name="IG_payoutperiod" class="acctypeinterval IG_payoutperiod form-group text-start newacctype-select2" <?php echo e($acctype->type == 3 ? "" : "readonly"); ?>>
                      <option value="0"><?php echo e(__("Daily")); ?></option>
                      <option value="1"><?php echo e(__("Monthly")); ?></option>
                      <option value="2"><?php echo e(__("Quartely")); ?></option>
                      <option value="3"><?php echo e(__("Half-Yearly")); ?></option>
                      <option value="4"><?php echo e(__("Yearly")); ?></option>
                    </select>
                  </div>
                </div>
                <?php endif; ?>
                <?php if($acctype->type == 4): ?>
                <div class="col-12 mg-t-25">
                  <h5 class="inline-block"><?php echo e(_("Term Deposit")); ?></h5>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Status")); ?></label>
                    <input value="<?php echo e($acctype->type == 4 ? $acctype->termstatus : ''); ?>" type="text" class="form-control form-control-sm" name="TD_status" <?php echo e($acctype->type == 4 ? "" : "readonly"); ?> required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Interests")); ?></label>
                    <select name="TD_interests" class="interests TD_interests form-group text-start newacctype-select2" <?php echo e($acctype->type == 4 ? "" : "readonly"); ?>>
                      <option value="0">
                          <?php echo e(__("Fixed")); ?>

                      </option>
                      <option value="1">
                          <?php echo e(__("Variable")); ?>

                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label><?php echo e(__("Term")); ?></label>
                    <input value="<?php echo e($acctype->type == 4 ? $acctype->term : ''); ?>" type="text" class="form-control form-control-sm" name="TD_term" <?php echo e($acctype->type == 4 ? "" : "readonly"); ?>>
                  </div>
                </div>
                <?php endif; ?>
                <button type="submit" class="mg-t-15 mg-l-15 mg-r-15 btn ripple btn-primary btn-lg btn-block"><?php echo e(__('Update Account Type')); ?></button>
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
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/accounts/edit_type.blade.php ENDPATH**/ ?>