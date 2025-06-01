<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item">
                <a href="<?= BASE_URL ?>user/profile?id=<?= $repo['user_id'] ?>">
                    <?= htmlspecialchars($repo['username']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($repo['name']) ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>
            <span class="text-muted"><?= htmlspecialchars($repo['username']) ?>/</span>
            <?= htmlspecialchars($repo['name']) ?>
            <span class="badge <?= $repo['is_public'] ? 'bg-success' : 'bg-secondary' ?>">
                <?= $repo['is_public'] ? 'Public' : 'Private' ?>
            </span>
        </h1>

        <div class="btn-group">
            <?php if ($canEdit): ?>
                <a href="<?= BASE_URL ?>repo/edit?id=<?= $repo['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <?php endif; ?>

            <form method="post" class="star-form ms-2">
                <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                <input type="hidden" name="action" value="<?= $isStarred ? 'unstar' : 'star' ?>">
                <button type="button" class="btn btn-sm <?= $isStarred ? 'btn-warning' : 'btn-outline-warning' ?> star-button">
                    <i class="bi bi-star-fill"></i> <?= $isStarred ? 'Starred' : 'Star' ?> (<span class="star-count"><?= $starCount ?></span>)
                </button>
            </form>

            <form action="<?= BASE_URL ?>repo/fork" method="post" class="ms-2">
                <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-diagram-2"></i> Fork (<?= $forkCount ?>)
                </button>
            </form>
        </div>
    </div>

    <p class="lead"><?= htmlspecialchars($repo['description']) ?></p>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>">Code</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>">Commits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>">Issues</a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="branchDropdown" data-bs-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?= htmlspecialchars($currentBranch) ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="branchDropdown">
                <?php foreach ($branches as $branch): ?>
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>&branch=<?= urlencode($branch['name']) ?>">
                            <?= htmlspecialchars($branch['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if ($canEdit): ?>
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= BASE_URL ?>commit/create" method="post">
                    <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                    <input type="hidden" name="branch_id" value="<?= $currentBranchId ?>">

                    <div class="mb-3">
                        <label for="message" class="form-label">Commit message</label>
                        <input type="text" class="form-control" id="message" name="message" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create commit</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="list-group">
        <?php if (!empty($commits)): ?>
            <?php foreach ($commits as $commit): ?>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><?= htmlspecialchars($commit['message']) ?></h6>
                        <small><?= ViewHelper::timeElapsed($commit['created_at']) ?></small>
                    </div>
                    <small class="text-muted">
                        <a href="<?= BASE_URL ?>user/profile?id=<?= $commit['user_id'] ?>">
                            <?= htmlspecialchars($commit['username']) ?>
                        </a> committed to <?= htmlspecialchars($commit['branch_name']) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item">No commits in this branch yet</div>
        <?php endif; ?>
    </div>
</div>