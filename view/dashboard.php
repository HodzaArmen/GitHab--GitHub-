<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login");
    exit();
}
?>

<div class="container mt-4">
    <h2>Your Dashboard</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>logout" class="btn btn-danger">Logout</a>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Repositories</h5>
                    <p class="card-text">Manage your code repositories</p>
                    <a href="<?= BASE_URL ?>repo" class="btn btn-outline-primary">View all</a>
                    <a href="<?= BASE_URL ?>repo/create" class="btn btn-primary">New repository</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Profile</h5>
                    <p class="card-text">Manage your account settings</p>
                    <a href="<?= BASE_URL ?>user/profile?id=<?= $_SESSION['user_id'] ?>" class="btn btn-outline-primary">View profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text">View all users</p>
                    <a href="<?= BASE_URL ?>user" class="btn btn-outline-primary">View all users</a>
                </div>
            </div>
        </div>
    </div>
</div>