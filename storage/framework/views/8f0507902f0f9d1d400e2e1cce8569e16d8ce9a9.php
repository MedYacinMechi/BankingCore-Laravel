
<?php $__env->startSection('content'); ?>
<div class="page">
    <div class="main-sidebar main-sidebar-sticky side-menu ps ps--active-y">
        <div class="admin-top-sidebar-info">
            <p><?php echo e(__("Welcome,")); ?> <strong><?php echo e(\Auth::user()->username); ?></strong></p>
            <p><?php echo e(__("Current time:")); ?> <?php echo e(date("d-m-Y  H:i")); ?></p>
        </div>
        <div class="mt-0 pd-t-0 main-sidebar-body">
            <ul class="nav">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.index')): ?>
                <li class="nav-item <?php echo e(Request::is('admin/dashboard') ? 'active show' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-home sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item <?php echo e(Request::is('admin/requests*') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.requests.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-address-card sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('System Requests')); ?></span>
                        <?php
                        $pdrequests = \App\Models\SystemRequest::where("status", 0)->count()
                        ?>
                        <?php if($pdrequests > 0): ?>
                        <span class="badge bg-warning side-badge"><?php echo e($pdrequests); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('admin/transactions*') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.transactions.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-exchange-alt sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Transactions')); ?></span>
                    </a>
                </li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('support.index')): ?>
                <li class="nav-item <?php echo e(Request::is('admin/supports') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.support.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-headset sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Support')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-header"><span class="nav-label"><?php echo e(__('System')); ?></span></li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currency.index')): ?>
                <li class="nav-item <?php echo e(Request::is('admin/currency*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.currency.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-dollar-sign sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Currencies')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item <?php echo e(Request::is('admin/fees*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.fees.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-university sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Fees')); ?></span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('admin/users*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.users.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-users sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Users')); ?></span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('admin/accounts*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.accounts.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-hdd-o sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Accounts')); ?></span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('admin/account/types*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.account.types')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-hdd-o sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Account Types')); ?></span>
                    </a>
                </li>
                <li class="nav-header"><span class="nav-label"><?php echo e(__('Operations')); ?></span></li>
                <li class="nav-item <?php echo e(Request::is('admin/deposit*') ? 'show active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('admin.transactions.deposit')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fas fa-archive sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Deposit')); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-header side-header sticky sticky-pin" style="margin-bottom: -64.2px;">
        <div class="container-fluid pd-l-40">
            <div class="col-lg-3 pd-l-0">
                <div class="logo-area">
                    <a href="/">
                        <img style="padding-left: 1px; height: 37.5px;" src="/frontend/assets/img/logov1.png">
                    </a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="header-right-section-area">
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
                        <li>
                            <a href="<?php echo e(route('lang', $value->name)); ?>" class="dropdown-item">
                            <?php if($value->name == "fr"): ?>
                            <span class="iconify " data-width="18" data-height="18" data-icon="emojione:flag-for-france"></span>
                            <?php elseif($value->name == "en"): ?>
                            <span class="iconify " data-width="18" data-height="18" data-icon="emojione:flag-for-united-kingdom"></span>
                            <?php elseif($value->name == "es"): ?>
                            <span class="iconify " data-width="18" data-height="18" data-icon="emojione:flag-for-spain"></span>
                            <?php endif; ?>
                            <span class="pd-l-10"><?php echo e($value->data); ?></span>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <div class="header-login-link f-right">
                        <a href="<?php echo e(route('logout')); ?>">
                            <span class="iconify" data-icon="ic:twotone-logout"  data-width="18" data-height="18" style="margin-right: 2px;margin-bottom: 2px;" data-inline="false"></span>
                            <span><?php echo e(__('Logout')); ?></span>
                        </a>
                    </div>
                    <div class="user-profile-img f-right">
                        <div class="main-profile-menu">
                            <span class="d-flex" href="#">
                                <span class="header-avatar-admin noselect"><span>A</span></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="jumps-prevent" style="padding-top: 64.2px;"></div>
    <div class="main-content side-content pt-0">
        <div class="container-fluid">
            <div class="inner-body">
                <div class="page-header">
                    <div class="w-100">
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                    <?php if(Request::is("admin/dashboard")): ?>
                    <h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Dashboard')); ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo e(__('Dashboard')); ?></li>
                    </ol>
                    <div class="container-fluid pd-l-0 mg-t-25">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="content-section-title fz-22 m-0 mb-3 fw-400 color-53">
                                <?php echo e(__('Transactions')); ?>

                                <a class="color-80" href="<?php echo e(route('admin.transactions.index')); ?>"><i class="fz-16 fe fe-external-link"></i></a>
                                </h2>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Wire Transactions')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Debit and Credit Wire Transfers')); ?></span>
                                                </div>
                                                <div class="d-flex-inline" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Debits')); ?>">
                                                    <span class="si si-arrow-up-circle w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e('€'.number_format($wiredebits, 2, '.', ',')); ?></span>
                                                </div>
                                                <div class="d-flex-inline pd-l-20" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Credits')); ?>">
                                                    <span class="si si-arrow-down-circle w-h-32 fa-2x lh-32 color-777" ></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e('€'.number_format($wirecredits, 2, '.', ',')); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="carbon:license-global" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Internal Transactions')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted">EUR</span>
                                                </div>
                                                <div class="dashboard-card-value color-53"><?php echo e('€'.number_format($interndebits, 2, '.', ',')); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="ri:exchange-dollar-line" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Wire Transactions (per currency)')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Volume per each currency')); ?></span>
                                                </div>
                                                <?php $__currentLoopData = $curwiredebits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cwd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($cwd == 0 && $curwirecredits[$key] == 0): ?>
                                                <?php continue; ?>
                                                <?php endif; ?>
                                                <div class="fz-18 pd-b-10"><?php echo e($currencies[$key][1]); ?></div>
                                                <div class="d-flex-inline" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Debits')); ?>">
                                                    <span class="si si-arrow-up-circle w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 fz-18 color-53"><?php echo e($currencies[$key][0].number_format($cwd, 2, '.', ',')); ?></span>
                                                </div>
                                                <div class="d-flex-inline pd-l-20" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Credits')); ?>">
                                                    <span class="si si-arrow-down-circle w-h-32 fa-2x lh-32 color-777" ></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 pd-b-10 fz-18 color-53"><?php echo e($currencies[$key][0].number_format($curwirecredits[$key], 2, '.', ',')); ?></span>
                                                </div>
                                                <br>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="carbon:license-global" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Pending Transactions')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Pending wire and internal transactions')); ?></span>
                                                </div>
                                                <div class="d-flex-inline" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Debits')); ?>">
                                                    <span class="si si-arrow-up-circle w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e($pdebits); ?></span>
                                                </div>
                                                <div class="d-flex-inline pd-l-20" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Credits')); ?>">
                                                    <span class="si si-arrow-down-circle w-h-32 fa-2x lh-32 color-777" ></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e($pwirecredits); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="bi:clock-history" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Pending Wire Transactions')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Pending Debit and Credit Wire Transfers')); ?></span>
                                                </div>
                                                <div class="d-flex-inline" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Debits')); ?>">
                                                    <span class="si si-arrow-up-circle w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e('€'.number_format($pwiredebitsvalue, 2, '.', ',')); ?></span>
                                                </div>
                                                <div class="d-flex-inline pd-l-20" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Credits')); ?>">
                                                    <span class="si si-arrow-down-circle w-h-32 fa-2x lh-32 color-777" ></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e('€'.number_format($pwirecreditsvalue, 2, '.', ',')); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="bi:clock-history" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h2 class="content-section-title fz-22 m-0 mb-3 fw-400 color-53">
                                <?php echo e(__('Funds')); ?>

                                </h2>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Funds')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(('Total funds held by all accounts')); ?></span>
                                                </div>
                                                <div class="dashboard-card-value color-53"><?php echo e('€'.number_format($totalfunds, 2, '.', ',')); ?>

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="icon-park-outline:funds" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h2 class="content-section-title fz-22 m-0 mb-3 fw-400 color-53">
                                <?php echo e(__('Users')); ?>

                                <a class="color-80" href="<?php echo e(route('admin.users.index')); ?>"><i class="fz-16 fe fe-external-link"></i></a>
                                </h2>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Total Users')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('All users including unverified')); ?></span>
                                                </div>
                                                <div class="dashboard-card-value color-53"><?php echo e($countusers); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="ph:users-three" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Active Users')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('All verified and active users')); ?></span>
                                                </div>
                                                <div class="dashboard-card-value color-53"><?php echo e($avusers); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="ic:outline-verified-user" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Unverified Users')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Number of non-verfied users')); ?></span>
                                                </div>
                                                <div class="dashboard-card-value color-53"><?php echo e($unverifusers); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="mdi:account-cancel-outline" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h2 class="content-section-title fz-22 m-0 mb-3 fw-400 color-53">
                                <?php echo e(__('Support')); ?>

                                <a class="color-80" href="<?php echo e(route('admin.support.index')); ?>"><i class="fz-16 fe fe-external-link"></i></a>
                                </h2>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card border-left-primary shadow card-statistic-2 mg-t-0">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08" googl="true">
                                            <div class="col mr-2">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__('Customer Support')); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted"><?php echo e(__('Closed and open support tickets')); ?></span>
                                                </div>
                                                <div class="d-flex-inline" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Open')); ?>">
                                                    <span class="si si-check w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e($opensupport); ?></span>
                                                </div>
                                                <div class="d-flex-inline pd-l-20" data-bs-placement="bottom" data-bs-toggle="tooltip" title="<?php echo e(__('Closed')); ?>">
                                                    <span class="si si-minus w-h-32 fa-2x lh-32 color-777"></span>
                                                    <span class="dashboard-card-value lh-32 pd-l-10 color-53"><?php echo e($closedsupport); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="iconify text-gray-300" data-icon="ic:twotone-support-agent" data-width="48" data-height="48"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    </div>
                    <?php echo $__env->yieldContent('pagecontentop'); ?>
                </div>
                <?php echo $__env->yieldContent('pagecontent'); ?>
            </div>
        </div>
    </div> 
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/sidemenu/sidemenu.js"></script>
<script src="/frontend/assets/js/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.backend.newapp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>