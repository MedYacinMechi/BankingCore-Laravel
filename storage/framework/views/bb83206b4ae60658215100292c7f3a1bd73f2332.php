
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Users')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>"><?php echo e(__('Users')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Edit')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4><?php echo e(__('Edit User')); ?></h4>
            </div>
            <?php if($errors->any()): ?>
              <div class="alert alert-danger">
                  <strong><?php echo e(__('Whoops!')); ?></strong> <?php echo e(__('There were some problems with your input.')); ?><br><br>
                  <ul>
                      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li><?php echo e($error); ?></li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
              </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('admin.users.update', $user_edit->id)); ?>" class="basicform">
              <?php echo csrf_field(); ?>
              <?php echo method_field('put'); ?>
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label><?php echo e(__('First Name')); ?></label>
                        <input type="text" class="form-control" placeholder="First Name" required name="firstname" value="<?php echo e($user_edit->firstname); ?>">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label><?php echo e(__('Email')); ?></label>
                        <input type="email" class="form-control" placeholder="Email Address" required name="email" value="<?php echo e($user_edit->email); ?>">
                      </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label><?php echo e(__('last Name')); ?></label>
                        <input type="text" class="form-control" placeholder="Last Name" required name="lastname" value="<?php echo e($user_edit->lastname); ?>">
                      </div>
                    </div>
                    <?php if($user_edit->type == 1): ?>
                   <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label><?php echo e(__('Company Name')); ?></label>
                        <input type="text" class="form-control" placeholder="company name" required name="companyname" value="<?php echo e($user_edit->companyname); ?>">
                      </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label><?php echo e(__('Phone')); ?></label>
                          <input type="text" class="form-control" placeholder="Phone" required name="phone_number" value="<?php echo e($user_edit->phone); ?>">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label><?php echo e(__('Password')); ?></label>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <div class="custom-file mb-3">
                    <label><?php echo e(__('Status')); ?></label>
                    <select name="status" class="form-control">
                      <option>-- <?php echo e(__('Select Status')); ?> --</option>
                      <option value="1" <?php echo e(($user_edit->status == 1) ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                      <option value="0" <?php echo e(($user_edit->status == 0) ? 'selected' : ''); ?>><?php echo e(__('Inactive')); ?></option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label"><?php echo e(__('Email verify status')); ?></div>
                      <label class="custom-switch mt-2">
                        <input <?php echo e(($user_edit->email_verified_at) ? 'checked' : ''); ?> type="checkbox" name="email_verified_at" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?php echo e(__('verify')); ?></span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label"><?php echo e(__('Phone verify status')); ?></div>
                      <label class="custom-switch mt-2">
                        <input <?php echo e(($user_edit->phone_verified_at) ? 'checked' : ''); ?> type="checkbox" name="phone_verified_at" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?php echo e(__('verify')); ?></span>
                      </label>
                    </div>
                  </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label two_step_auth"></div>
                      <!--<label class="custom-switch mt-2">
                        <input <?php echo e(($user_edit->two_step_auth) ? 'checked' : ''); ?> type="checkbox" name="two_step_auth" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?php echo e(__('Enable')); ?></span>
                      </label>-->
                    </div>

                  </div>
              </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn"><?php echo e(__('Update')); ?></button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/user/edit.blade.php ENDPATH**/ ?>