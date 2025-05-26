<div class="container mt-4">
    <h1>Starred Repositories</h1>

    <?php if (!empty($starredRepos)): ?>
        <div class="list-group">
            <?php foreach ($starredRepos as $repo): ?>
                <a href="<?= htmlspecialchars(BASE_URL . "repo/detail?id=" . $repo['id']) ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></h5>
                        <small class="text-muted">Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></small>
                    </div>
                    <p class="mb-1"><?= htmlspecialchars($repo['description']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?= $isCurrentUser ? 'You have' : htmlspecialchars($user['username']) . ' has' ?> no starred repositories.
        </div>
    <?php endif; ?>
</div>