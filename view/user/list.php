<div class="container mt-4">
    <h2>Users</h2>
    
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search users...">
    </div>
    
    <div class="list-group">
        <?php foreach ($users as $user): ?>
            <a href="<?= htmlspecialchars(BASE_URL . "user/profile?id=" . $user['id']) ?>" class="list-group-item list-group-item-action">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/40" alt="Avatar" class="rounded-circle me-3">
                    <div>
                        <h5 class="mb-0"><?= htmlspecialchars($user['username']) ?></h5>
                        <?php if (!empty($user['full_name'])): ?>
                            <small class="text-muted"><?= htmlspecialchars($user['full_name']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>