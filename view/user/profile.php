<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?= htmlspecialchars($user['username']) ?></h3>
                    <?php if (!empty($user['avatar_url'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>assets/images/default-avatar.png" alt="Default Avatar" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <?php endif; ?>
                    <?php if (!empty($user['bio'])): ?>
                        <p class="text-muted"><?= htmlspecialchars($user['bio']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($user['full_name'])): ?>
                        <p class="text-muted"><?= htmlspecialchars($user['full_name']) ?></p>
                    <?php endif; ?>

                    <?php if ($isCurrentUser): ?>
                        <a href="<?= BASE_URL ?>user/edit" class="btn btn-outline-secondary btn-sm">Edit profile</a>
                    <?php endif; ?>
                    <div class="mt-3">
                        <p class="mb-1"><strong>Repositories:</strong> <?= count($repos) ?></p>
                        <p class="mb-1"><strong>Stars:</strong> <?= count($starredRepos) ?></p>
                        <p class="mb-1"><strong>Joined:</strong> <?= ViewHelper::timeElapsed($user['created_at']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h4>Repositories</h4>
            <?php if (!empty($repos)): ?>
                <div class="list-group">
                    <?php foreach ($repos as $repo): ?>
                        <?php if ($repo['is_public'] == 1 || $isCurrentUser): ?>
                            <a href="<?= htmlspecialchars(BASE_URL . "repo/detail?id=" . $repo['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= htmlspecialchars($repo['name']) ?></h5>
                                    <small class="text-muted">Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></small>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($repo['description']) ?></p>
                                <small class="text-muted">
                                    <?php if (empty($repo['is_public']) || $repo['is_public'] == 0): ?>
                                        <span class="badge bg-secondary">Private</span>
                                    <?php endif; ?>
                                </small>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <?= $isCurrentUser ? 'You have' : htmlspecialchars($user['username']) . ' has' ?> no repositories yet.
                </div>
            <?php endif; ?>

            <?php if (!empty($starredRepos)): ?>
                <h4 class="mt-5">Starred repositories</h4>
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
            <?php endif; ?>
        </div>
    </div>
</div>