<!-- header area start -->
<header>
    <div class="header-area <?php echo e(Request::is('/') ? null : 'fixed'); ?>">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="logo-area">
                        <a href="<?php echo e(url('/')); ?>">
                            <img style="height: 37.5px;" src="<?php echo e(asset('frontend/assets/img/logov1.png')); ?>" alt="">

                        </a>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="header-right-section-area">
                        <div class="header-menu f-right">
                            <div class="mobile-menu">
                                <a class="toggle f-right" href="#" role="button" aria-controls="hc-nav-1"><span class="iconify" data-icon="heroicons-outline:menu" data-inline="false"></span></a>
                            </div>
                           <!--  <nav id="main-nav">
                                <ul>
                                    <?php echo e(header_menu('header')); ?>

                                </ul>
                            </nav>-->
                        </div>
                        <div class="header-language-area f-right">
                            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="iconify" data-icon="tabler:world" data-inline="false"></span>
                                <span><?php echo e(App::getlocale()); ?></span>
                                <span class="iconify" data-icon="dashicons:arrow-down-alt2" data-inline="false"></span>
                            </a>
                            <?php
                                $langs = App\Models\Language::where('status',1)->get();
                            ?>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php $__currentLoopData = $langs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a href="<?php echo e(route('lang',$value->name)); ?>" class="dropdown-item"><?php echo e($value->data); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <div class="header-login-link f-right">
                            <?php if(Auth::check()): ?>
                                <span class="iconify" data-icon="ic:twotone-logout"  data-width="18" data-height="18" style="margin-right: 2px;margin-bottom: 2px;" data-inline="false"></span>
                                <a href="<?php echo e(route('logout')); ?>"><?php echo e(__('Logout')); ?></a>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>">
                                    <span class="iconify" data-icon="ic:twotone-login"  data-width="18" data-height="18" style="margin-right: 2px;margin-bottom: 2px;" data-inline="false"></span>
                                    <span><?php echo e(__('Login')); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if(Auth::check()): ?>
                            <div class="user-profile-img f-right">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false"><img src="https://ui-avatars.com/api/?size=45&background=random&name=<?php echo e(Auth::User()->name); ?>" alt=""></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a href="<?php echo e(route('login')); ?>" class="dropdown-item"><?php echo e(__('Dashboard')); ?></a></li>
                                    <li><a href="<?php echo e(route('user.account.setting')); ?>" class="dropdown-item"><?php echo e(__('Settings')); ?></a></li>
                                    <li><a href="<?php echo e(route('logout')); ?>" class="dropdown-item"><?php echo e(__('Logout')); ?></a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                        <div class="header-btn f-right">
                            <a href="<?php echo e(route('register')); ?>">
                                <span class="iconify" data-icon="fluent:key-32-regular" data-width="18" data-height="18" style="margin-bottom: 2px;" data-inline="false"></span>
                                <span><?php echo e(__('Register')); ?></span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end --><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/layouts/frontend/partials/header.blade.php ENDPATH**/ ?>