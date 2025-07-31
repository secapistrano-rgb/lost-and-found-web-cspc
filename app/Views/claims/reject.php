<?php
/** @var array $claim */

if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session('errors') as $error): ?>
            <?= esc($error) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Reject Claim</h4>
                </div>
                <div class="card-body">
                    <form action="<?= route_to('claims.reject') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="claim_id" value="<?= esc($claim['id']) ?>">
                        

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">Reject Claim</button>
                            <a href="<?= route_to('claims.index') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
