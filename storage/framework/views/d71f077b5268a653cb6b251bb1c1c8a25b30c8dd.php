

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h4 class="mt-4">History</h4>
        <div class="history-list" style="max-height: 500px; overflow-y: auto;">
            <?php
                $unread = $notifications->filter(function ($n) {
                    return is_null($n->read_at);
                });
            ?>
            <?php if($unread->isEmpty()): ?>
                <p class="text-center">No History Avaiable.</p>
            <?php else: ?>
                <?php $__currentLoopData = $unread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('notifications.read', $notification->id)); ?>" class="text-decoration-none">
                        <div class="history-item mb-3 p-3 border rounded">
                            <h6><?php echo e($notification->data['messages']); ?></h6>
                            <p class="mb-1 text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                            <small class="text-danger">Unread</small>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <!-- Gunakan tampilan pagination kustom -->
        <div class="d-flex justify-content-center mt-3">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo e($notifications->onFirstPage() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($notifications->previousPageUrl()); ?>" tabindex="-1">Previous</a>
                    </li>
                    <?php for($i = 1; $i <= $notifications->lastPage(); $i++): ?>
                        <li class="page-item <?php echo e($notifications->currentPage() == $i ? 'active' : ''); ?>">
                            <a class="page-link" href="<?php echo e($notifications->url($i)); ?>"><?php echo e($i); ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo e(!$notifications->hasMorePages() ? 'disabled' : ''); ?>">
                        <a class="page-link" href="<?php echo e($notifications->nextPageUrl()); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/historinotif/index.blade.php ENDPATH**/ ?>