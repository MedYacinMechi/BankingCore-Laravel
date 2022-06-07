
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Currencies')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Currencies')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
    <div class="col-12">
      <div class="row mb-4">
          <div class="col-lg-6">
            <div class="add-new-btn">
              <a href="<?php echo e(route('admin.currency.create')); ?>" class="btn btn-primary ripple">
              <?php echo e(__('Add New Currency')); ?>

              </a>
            </div>
          </div>
      </div>
      <?php echo $__env->make("components.alerts", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="table-responsive">
          <table class="main-default-table col-md-12 table table-bordered" id="table-2">
            <thead>
              <tr>
                <th><?php echo e(__('Name')); ?></th>
                <th><?php echo e(__('ISOcode')); ?></th>
                <th><?php echo e(__('Symbol')); ?></th>
                <th><?php echo e(__('Status')); ?></th>
                <th><?php echo e(__('Action')); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($currency->fullname); ?></td>
                <td><?php echo e($currency->ISOcode); ?></td>
                <td><?php echo e($currency->symbol); ?></td>
                <td>
                  <?php if($currency->status == 1): ?>
                  <span class="badge bg-pill bg-primary-light"><?php echo e(__('Enabled')); ?></span>
                  <?php endif; ?>
                  <?php if($currency->status == 0): ?>
                  <span class="badge bg-pill bg-warning-light"><?php echo e(__('Disabled')); ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <div>
                      <a href="<?php echo e(route('admin.currency.edit', $currency->id)); ?>" class="btn ripple btn-light btn-sm fz-15">
                        <i class="fe fe-edit"></i>
                      </a>
                      <a href="#" data-bs-target="#confirmstatus_<?php echo e($currency->id); ?>" data-bs-toggle="modal" class="btn ripple btn-light btn-sm fz-15">
                        <?php if($currency->status == 1): ?>
                        <i class="fe fe-minus-circle"></i>
                        <?php else: ?>
                        <i class="fe fe-check"></i>
                        <?php endif; ?>
                      </a>
                      <div class="modal" id="confirmstatus_<?php echo e($currency->id); ?>">
                        <div class="modal-dialog modal-sm" role="document">
                          <div class="modal-content">
                            <div class="modal-header pd-l-15 pd-b-0">
                              <h6 class="modal-title"><?php echo e(__("Confirmation")); ?></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                            </div>
                            <div class="modal-body pd-l-15 pd-b-0">
                              <p class="lh-20">
                                Are you sure?
                              </p>
                            </div>
                            <div class="modal-footer justify-content-center pd-t-10 pd-b-10">
                              <form class="d-inline" method="POST" action="<?php echo e(route('admin.currency.setstatus', $currency->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php if($currency->status == 1): ?>
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
          <?php echo e($currencies->links('vendor.pagination.bootstrap-4')); ?>

      </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/currency/index.blade.php ENDPATH**/ ?>