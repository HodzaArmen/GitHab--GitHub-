<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Repositories</h2>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="<?= BASE_URL ?>dashboard" class="btn btn-primary">Back to Dashboard</a>
            <a href="<?= BASE_URL ?>repo/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New repository
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION["user_id"]) && !empty($userRepos)): ?>
        <div class="mb-5">
            <h4>Your repositories</h4>
            <div class="list-group">
                <?php foreach ($userRepos as $repo): ?>
                    <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></h5>
                            <small class="text-muted">Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($repo['description']) ?></p>
                        <small class="text-muted">
                            <?php if (!$repo['is_public']): ?>
                                <span class="badge bg-secondary">Private</span>
                            <?php endif; ?>
                        </small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($starredRepos)): ?>
        <div class="mb-5">
            <h4>Starred repositories</h4>
            <div class="list-group">
                <?php foreach ($starredRepos as $repo): ?>
                    <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></h5>
                            <small class="text-muted">Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($repo['description']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($publicRepos)): ?>
        <div class="mb-5">
            <h4>Public repositories</h4>
            <div class="list-group">
                <?php foreach ($publicRepos as $repo): ?>
                    <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></h5>
                            <small class="text-muted">Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($repo['description']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No public repositories found.</div>
    <?php endif; ?>
</div>