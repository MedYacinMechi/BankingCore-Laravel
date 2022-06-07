
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('New Currency')); ?></h2>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo e(route('admin.currency.index')); ?>"><?php echo e(__('Currencies')); ?></a></li>
  <li class="breadcrumb-item active"><?php echo e(__('Create')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
    <div class="col-12">
        <div class="">
            <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <form method="POST" action="<?php echo e(route('admin.currency.store')); ?>" class="basicform">
              <?php echo csrf_field(); ?>
              <div class="">              
                  <div class="form-group">
                    <label><?php echo e(__('Full Name')); ?></label>
                    <div>
                      <input type="text" class="form-control" placeholder="<?php echo e(__('Full Name')); ?>" required name="title">
                    </div>
                  </div>                  
                  <div class="form-group">
                     <label><?php echo e(__('Select Currency')); ?></label>
                     <div>
                        <select name="currency_name" class="form-control ">
                            <?php $__currentLoopData = $currencylist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($currency['cc']); ?>"><?php echo e($currency['cc'].' -- '.$currency['name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                     </div>
                  </div> 
                  <div class="form-group">
                    <label><?php echo e(__('Status')); ?></label>
                    <div>
                      <select name="status" class="form-control">
                        <option value="1"><?php echo e(__('Active')); ?></option>
                        <option value="0"><?php echo e(__('In-Active')); ?></option>
                      </select>
                    </div>
                  </div>
                   <div class="form-group">
                    <label><?php echo e(__('Default')); ?></label>
                    <div>
                      <input checked type="checkbox" name="default">
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="w-100 btn btn-primary ripple"><?php echo e(__('Submit')); ?></button>
                  </div>
              </div>
          </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/currency/create.blade.php ENDPATH**/ ?>