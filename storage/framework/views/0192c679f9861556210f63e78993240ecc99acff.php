

<?php $__env->startSection('content'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="main-content">
    <div class="container-fluid">
        <div class="inner-body" style="min-height: 600px !important;">
        <!-- <div class="col-md-12" id="tsparticles" style="min-height: 600px !important;"> -->
            <div class="card" style="width: 50%;left: 25%;">
                <div class="row row-sm">
                    <div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
                        <div class="mt-5 pt-4 p-2 pos-absolute">
                            <div class="clearfix"></div>
                            <img src="/assets/img/svgs/user.svg" class="ht-100 mb-0" alt="user">
                            <h5 class="mt-4 text-white"><?php echo e(__("Signin to Your Account")); ?></h5>
                            <span class="tx-white-6 tx-13 mb-5 mt-xl-0"><?php echo e(__("If you don't have an account yet signup to access unlimited home banking tools and services")); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form ">
                        <div class="container-fluid">
                            <div class="row row-sm">
                                <div class="card-body mt-2 mb-2">
                                    <img src="/frontend/assets/img/logov1.png" class="d-lg-none header-brand-img text-start float-start mb-4" alt="logo">
                                    <div class="clearfix"></div>
                                    <form method="POST" action="<?php echo e(route('user.login')); ?>" class="login-form needs-validation">
                                        <?php echo csrf_field(); ?>
                                        <h5 class="text-start mb-2"><?php echo e(__("Signin to Your Account")); ?></h5>
                                        <p class="mb-4 text-muted tx-13 ms-0 text-start"><?php echo e(__("Signin to transfer, loan and pay your bills online")); ?></p>
                                        <?php if(Session::has('message')): ?>
                                        <div class="alert alert-success" role="alert">
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <?php echo e(Session::get('message')); ?>

                                        </div>
                                        <?php endif; ?>
                                        <?php if(Session::has('success')): ?>
                                        <div class="alert alert-success" role="alert">
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            Session::get('success')
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group text-start">
                                            <label><?php echo e(__("Email")); ?></label>
                                            <input name="email" class="form-control" placeholder="Enter your email" type="text">
                                        </div>
                                        <div class="form-group text-start">
                                            <label><?php echo e(__("Password")); ?></label>
                                            <input name="password" class="form-control" placeholder="Enter your password" type="password">
                                        </div>
                                        <!-- 6Lf0do0eAAAAAGTsA3BovcRTqJG3WZMcTxtN0u9X -->
                                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                                        <button class="btn ripple btn-main-primary btn-block"><?php echo e(__("Sign In")); ?></button>
                                    </form>
                                    <div class="text-start mt-5 ms-0">
                                        <div class="mb-1"><a href="<?php echo e(route('password.request')); ?>"><?php echo e(__("Forgot password?")); ?></a></div>
                                        <div><?php echo e(__("Don't have an account?")); ?> <a href="<?php echo e(route('register')); ?>"><?php echo e(__("Register Here")); ?></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- dahboard area start -->
<!-- <section>
    <div class="dashboard-area pt-150 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 offset-lg-3">
                    <div class="main-container">
                        <div class="header-section">
                            <h4><?php echo e(__('Login In')); ?></h4>
                        </div>
                        <?php if(Session::has('message')): ?>
                            <div class="alert alert-success">
                                <?php echo e(Session::get('message')); ?>

                            </div>
                        <?php endif; ?>
                        <?php if(Session::has('success')): ?>
                        <div class="alert alert-success"><?php echo e(Session::get('success')); ?></div>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('login')); ?>" class="needs-validation" novalidate="">
                        <?php echo csrf_field(); ?>
                            <div class="login-section">
                                <h6><?php echo e(__('Email & Password')); ?></h6>
                                <div class="form-group">
                                    <input type="text" name="email" placeholder="<?php echo e(__('Enter Email Address')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" placeholder="<?php echo e(__('Password')); ?>" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            <?php echo e(__('Remember Me')); ?>

                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <?php if(Route::has('password.request')): ?>
                                        <div class="forgot-password f-right">
                                          <a href="<?php echo e(route('password.request')); ?>" class="text-small">
                                            <?php echo e(__('Forgot Password?')); ?>

                                          </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="login-btn">
                                            <button type="submit"><?php echo e(__('Login')); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="already-have-account text-center">
                                            <br>
                                            <p><?php echo e(__('Not registered?')); ?> <a href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- dahboard area end -->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tsparticles/1.18.11/tsparticles.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/auth/login.blade.php ENDPATH**/ ?>