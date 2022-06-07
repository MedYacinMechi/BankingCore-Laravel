
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('System Requests')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__('Requests')); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="">
      <div class="">
          <div class="row">
            
          </div>
          <div class="table-responsive">
            <table class="main-default-table col-md-12 table table-bordered">
              <thead>
                <tr>
                  <th><?php echo e(__('Datetime')); ?></th>
                  <th><?php echo e(__('From')); ?></th>
                  <th><?php echo e(__('Subject')); ?></th>
                  <th><?php echo e(__('Importance')); ?></th>
                  <th><?php echo e(__('Status')); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <?php if($request->type == 0): ?>
                    <td><?php echo e($request->created_at); ?></td>
                    <td>
                      <a href="<?php echo e(route('admin.users.show', $request->transaction->user->id)); ?>">
                        <?php echo e($request->transaction->user->firstname." ".$request->transaction->user->lastname); ?>

                      </a>
                      <span><?php echo e($request->transaction->currencycode().number_format($request->transaction->value, 2, '.', ',')); ?></span>
                      <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                      <?php if($request->transaction->wireid == null): ?>
                      <a href="<?php echo e(route('admin.users.show', $request->transaction->user->id)); ?>">
                        <?php echo e($request->transaction->destinationtx()->user->firstname." ".$request->transaction->destinationtx()->user->lastname); ?>

                      </a>
                      <span><?php echo e($request->transaction->destinationtx()->currencycode().number_format($request->transaction->destinationtx()->value, 2, '.', ',')); ?></span>
                      <?php else: ?>
                      <span><?php echo e($request->transaction->wire->_32a_currency.number_format($request->transaction->wire->_32a_value, 2, '.', ',')); ?></span>
                      <span><?php echo e($request->transaction->wire->_59_iban); ?></span>
                      <?php endif; ?>
                    </td>
                    <td><a href="<?php echo e(route('admin.requests.view', $request->id)); ?>"><?php echo e($request->subject); ?></a></td>
                    <td><?php echo e($request->importance); ?></td>
                    <td>
                      <span class="all-small-caps text-<?php echo e($request->status == 1 ? 'success' : ($request->status == 2 ? 'danger' : 'warning')); ?>">
                      <span class="dot-label bg-<?php echo e($request->status == 1 ? 'success' : ($request->status == 2 ? 'danger' : 'warning')); ?> me-1"></span>
                      <?php echo e($request->status == 1 ? 'EXECUTED' : ($request->status == 2 ? 'CANCELED' : 'PENDING')); ?>

                    </span>
                    </td>
                  <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/requests/index.blade.php ENDPATH**/ ?>