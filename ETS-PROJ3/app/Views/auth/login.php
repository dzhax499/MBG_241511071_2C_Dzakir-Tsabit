<div class="row justify-content-center">
    <div class="col-md-6">
        <h3>Login</h3>
        <form method="post" action="/auth/attempt">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Login</button>
        </form>

        <?php if (isset($errors) && is_array($errors)): ?>
            <div class="mt-3">
                <?php foreach ($errors as $err): ?>
                    <div class="text-danger"><?= esc($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>