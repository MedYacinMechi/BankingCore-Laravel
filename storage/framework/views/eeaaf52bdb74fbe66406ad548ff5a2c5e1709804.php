
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('All Users')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Users')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="">
  <div class="">
      <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div>
        <div class="col-lg-6 col-sm-12 col-md-12 system-search">
          <form action="<?php echo e(route('admin.user.search')); ?>" class="w-100 row" method="GET">
            <input value="<?php echo e(empty($_GET['keyword']) ? '' : $_GET['keyword']); ?>" placeholder="<?php echo e('Keyword'); ?>" class="col-4 form-control form-control-sm" type="text" name="keyword">
            <div class="col-4">
              <select class="col-4 form-group text-start search-type" name="column">
                <option value="0"><?php echo e(__('Name')); ?></option>
                <option value="1"><?php echo e(__('Username')); ?></option>
                <option value="2"><?php echo e(__('Email')); ?></option>
                <option value="3"><?php echo e(__('Phone')); ?></option>
              </select>
            </div>
            <button class="btn ripple btn-primary" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
      <div class="table-responsive mg-t-10">
          <table class="main-default-table col-md-12 table table-bordered" id="table-2">
            <thead>
              <tr>
                <th><?php echo e(__('Company / User')); ?></th>
                <th><?php echo e(__('Username')); ?></th>
                <th><?php echo e(__('Email')); ?></th>
                <th><?php echo e(__('Phone')); ?></th>
                <th><?php echo e(__('Current Balance')); ?></th>
                <th><?php echo e(__('Status')); ?></th>
                <th><?php echo e(__('KYC')); ?></th>
                <th><?php echo e(__('Action')); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                  <td><a href="<?php echo e(route('admin.users.show', $user->id)); ?>"><?php echo e($user->name_represent()); ?></a></td>
                  <td><?php echo e($user->username.$user->id); ?></td>
                  <td><?php echo e($user->email); ?></td>
                  <td>
                    <?php if($user->type == 1): ?> 
                      <?php   $value = empty($user->officephone) ? null : $user->officephone ?>
                      <?php if(empty($value)): ?>
                        <?php $value = empty($user->mobilephone) ? null : $user->mobilephone ?>
                        <?php if(empty($value)): ?>
                        <?php $value = empty($user->homephone) ? null : $user->homephone ?>
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php echo e(empty($value) ? "NaN" : $value); ?>

                    <?php else: ?>
                      <?php   $value = empty($user->mobilephone) ? null : $user->mobilephone ?>
                      <?php if(empty($value)): ?>
                        <?php $value = empty($user->homephone) ? null : $user->homephone ?>
                      <?php endif; ?>
                      <?php echo e(empty($value) ? "NaN" : $value); ?>

                    <?php endif; ?>
                  </td>
                  <td><?php echo e($user->currentbalance(true)->getsymbol().number_format($user->currentbalance(), 2, '.', ',')); ?></td>
                  <?php if($user->status == 1): ?>
                  <td><span class="badge bg-pill bg-primary-light"><?php echo e(__('Enabled')); ?></span></td>
                  <?php endif; ?>
                  <?php if($user->status == 0): ?>
                  <td><span class="badge bg-pill bg-warning-light"><?php echo e(__('Disabled')); ?></span></td>
                  <?php endif; ?>
                  <?php
                  $docspath = storage_path("app/docs/".$user->id);
                  $hasdocs  = is_dir($docspath) ? !empty(\File::files($docspath)) : false;
                  ?>
                  <?php if($user->docs_verified_at != null): ?>
                  <td><span class="badge bg-pill bg-light"><?php echo e(__('Completed')); ?></span></td>
                  <?php else: ?>
                  <td><span class="badge bg-pill bg-light"><?php echo e($hasdocs ? __('Pending') : __('Not Uploaded')); ?></span></td>
                  <?php endif; ?>
                  <td>
                    <div>
                      <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="btn ripple btn-light btn-sm fz-15">
                        <i class="fe fe-eye"></i>
                      </a>
                      <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn ripple btn-light btn-sm fz-15">
                        <i class="fe fe-edit"></i>
                      </a>
                      <a href="#" data-bs-target="#confirmstatus_<?php echo e($user->id); ?>" data-bs-toggle="modal" class="btn ripple btn-light btn-sm fz-15">
                        <?php if($user->status == 1): ?>
                        <i class="fe fe-minus-circle"></i>
                        <?php else: ?>
                        <i class="fe fe-check"></i>
                        <?php endif; ?>
                      </a>
                      <div class="modal" id="confirmstatus_<?php echo e($user->id); ?>">
                        <div class="modal-dialog modal-sm" role="document">
                          <div class="modal-content">
                            <div class="modal-header pd-l-15 pd-b-0">
                              <h6 class="modal-title"><?php echo e(__("Confirmation")); ?></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                            </div>
                            <div class="modal-body pd-l-15 pd-b-0">
                              <p class="lh-20">
                                <?php if($user->status == 1): ?>
                                <?php echo e(__("This will prevent ".$user->firstname." ".$user->lastname." from sending/receiving internal and wire transactions on all his accounts, and prevents updating of personal account information. The user will continue to be able to login to his account and access transactions history or dashboard summary.")); ?>

                                <?php else: ?>
                                <?php echo e(__("Allow ".$user->firstname." to use his account again and send/receive internal and wire transactions on all his accounts (except disabled accounts), and allows updating of personal account information such as email, phone number or other information.")); ?>

                                <?php endif; ?>
                              </p>
                            </div>
                            <div class="modal-footer justify-content-center pd-t-10 pd-b-10">
                              <form class="d-inline" method="POST" action="<?php echo e(route('admin.user.setstatus', $user->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php if($user->status == 1): ?>
                                <input type="hidden" name="status" value="0">
                                <?php else: ?>
                                <input type="hidden" name="status" value="1">
                                <?php endif; ?>
                                <button type="submit" class="btn ripple btn-primary">
                                  <?php echo e(__("Continue")); ?>

                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          <div class="float-right">
          <?php echo e($users->withQueryString()->links('vendor.pagination.bootstrap-4')); ?>

          </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/select2.min.js"></script>
<script src="<?php echo e(asset('backend/admin/assets/js/sweetalert2.all.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/user/index.blade.php ENDPATH**/ ?>