<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <!-- css here -->
    <?php echo SEO::generate(true); ?>

    <link rel="icon" href="/frontend/assets/img/favicon1.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
    <!-- //TODO: remove all unnecessary sheets-->
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/default.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/responsive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/admin/assets/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/admin/assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('backend/admin/assets/css/components.css')); ?>">
    <link rel="stylesheet" href="/assets/plugins/web-fonts/icons.css">
    <link rel="stylesheet" href="/assets/plugins/web-fonts/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/web-fonts/plugin.css">
    <link rel="stylesheet" href="/assets/css/icon-list.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/boxed.css">
    <link rel="stylesheet" href="/assets/css/dark-boxed.css">
    <link rel="stylesheet" href="/assets/css/skins.css">
    <link rel="stylesheet" href="/assets/css/dark-style.css">
    <link rel="stylesheet" href="/assets/css/colors/default.css">
    <?php echo $__env->yieldPushContent('css'); ?>
</head>
<body class="main-body leftmenu adminapp">
    <?php echo $__env->yieldContent('content'); ?>
    <!-- Jquery/Bootstrap-->
    <script src="<?php echo e(asset('frontend/assets/js/jquery.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="<?php echo e(asset('frontend/assets/js/iconify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/admin/assets/js/moment.min.js')); ?>"></script>
    <!-- TODO: to be changed with spruha template alerts + push only when required -->
    <script src="<?php echo e(asset('backend/admin/assets/js/sweetalert2.all.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js'); ?>
    <!-- Ebanqmbi -->
    <script src="/frontend/assets/js/common.js"></script>
</body><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/layouts/backend/newapp.blade.php ENDPATH**/ ?>