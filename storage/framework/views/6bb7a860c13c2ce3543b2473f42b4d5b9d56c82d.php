
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('User Details')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>"><?php echo e(__('Users')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($user->firstname." ".$user->lastname); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="">
      <div class="">
          <?php echo $__env->make('components.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <h4 class="d-inline"><?php echo e(__('Information')); ?></h4><i class="information-toggle pd-l-5 fz-15 cursor-pointer fe fe-corner-right-down"></i>
          <div class="table-responsive mg-t-5">
              <table class="main-default-table col-md-12 table table-bordered">
                  <thead>
                    <tr>
                        <th scope="col"><?php echo e(__('Title')); ?></th>
                        <th scope="col"><?php echo e(__('Value')); ?></th>
                    </tr>
                  </thead>
                  <tbody class="information-tbody bg-fafafa d-none">
                    <tr>
                      <td class="col-3"><?php echo e(__('First Name')); ?></td>
                      <td><?php echo e($user->firstname); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Last Name')); ?></td>
                      <td><?php echo e($user->lastname); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Birthdate')); ?></td>
                      <td><?php echo e(empty($user->birthdate) ? "NaN" : $user->birthdate); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Document Type')); ?></td>
                      <td><?php echo e($user->doctype == 0 ? __("Passport") : ($user->doctype == 1 ? __("National ID") : __("Driver License"))); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Document Number')); ?></td>
                      <td><?php echo e($user->docnumber); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Document Issue Date')); ?></td>
                      <td><?php echo e($user->docissuedate); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Username')); ?></td>
                      <td><?php echo e($user->username); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Email')); ?></td>
                      <td><?php echo e($user->email); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Mobile Phone')); ?></td>
                      <td><?php echo e($user->mobilephone == null ? "NaN" : $user->mobilephone); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Home Phone')); ?></td>
                      <td><?php echo e($user->homephone == null ? "NaN" : $user->homephone); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Office Phone')); ?></td>
                      <td><?php echo e($user->officephone == null ? "NaN" : $user->officephone); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Fax')); ?></td>
                      <td><?php echo e($user->fax == null ? "NaN" : $user->fax); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Google 2FA')); ?></td>
                      <td>
                        <?php if($user->g2fakey != null): ?>
                        <span class="badge bg-pill bg-primary-light"><?php echo e(__('Active')); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-warning-light"><?php echo e(__('Unset')); ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Status')); ?></td>
                      <td>
                        <?php if($user->status == 1): ?>
                        <span class="badge bg-pill bg-primary-light"><?php echo e(__('Active')); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-warning-light"><?php echo e(__('Inactive')); ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Email/Phone status')); ?></td>
                      <td>
                        <?php if($user->email_verified_at != null): ?>
                        <span class="badge bg-pill bg-primary-light"><?php echo e(__('Verified')); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-warning-light"><?php echo e(__('Unverified')); ?></span>
                        <?php endif; ?>
                        <?php if($user->phone_verified_at != null): ?>
                        <span class="badge bg-pill bg-primary-light"><?php echo e(__('Verified')); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-warning-light"><?php echo e(__('Unverified')); ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('KYC status')); ?></td>
                      <td>
                        <?php if($user->docs_verified_at == null): ?>
                        <span class="badge bg-pill bg-warning-light"><?php echo e(__('Pending')); ?></span>
                        <?php else: ?>
                        <span class="badge bg-pill bg-primary-light"><?php echo e(__('Completed')); ?></span>
                        <?php endif; ?>
                        <?php if($hasdocs): ?>
                        <a href="<?php echo e(route('admin.user.getdocs', $user->id)); ?>">
                          <button type="button" class="btn ripple btn-primary btn-sm"><?php echo e(__('Download')); ?></button>
                        </a>
                        <?php endif; ?>
                        <?php if($hasdocs && $user->docs_verified_at == null): ?>
                        <form class="d-inline" action="<?php echo e(route('admin.user.setkycstatus', $user->id)); ?>" method="POST">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="status" value="1">
                          <button type="submit" class="btn ripple btn-success btn-sm"><?php echo e(__('Approve')); ?></button>
                        </form>
                        <?php endif; ?>
                        <?php if($user->docs_verified_at != null): ?>
                        <form class="d-inline" action="<?php echo e(route('admin.user.setkycstatus', $user->id)); ?>" method="POST">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="status" value="0">
                          <button type="submit" class="btn ripple btn-warning btn-sm"><?php echo e(__('Cancel Approval')); ?></button>
                        </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <!-- <tr>
                      <td class="col-3"><?php echo e(__('Last seen')); ?></td>
                      <td><?php echo e($user->last_active); ?></td>
                    </tr> -->
                    <tr>
                      <td class="col-3"><?php echo e(__('Residence')); ?></td>
                      <td><?php echo e(empty($user->residence) ? "NaN" : $user->residence); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Citizenship')); ?></td>
                      <td><?php echo e(empty($user->citizenship) ? "NaN" : $user->citizenship); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('City')); ?></td>
                      <td><?php echo e(empty($user->city) ? "NaN" : $user->city); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Address')); ?></td>
                      <td><?php echo e(empty($user->address) ? "NaN" : $user->address); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Alt. Address')); ?></td>
                      <td><?php echo e(empty($user->address2) ? "NaN" : $user->address2); ?></td>
                    </tr>
                    <?php if($user->type == 1): ?>
                    <tr>
                      <td class="col-3"><?php echo e(__('Position')); ?></td>
                      <td><?php echo e(empty($user->position) ? "NaN" : $user->position); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Company')); ?></td>
                      <td><?php echo e(empty($user->companyname) ? "NaN" : $user->companyname); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Incorporation Type')); ?></td>
                      <td><?php echo e(empty($user->incorporationtype) ? "NaN" : $user->incorporationtype); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Company Registration Number')); ?></td>
                      <td><?php echo e(empty($user->companyregnumber) ? "NaN" : $user->companyregnumber); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('VAT')); ?></td>
                      <td><?php echo e(empty($user->uk_eu_vat) ? "NaN" : $user->uk_eu_vat); ?></td>
                    </tr>
                    <tr>
                      <td class="col-3"><?php echo e(__('Launch Date')); ?></td>
                      <td><?php echo e(empty($user->launchdate) ? "NaN" : $user->launchdate); ?></td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
              </table>
          </div>
          <h4 class="d-inline"><?php echo e(__('Accounts')); ?></h4><i class="accounts-toggle pd-l-5 fz-15 cursor-pointer fe fe-corner-right-down"></i>
          <div class="table-responsive mg-t-5">
            <table class="main-default-table col-md-12 table table-bordered">
              <thead>
                <tr>
                    <th scope="col"><?php echo e(__('Identifier')); ?></th>
                    <th scope="col"><?php echo e(__('Currency')); ?></th>
                    <th scope="col"><?php echo e(__('Type')); ?></th>
                    <th scope="col"><?php echo e(__('Available Balance')); ?></th>
                    <th scope="col"><?php echo e(__('Current Balance')); ?></th>
                    <th scope="col"><?php echo e(__('Last Activity')); ?></th>
                    <th scope="col"><?php echo e(__('Creation Date')); ?></th>
                </tr>
              </thead>
              <tbody class="bg-fafafa accounts-tbody d-none">
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><a href="<?php echo e(route('admin.accounts.view', $acc->id)); ?>"><?php echo e($acc->identifier); ?></a></td>
                  <td><?php echo e($acc->currency->ISOcode); ?></td>
                  <td><?php echo e($acc->acctype->name); ?></td>
                  <td><?php echo e(number_format($acc->balance(), 2, '.', ',')); ?></td>
                  <td><?php echo e(number_format($acc->currentbalance(), 2, '.', ',')); ?></td>
                  <td><?php echo e($acc->lastactivity()); ?></td>
                  <td><?php echo e($acc->created_at); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
          <h4 class="mg-t-5"><?php echo e(__("Transactions")); ?></h4>
          <div class="table-responsive">
            <?php echo $__env->make("admin.transactions.user_table", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="float-right">
            <?php echo e($transactions->links('vendor.pagination.bootstrap-4')); ?>

            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/user/view.blade.php ENDPATH**/ ?>