
<?php $__env->startSection('breadcrumb'); ?>
<h2 class="main-content-title tx-24 mg-b-5 fw-200"><?php echo e(__('Account Report')); ?></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo e(__('System')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(__("Accounts")); ?></li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('pagecontent'); ?>
<div class="row">
  <div class="col-12">
    <div class="">
      <div class="">
        <div class="d-none">
          <i id="userpb_info" data-address="<?php echo e($account->user->address_represent()); ?>" data-fullname="<?php echo e($account->user->name_represent()); ?>"></i>
          <i id="statement_time" data-ts="<?php echo e(date('Y/m/d H:i:s')); ?>"></i>
          <?php if(count($transactions) > 0): ?>
          <i id="statement_period" data-start="<?php echo e($transactions[0]['created_at']); ?>" data-end="<?php echo e($transactions[count($transactions)-1]['created_at']); ?>"></i>
          <?php else: ?>
          <i id="statement_period" data-start="" data-end=""></i>
          <?php endif; ?>
        </div>
        <div class="row">
          <h4 class="col-6"><?php echo e(__("User")); ?></h4>
          <div class="col-6">
            <button class="account-statement-btn float-right btn-rounded btn-sm btn ripple btn-primary"><?php echo e(__("PDF Statement")); ?></button>
          </div>
        </div>
        <div class="table-responsive mg-t-5">
          <table class="main-default-table col-md-12 table table-bordered">
              <thead>
                <tr>
                  <th scope="col"><?php echo e(__('Company (Legal Person) or Full Name')); ?></th>
                  <th scope="col"><?php echo e(__('Creation Date')); ?></th>
                </tr>
              </thead>
              <tbody class="information-tbody bg-fafafa">
                <td><?php echo e($account->name_represent()); ?>

                </td>
                <td><?php echo e($account->user->created_at); ?></td>
              </tbody>
          </table>
        </div>
        <h4><?php echo e(__("Account")); ?></h4>
        <div class="table-responsive mg-t-5">
          <table id="account_table" class="main-default-table col-md-12 table table-bordered">
              <thead>
                <tr>
                  <th scope="col"><?php echo e(__('Account Creation Date')); ?></th>
                  <th scope="col"><?php echo e(__('Account Number')); ?></th>
                  <th scope="col"><?php echo e(__('Account Type')); ?></th>
                  <th scope="col"><?php echo e(__('Currency')); ?></th>
                  <th scope="col"><?php echo e(__('Available Balance')); ?></th>
                  <th scope="col"><?php echo e(__('Current Balance')); ?></th>
                </tr>
              </thead>
              <tbody class="information-tbody bg-fafafa">
                <tr>
                  <td><?php echo e($account->created_at); ?></td>
                  <td><?php echo e($account->identifier); ?></td>
                  <td><?php echo e($account->acctype->name); ?></td>
                  <td><?php echo e($account->currency->ISOcode); ?></td>
                  <td><?php echo e($account->currency->getsymbol().number_format($account->balance(), 2, '.', ',')); ?></td>
                  <td><?php echo e($account->currency->getsymbol().number_format($account->currentbalance(), 2, '.', ',')); ?></td>
                </tr>
              </tbody>
          </table>
        </div>
        <h4><?php echo e(__("Transactions")); ?></h4>
        <div class="table-responsive mg-t-5">
          <table id="transactions_report" class="main-default-table col-md-12 table table-bordered">
              <thead>
                <tr>
                  <th scope="col"><?php echo e(__('Date / Time')); ?></th>
                  <th scope="col"><?php echo e(__('ID')); ?></th>
                  <th scope="col"><?php echo e(__('Transaction Description')); ?></th>
                  <th scope="col"><?php echo e(__('Debit')); ?></th>
                  <th scope="col"><?php echo e(__('Credit')); ?></th>
                  <th scope="col"><?php echo e(__('Available Balance')); ?></th>
                  <th scope="col"><?php echo e(__('Current Balance')); ?></th>
                  <th scope="col"><?php echo e(__('Status')); ?></th>
                </tr>
              </thead>
              <tbody class="information-tbody bg-fafafa">
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($t->created_at); ?></td>
                  <td><?php echo e($t->id); ?></td>
                  <td>
                    <?php if($t->deposit): ?>
                      <?php echo e(__("Deposit")); ?>

                    <?php else: ?>
                      <?php if($t->type == 0): ?>
                        <?php if($t->wireid == null): ?>
                        <?php echo e(__("Transfer to ")); ?>

                        <a href="<?php echo e(route('admin.accounts.view', $t->destinationtx()->account->id)); ?>">
                          "<?php echo e($t->destinationtx()->account->identifier); ?>"
                        </a>
                        <?php else: ?>
                          <?php echo e(__("Wire Transfer to ")); ?>

                          "<?php echo e($t->wire->_59_iban); ?>"
                        <?php endif; ?>
                      <?php else: ?>
                        <?php if($t->wireid == null): ?>
                        <?php echo e(__("Transfer from ")); ?>

                        <a href="<?php echo e(route('admin.accounts.view', $t->origintx->account->id)); ?>">
                          "<?php echo e($t->origintx->account->identifier); ?>"
                        </a>
                        <?php else: ?>
                          <?php echo e(__("Wire Transfer from ")); ?>

                          "<?php echo e($t->wire->_56a_iban); ?>"
                        <?php endif; ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if($t->type == 0): ?>
                    <?php echo e(number_format($t->value, 2, '.', ',')); ?>

                    <?php endif; ?> 
                  </td>
                  <td>
                    <?php if($t->type == 1): ?>
                    <?php echo e(number_format($t->value, 2, '.', ',')); ?>

                    <?php endif; ?>
                  </td>
                  <td><?php echo e(number_format($t->account->balance_at($t->created_at), 2, '.', ',')); ?></td>
                  <td><?php echo e(number_format($t->account->currentbalance_at($t->created_at), 2, '.', ',')); ?></td>
                  <td data-status="<?php echo e($t->status == 0 ? 'PENDING' : ($t->status == 1 ? 'EXECUTED' : 'CANCELED')); ?>" class="tx-center">
                    <?php if($t->status == 0): ?>
                    <i data-status="PENDING" class="si si-clock text-warning" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Pending')); ?>"></i>
                    <?php elseif($t->status == 1): ?>
                    <i data-status="EXECUTED" class="si si-check text-success" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Approved')); ?>"></i>
                    <?php elseif($t->status == 2): ?>
                    <i data-status="CANCELED" class="si si-close text-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Canceled')); ?>"></i>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
          </table>
          <div class="float-right">
              <?php echo e($transactions->links('vendor.pagination.bootstrap-4')); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js" integrity="sha512-P3z5YHtqjIxRAu1AjkWiIPWmMwO9jApnCMsa5s0UTgiDDEjTBjgEqRK0Wn0Uo8Ku3IDa1oer1CIBpTWAvqbmCA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/admin/accounts/view.blade.php ENDPATH**/ ?>