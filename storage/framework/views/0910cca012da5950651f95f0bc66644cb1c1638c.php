<thead>
    <tr>
        <th scope="col"><?php echo e(__('Description')); ?></th>
        <th scope="col"><?php echo e(__('Value')); ?></th>
        <th scope="col"><?php echo e(__('Fee')); ?></th>
        <th scope="col"><?php echo e(__('DateTime')); ?></th>
        <th scope="col" class="wd-5p row-data-view no-sort btn-col border-t-r-0"></th>
        <th scope="col" class="wd-5p row-data-view no-sort btn-col border-t-r-0"></th>
    </tr>
</thead>
<tbody class="bg-fafafa">
    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($transaction->wireid == null): ?>
    <tr data-txid="<?php echo e($transaction->id); ?>">
        <td>
            <?php if($transaction->originid == null && $transaction->destuserid == null): ?>
                <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>"><?php echo e($transaction->account->identifier); ?></a>
                <span class="color-a7a7a7 pd-lr-5 fa fa-angle-left"></span>
                <span><?php echo e(__("Deposit")); ?></span>
            <?php else: ?>
                <?php if($transaction->type == 0): ?>
                    <?php if($transaction->user_id == $transaction->destuserid): ?>
                        <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                            <?php echo e($transaction->account->identifier); ?>

                        </a>
                        <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                        <a href="<?php echo e(route('user.account.view', $transaction->destinationtx()->account->id)); ?>">
                            <?php echo e($transaction->destinationtx()->account->identifier); ?>

                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                            <?php echo e($transaction->account->identifier); ?>

                        </a>
                        <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e($transaction->destinationtx()->account->identifier); ?>">
                            <?php echo e($transaction->destinationtx()->user->firstname." ".$transaction->destinationtx()->user->lastname.".".$transaction->destinationtx()->account->identifier); ?>

                        </span>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>"><?php echo e($transaction->account->identifier); ?></a>
                    <span class="color-a7a7a7 pd-lr-5 fa fa-angle-left"></span>
                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e($transaction->origintx->account->identifier); ?>"><?php echo e($transaction->origintx->user->firstname." ".$transaction->origintx->user->lastname.".".$transaction->origintx->account->identifier); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if($transaction->originid == null && $transaction->destuserid == null): ?>
                <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

            <?php else: ?>
                <?php if($transaction->type == 0): ?>
                    <?php if($transaction->destinationtx()->currency()->ISOcode == $transaction->currency()->ISOcode): ?>
                    <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                    <?php else: ?>
                    <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                    <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                    <?php echo e($transaction->destinationtx()->currencycode().number_format($transaction->destinationtx()->value, 2, '.', ',')); ?>

                    <?php endif; ?>
                <?php else: ?>
                    <?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?>

                <?php endif; ?>
            <?php endif; ?>
        </td>
        <td><?php echo e($transaction->currencycode().number_format($transaction->feevalue(), 2, '.', ',')); ?></td>
        <td>
          <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e($transaction->created_at); ?>">
            <?php echo e($transaction->created_at->diffForHumans()); ?>

          </span>
        </td>
        <td class="tx-center">
            <?php if($transaction->originid == null && $transaction->destuserid == null): ?>
                <span class="iconify" data-icon="carbon:certificate-check" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Deposit')); ?>"></span>
            <?php else: ?>
                <?php if($transaction->type == 0): ?>
                    <?php if($transaction->user_id == $transaction->destinationtx()->user_id): ?>
                    <span class="iconify" data-icon="carbon:arrows-horizontal" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Between My Accounts')); ?>"></span>
                    <?php else: ?>
                    <span class="iconify" data-icon="carbon:arrow-up-right" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('DEBIT')); ?>"></span>
                    <?php endif; ?>
                <?php else: ?>
                <span class="iconify" data-icon="carbon:arrow-down-left" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('CREDIT')); ?>"></span>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <td class="tx-center">
            <?php if($transaction->status == 0): ?>
            <i class="si si-clock text-warning" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Pending')); ?>"></i>
            <?php elseif($transaction->status == 1): ?>
            <i class="si si-check text-success" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Approved')); ?>"></i>
            <?php elseif($transaction->status == 2): ?>
            <i class="si si-close text-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Canceled')); ?>"></i>
            <?php endif; ?>
        </td>
    </tr>
    <?php else: ?>
    <tr data-txid="<?php echo e($transaction->id); ?>">
        <td>
            <?php if($transaction->type == 0): ?>
                <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                    <?php echo e($transaction->account->identifier); ?>

                </a>
                <span class="color-a7a7a7 pd-lr-5 fa fa-angle-right"></span>
                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e('('.$transaction->wire->_57a_name.') '.$transaction->wire->_59_iban); ?>">
                    <?php echo e(empty($transaction->wire->_59_name) ? "NaN" : $transaction->wire->_59_name); ?>.<?php echo e(empty($transaction->wire->_59_iban) ? "NaN" : $transaction->wire->_59_iban); ?>

                </span>
            <?php else: ?>
                <a href="<?php echo e(route('user.account.view', $transaction->account->id)); ?>">
                    <?php echo e($transaction->account->identifier); ?>

                </a>
                <span class="color-a7a7a7 pd-lr-5 fa fa-angle-left"></span>
                <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e('('.$transaction->wire->_56a_name.') '.$transaction->wire->_56a_iban); ?>">
                    <?php echo e(empty($transaction->wire->_56a_name) ? "NaN" : $transaction->wire->_56a_name); ?>.<?php echo e(empty($transaction->wire->_56a_iban) ? "NaN" : $transaction->wire->_56a_iban); ?>

                </span>
            <?php endif; ?>
            <span data-toggle="tooltip" data-placement="top" title="<?php echo e(__('WIRE')); ?>" class="iconify valign-middle color-d0 mg-l-10" data-icon="carbon:license-global" data-width="20" data-height="20"></span>
        </td>
        <td><?php echo e($transaction->currencycode().number_format($transaction->value, 2, '.', ',')); ?></td>
        <td><?php echo e($transaction->currencycode().number_format($transaction->feevalue(), 2, '.', ',')); ?></td>
        <td>
          <span data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e($transaction->created_at); ?>">
            <?php echo e($transaction->created_at->diffForHumans()); ?>

          </span>
        </td>
        <td class="tx-center">
            <?php if($transaction->type == 0): ?>
            <span class="iconify" data-icon="carbon:arrow-up-right" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Outgoing Wire')); ?>"></span>
            <?php else: ?>
            <span class="iconify" data-icon="carbon:arrow-down-left" data-width="16" data-height="16" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Incoming Wire')); ?>"></span>
            <?php endif; ?>
        </td>
        <td class="tx-center">
            <?php if($transaction->status == 0): ?>
            <i class="si si-clock text-warning" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Pending')); ?>"></i>
            <?php elseif($transaction->status == 1): ?>
            <i class="si si-check text-success" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Approved')); ?>"></i>
            <?php elseif($transaction->status == 2): ?>
            <i class="si si-close text-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="<?php echo e(__('Canceled')); ?>"></i>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody><?php /**PATH C:\Users\Yacin\Downloads\Laravelle\intialClone\ebanqmbi\script\resources\views/user/transaction/transactions.blade.php ENDPATH**/ ?>