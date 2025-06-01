<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>user/profile?id=<?= $repo['user_id'] ?>"><?= htmlspecialchars($repo['username']) ?></a></li>
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

            <?php if ($canEdit): ?>
                <form action="<?= BASE_URL ?>repo/delete" method="post" class="ms-2">
                    <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this repository?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <p class="lead"><?= htmlspecialchars($repo['description']) ?></p>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="#">Code</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>">Commits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>">Issues</a>
        </li>
    </ul>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="branchDropdown" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($branches[0]['name']) ?>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($branches as $branch): ?>
                        <li><a class="dropdown-item" href="#"><?= htmlspecialchars($branch['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <h5>Files:</h5>
            <?php if ($canEdit): ?>
                <a href="<?= BASE_URL ?>file/new?repo_id=<?= $repo["id"] ?>" class="btn btn-sm btn-primary mb-2">+ New File</a>
            <?php endif; ?>
            <?php if (!empty($files)): ?>
                <ul>
                    <?php foreach ($files as $f): ?>
                        <li>
                            <a href="<?= BASE_URL ?>file/view?repo_id=<?= $repo["id"] ?>&file=<?= urlencode($f["file_path"]) ?>">
                                <?= htmlspecialchars($f["file_name"]) ?></a>
                            <?php if ($canEdit): ?>
                                (<a href="<?= BASE_URL ?>file/edit?repo_id=<?= $repo["id"] ?>&file=<?= urlencode($f["file_path"]) ?>">Edit</a>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No files in this repository.</p>
            <?php endif; ?>
        </div>
    </div>

    <h4>Recent Commits</h4>
    <div class="list-group mb-4">
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
            <a href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>" class="list-group-item list-group-item-action text-center">
                View all commits
            </a>
        <?php else: ?>
            <div class="list-group-item">No commits yet</div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>About</h5>
                </div>
                <div class="card-body">
                    <p><i class="bi bi-calendar"></i> Created <?= date('M j, Y', strtotime($repo['created_at'])) ?></p>
                    <p><i class="bi bi-arrow-repeat"></i> Updated <?= ViewHelper::timeElapsed($repo['updated_at']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Issues</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($issues)): ?>
                        <ul class="list-unstyled">
                            <?php foreach ($issues as $issue): ?>
                                <li class="mb-2">
                                    <a href="<?= BASE_URL ?>issue/detail?id=<?= $issue['id'] ?>">
                                        #<?= $issue['id'] ?> <?= htmlspecialchars($issue['title']) ?>
                                    </a>
                                    <span class="badge <?= $issue['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($issue['status']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                            View all issues
                        </a>
                    <?php else: ?>
                        <p>No issues yet</p>
                        <a href="<?= BASE_URL ?>issue/create?repo_id=<?= $repo['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                            Create first issue
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>