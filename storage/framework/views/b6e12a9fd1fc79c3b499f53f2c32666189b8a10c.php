<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <?php echo SEO::generate(true); ?>

    <link rel="icon" href="/frontend/assets/img/favicon1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/default.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/assets/css/responsive.css')); ?>">

    <!-- SPRUHA refs (unfiltered) -->
    <link href="/assets/plugins/web-fonts/icons.css" rel="stylesheet"/>
    <link href="/assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/plugins/web-fonts/plugin.css" rel="stylesheet"/>
    <link href="/assets/css/icon-list.css" rel="stylesheet"/>
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/boxed.css" rel="stylesheet" />
    <link href="/assets/css/dark-boxed.css" rel="stylesheet" />
    <link href="/assets/css/skins.css" rel="stylesheet">
    <link href="/assets/css/dark-style.css" rel="stylesheet">
    <link href="/assets/css/colors/default.css" rel="stylesheet">
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="/assets/css/colors/color.css">
    <?php echo $__env->yieldPushContent('css'); ?>
</head>
<body>
    <!--- Header Section ---->
    <?php echo $__env->make('layouts.frontend.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
    <?php echo $__env->yieldContent('content'); ?>

    <!--- footer Section ---->
    <?php echo $__env->make('layouts.frontend.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Jquery/Bootstrap-->
    <script src="<?php echo e(asset('frontend/assets/js/jquery.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="<?php echo e(asset('frontend/assets/js/iconify.min.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('js'); ?>
    <!-- Ebanq js -->
    <script src="/frontend/assets/js/common.js"></script>
</body>
</html><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/layouts/frontend/app.blade.php ENDPATH**/ ?>