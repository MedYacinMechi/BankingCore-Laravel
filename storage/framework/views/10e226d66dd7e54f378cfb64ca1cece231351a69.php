<?php if(Session::has('success')): ?>
<div class="alert alert-success" role="alert">
  <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong><?php echo e(Session::get('success')); ?></strong>
</div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
<div class="alert alert-danger" role="alert">
  <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong><?php echo e(Session::get('error')); ?></strong>
</div>
<?php endif; ?>
<?php if(Session::has('errors')): ?>
<?php $__currentLoopData = Session::get('errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="alert alert-danger" role="alert">
  <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong><?php echo e($error); ?></strong>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/components/alerts.blade.php ENDPATH**/ ?>