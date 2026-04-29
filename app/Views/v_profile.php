<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Profile Information</h6>
        <table class="table table-borderless">
            <tr>
                <td class="text-primary fw-semibold" width="130">Username</td>
                <td>
                    <?= esc($username) ?>
                    <span class="badge bg-danger ms-1"><?= esc($role) ?></span>
                </td>
            </tr>
            <tr>
                <td class="text-primary fw-semibold">Email</td>
                <td><a href="mailto:<?= esc($email) ?>"><?= esc($email) ?></a></td>
            </tr>
            <tr>
                <td class="text-primary fw-semibold">Login Time</td>
                <td><?= esc($login_time) ?></td>
            </tr>
            <tr>
                <td class="text-primary fw-semibold">Status</td>
                <td>
                    <?php if ($isLoggedIn): ?>
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Sudah Login
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">Belum Login</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<?= $this->endSection() ?>