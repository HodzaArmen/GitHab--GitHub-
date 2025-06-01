<div class="container mt-4">
    <h2>Users</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Joined</th>
                <th>Repositories</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?php if ($user['avatar_url']): ?>
                            <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="rounded-circle" width="50">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/50" alt="Placeholder Avatar" class="rounded-circle" width="50">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>user/profile?id=<?= htmlspecialchars($user['id']) ?>">
                            <?= htmlspecialchars($user['username']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($user['full_name'] ?? '') ?></td> <!-- Assuming you have a full_name field -->
                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($user['created_at']))) ?></td> <!-- Assuming you have a created_at field -->
                    <td><?= htmlspecialchars($user['repo_count'] ?? 0) ?></td> <!-- Assuming you have a repo_count field -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>