
<?php $__env->startPush('css'); ?>
<link href="/frontend/assets/css/select2.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php
    $prefix = "script/storage/app/avatar/".Auth::user()->username;
    $exts   = [".jpg", ".jpeg", ".png", ".bmp"];
    $avatar = null;
    foreach ($exts as $ext) { if (file_exists($prefix.$ext)) { $avatar = $prefix.$ext; }}
    preg_match_all("/^(\w)\w+\s(\w)/i", Auth::user()->name, $matches);
    $username_initials = "NA";
    if (count($matches) == 3) { $username_initials =Auth::user()->firstname[0].Auth::user()->lastname[0]; }
?>
<?php $__env->startSection('content'); ?>
<span id="userdashboard" class="d-none"></span>
<div class="page">
    <div class="main-sidebar main-sidebar-sticky side-menu ps ps--active-y">
        <div class="pt-4 sidebar-top-content text-center user-sidebar-top">
            <div class="user-sidebar-imgcontainer">
                <div class="user-pic-container avatar avatar-xxl d-none d-sm-flex bg-primary" style="<?php echo e(($avatar != null) ? 'background-color: #eaedf7 !important;' : ''); ?>">
                     <?php if($avatar == null): ?>
                    <?php echo e(Auth::user()->firstname[0].Auth::user()->lastname[0]); ?>

                    <?php endif; ?>
                    <img <?php if($avatar != null): ?> src="/<?php echo e($avatar); ?>" <?php endif; ?>>
                    <input type="file" id="file">
                    <label class="user-pic-upload-btn" for="file" id="uploadBtn">
                        <i class="cam-icon"></i>
                    </label>
                </div>
            </div>
            <div class="user-name pt-4">
                <span class="tag tag-primary tag-pill mt-1 mb-1"><?php echo e(Auth::user()->username); ?></span>
            </div>
        </div>
        <div class="main-sidebar-body">
            <ul class="nav">
                <li class="nav-header">
                    <span class="nav-label">
                    <?php echo e(Auth::user()->firstname." ".Auth::user()->lastname); ?>

                    </span>
                </li>
                <li class="nav-item <?php echo e(Request::is('user/dashboard') ? 'active show' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('user.dashboard')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-home sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('user/support*') ? 'active show' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('user.support.index')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-mail sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Support')); ?></span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(Request::is('user/transfer*') ? 'active show' : ''); ?>">
                    <a class="nav-link with-sub" href="#">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-send sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Transfer')); ?></span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>
                    <ul class="nav-sub">
                        <li class="side-menu-label1"><a href="#"><?php echo e(__('Transfer')); ?></a></li>
                        <li class="nav-sub-item <?php echo e(Request::is('user/transfer/between-accounts*') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.transfer.between_accounts')); ?>">
                            <?php echo e(__('Between Accounts')); ?>

                        </a>
                        </li>
                        <li class="nav-sub-item <?php echo e(Request::is('user/transfer/another-user*') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.transfer.other_user')); ?>">
                            <?php echo e(__('Another User')); ?>

                            </a>
                        </li>
                        <li class="nav-sub-item <?php echo e(Request::is('user/transfer/multiple-users*') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.transfer.multiple_users')); ?>">
                            <?php echo e(__('Multiple Users')); ?>

                            </a>
                        </li>
                        <li class="nav-sub-item <?php echo e(Request::is('user/transfer/wire') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.transfer.wire')); ?>"><?php echo e(__('Wire')); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?php echo e(Request::is('user/account-*') ? 'active show' : (Request::is('user/transaction/history') ? 'active show' : '')); ?>">
                    <a class="nav-link with-sub" href="#">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-user sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Account')); ?></span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-sub-item <?php echo e(Request::is('user/transaction/history') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.transaction.history')); ?>"><?php echo e(__('Transaction History')); ?></a>
                        </li>
                        <li class="nav-sub-item <?php echo e(Request::is('user/account-settings') ? 'active' : ''); ?>">
                            <a class="nav-sub-link" href="<?php echo e(route('user.account.setting')); ?>"><?php echo e(__('Personal Information')); ?></a>
                        </li>
                        <li class="nav-sub-item ">
                            <a class="nav-sub-link" href="#"><?php echo e(__('Security')); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('logout')); ?>">
                        <span class="shape1"></span>
                        <span class="shape2"></span>
                        <i class="fe fe-power sidemenu-icon"></i>
                        <span class="sidemenu-label"><?php echo e(__('Logout')); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-header side-header sticky sticky-pin" style="margin-bottom: -64.2px;">
        <div class="container-fluid">
            <div class="col-lg-3">
                <div class="logo-area">
                    <a href="/">
                        <img style="height: 37.5px;" src="/frontend/assets/img/logov1.png">
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
                    <div class="main-header-notification">
                        <a class="nav-link icon " data-bs-toggle="dropdown" aria-expanded="false" href="#">
                            <i class="fe fe-bell header-icons"></i>
                            <span class="badge bg-danger nav-link-badge " id="nbr_notif"><?php echo e(Auth::user()->unreadNotifications->count()); ?></span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="header-navheading ">
                                <p class="main-notification-text"> You have <span id="nbr_notif2"><?php echo e(Auth::user()->unreadNotifications->count()); ?></span> unread notification<span class="badge bg-pill bg-primary ms-3">View all</span></p>
                            </div>
                            <div class="main-notification-list" id="notif">
                                 <?php $__currentLoopData = Auth::user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="media new " >
                                      <!--  <div class="main-img-user online"><img alt="avatar" src="/frontend/assets/img/logov1.png">
                                        </div>-->
                                        <div class="media-body " data-id="<?php echo e($notification->id); ?>" role="note">
                                            <p><?php echo e($notification->data['type']); ?> <strong><?php echo e(Auth::user()->username); ?></strong> <?php echo e($notification->data['message']); ?></p><span><?php echo e($notification->created_at); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="dropdown-footer">
                                <a href="<?php echo e(route('user.notifications')); ?>" id="all_not" >View All Notifications</a>
                            </div>
                        </div>
                    </div>
                    <div class="header-login-link f-right">
                        <a href="<?php echo e(route('logout')); ?>">
                            <span class="iconify" data-icon="ic:twotone-logout"  data-width="18" data-height="18" style="margin-right: 2px;margin-bottom: 2px;" data-inline="false"></span>
                            <span><?php echo e(__('Logout')); ?></span>
                        </a>
                    </div>
                    <div class="user-profile-img f-right">
                        <div class="dropdown main-profile-menu">
                            <div class="d-flex">
                                <span class="header-avatar-small avatar">
                                    <?php if($avatar == null): ?>
                                     <span><?php echo e(Auth::user()->firstname[0].Auth::user()->lastname[0]); ?></span>
                                    <?php else: ?>
                                    <img <?php if($avatar != null): ?> src="/<?php echo e($avatar); ?>" <?php endif; ?>>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="jumps-prevent pd-t-64-2"></div>
    <div class="main-content side-content pt-0">
        <div class="container-fluid">
            <div class="inner-body">
                <div class="page-header">
                    <div class="w-100">
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                    <?php if(Request::is("user/dashboard")): ?>
                    <script>
                    const exchangeScheme = JSON.parse(`<?= json_encode($exhscheme) ?>`);
                    </script>
                    <h2 class="main-content-title fz-26 mg-b-5 pd-l-25"><?php echo e(__('Dashboard')); ?></h2>
                    <ol class="breadcrumb pd-l-25">
                        <li class="breadcrumb-item"><a href="#"><?php echo e(__('Account')); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo e(__('Summary')); ?></li>
                    </ol>
                    <div class="container-fluid mg-t-25">
                        <h4>
                            <strong class="fw-400 color-53"><?php echo e(('Balance Allocation')); ?></strong>
                        </h4>
                    </div>
                    <div class="container-fluid">
                        <div class="progress w-100 pd-0">
                            <?php if(count($balancealloc) == 0): ?>
                            <div class="progress-bar progress-bar-xs bg-primary" style="width: 100% !important;">
                                <span>0%</span>
                            </div>
                            <?php endif; ?>
                            <?php
                            $colors = array("bg-success", "bg-danger", "bg-info", "bg-secondary", "bg-primary")
                            ?>
                            <?php $__currentLoopData = $balancealloc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="progress-bar progress-bar-xs <?php echo e(array_pop($colors)); ?>" style="width: <?php echo e(($value != 0 && $value < 10) ? 10 : $value); ?>% !important;">
                                <span><?php echo e($key); ?> <?php echo e(substr($value, 0, 4)); ?>%</span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="container-fluid mg-t-25">
                        <div class="list-style-none mg-0 pd-0">
                            <div class="">
                                <h4 class="summary-period-header d-inline">
                                    <strong class="color-53 fw-400"><?php echo e(__('Summary for the')); ?></strong>
                                    <select name="summaryperiod" class="form-group text-start select2 select2-summary">
                                        <option value="0"><?php echo e(__("month")); ?></option>
                                        <option value="1"><?php echo e(__("week")); ?></option>
                                        <option value="2"><?php echo e(__("day")); ?></option>
                                        <option value="3"><?php echo e(__("year")); ?></option>
                                    </select>
                                </h4>
                                <h4 class="summary-period-header d-inline float-right">
                                    <i id="defaultcur" data-id="<?php echo e($defaultcur->id); ?>"></i>
                                    <select name="summarycurrency" class="form-group text-start select2 select2-currency">
                                        <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option data-symbol="<?php echo e($currency->symbol); ?>" value="<?php echo e($currency->id); ?>"><?php echo e(trim($currency->ISOcode)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row w-100 mg-0 pd-0">
                            <div class="col-xl-4 col-md-4 mb-4 pd-l-0">
                                <div class="card border-left-primary shadow summary-card">
                                    <div class="dashboard-card">
                                        <div class="row no-gutters align-items-center pd-t-rem-08">
                                            <div class="col mr-2 money-in">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__("Money In")); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted">
                                                        <?php echo e(__("All Accounts")); ?> <span class="curr">(<?php echo e($defaultcur->ISOcode); ?>)</span>
                                                    </span>
                                                </div>
                                                <div data-value="<?php echo e($moneyin); ?>" data-currency="<?php echo e($defaultcur->ISOcode); ?>" class="dashboard-card-value text-gray-800">
                                                    <?php echo e($defaultcur->symbol.number_format($moneyin, 2, '.', ',')); ?>

                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-circle-down fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4 mb-4">
                                <div class="card border-left-primary shadow summary-card">
                                    <div class="dashboard-card">
                                        <div class="row no-gutters align-items-center pd-t-rem-08">
                                            <div class="col mr-2 money-out">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__("Money Out")); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted">
                                                        <?php echo e(__("All Accounts")); ?> <span class="curr">(<?php echo e($defaultcur->ISOcode); ?>)</span>
                                                    </span>
                                                </div>
                                                <div data-value="<?php echo e($moneyout); ?>" data-currency="<?php echo e($defaultcur->ISOcode); ?>" class="dashboard-card-value text-gray-800">
                                                    <?php echo e($defaultcur->symbol.number_format($moneyout, 2, '.', ',')); ?>

                                                </div>    
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-circle-up fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4 mb-4 pd-r-0 pd-r-0">
                                <div class="card border-left-primary shadow summary-card">
                                    <div class="dashboard-card" googl="true">
                                        <div class="row no-gutters align-items-center pd-t-rem-08">
                                            <div class="col mr-2 pending-in-out">
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-18 font-weight-bold mb-1"><?php echo e(__("Pending Transactions")); ?></label>
                                                    <span class="d-block tx-12 mb-0 text-muted">
                                                        <?php echo e(__("All Accounts")); ?> <span class="curr">(<?php echo e($defaultcur->ISOcode); ?>)</span>
                                                    </span>
                                                </div>
                                                <div data-in="<?php echo e($pmoneyin); ?>" data-out="<?php echo e($pmoneyout); ?>" data-currency="<?php echo e($defaultcur->ISOcode); ?>" class="dashboard-card-value text-gray-800">
                                                    <span class="fas fa-arrow-circle-down text-gray-300"></span>
                                                    <span class="pmoneyin"><?php echo e($defaultcur->symbol.number_format($pmoneyin, 2, '.', ',')); ?></span>
                                                    <span class="fas fa-arrow-circle-up text-gray-300 pd-l-20"></span>
                                                    <span class="pmoneyout"><?php echo e($defaultcur->symbol.number_format($pmoneyout, 2, '.', ',')); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fa fa-clock-o fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="mg-t-25 mg-b-15">
                            <h4><strong class="fw-400 color-53"><?php echo e(('My Accounts')); ?></strong></h4>
                        </div>
                        <div class="table-responsive">
                            <table class="main-default-table col-md-12 table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"><?php echo e(__('Account Number')); ?></th>
                                        <th scope="col"><?php echo e(__('Type')); ?></th>
                                        <th scope="col"><?php echo e(__('Currency')); ?></th>
                                        <th scope="col"><?php echo e(__('Current Balance')); ?></th>
                                        <th scope="col"><?php echo e(__('Last Activity')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-fafafa">
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><a href="<?php echo e(route('user.account.view', $account->id)); ?>"><?php echo e($account->identifier); ?></a></td>
                                        <td><?php echo e($account->acctype->name); ?></td>
                                        <td><?php echo e($account->currency->ISOcode); ?></td>
                                        <td><?php echo e($account->currency->symbol.number_format($account->balance(), 2, '.', ',')); ?></td>
                                        <td><?php echo e($account->lastactivity()); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="mg-t-25 mg-b-15">
                            <h4>
                                <strong class="fw-400 color-53"><?php echo e(('Transactions')); ?></strong>
                            </h4>
                        </div>
                        <div class="table-responsive">
                            <table class="main-default-table transactions main-default-table-clickable col-md-12 table table-bordered">
                                <?php echo $__env->make("user.transaction.transactions", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </table>
                            <div class="float-right">
                                <?php echo e($transactions->links('vendor.pagination.bootstrap-4')); ?>

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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="/frontend/assets/js/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/frontend/assets/js/sidemenu/sidemenu.js"></script>
<script src="/frontend/assets/js/select2.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend.newapp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/dashboard.blade.php ENDPATH**/ ?>