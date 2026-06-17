<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Profile Information</h5>

        <table class="table table-borderless" style="width: auto;">
            <tr>
                <td style="color:#4154f1; width:200px;"><strong>Username</strong></td>
                <td><?= session()->get('username') ?>
                    <span class="badge bg-danger ms-1"><?= session()->get('role') ?></span>
                </td>
            </tr>
            <tr>
                <td style="color:#4154f1;"><strong>Email</strong></td>
                <td>
                    <a href="mailto:<?= session()->get('email') ?>">
                        <?= session()->get('email') ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td style="color:#4154f1;"><strong>Login Time</strong></td>
                <td><?= session()->get('login_time') ?></td>
            </tr>
            <tr>
                <td style="color:#4154f1;"><strong>Status</strong></td>
                <td>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Sudah Login
                    </span>
                </td>
            </tr>
        </table>

    </div>
</div>

<?= $this->endSection() ?>