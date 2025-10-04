<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= esc($title ?? 'MBG App') ?></title>
    <!-- bootstrap CDN  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="bi bi-egg-fried"></i>
                <i class="bi-cup"></i> MBG
            </a>
            <div>
                <?php if (session()->get('isLoggedIn')): ?>
                    <?php
                    $role = session()->get('role');
                    $roleClass = [
                    'gudang' => 'bg-primary',
                    'dapur' => 'bg-success',
                    ];
                    ?>
                    <span class="me-2 ms-auto">
                        Halo, <?= esc(session()->get('user_name')) ?>
                        <span class="badge <?= $roleClass[$role] ?? 'bg-secondary' ?>"><?= esc($role) ?></span>
                    </span>
                    </span>
                    <a class="btn btn-sm btn-outline-secondary" href="/logout">Logout</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-primary" href="/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>