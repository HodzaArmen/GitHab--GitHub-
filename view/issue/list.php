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

            <form action="<?= BASE_URL ?>repo/star" method="post" class="ms-2">
                <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                <input type="hidden" name="action" value="<?= $isStarred ? 'unstar' : 'star' ?>">
                <button type="submit" class="btn btn-sm <?= $isStarred ? 'btn-warning' : 'btn-outline-warning' ?> star-button">
                    <i class="bi bi-star-fill"></i> <?= $isStarred ? 'Starred' : 'Star' ?> (<?= $starCount ?>)
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
            <a class="nav-link" href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>">Commits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Issues</a>
        </li>
    </ul>
    <div class="d-flex justify-content-between align-items-center mb-4">        
        <?php if ($canEdit): ?>
            <a href="<?= BASE_URL ?>issue/create?repo_id=<?= $repo['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New issue
            </a>
        <?php endif; ?>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link <?= $status === 'open' ? 'active' : '' ?>" 
                       href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>&status=open">
                        Open
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $status === 'closed' ? 'active' : '' ?>" 
                       href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>&status=closed">
                        Closed
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <?php if (!empty($issues)): ?>
                <div class="list-group">
                    <?php foreach ($issues as $issue): ?>
                        <a href="<?= BASE_URL ?>issue/detail?id=<?= $issue['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    <span class="badge <?= $issue['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?> me-2">
                                        <?= ucfirst($issue['status']) ?>
                                    </span>
                                    <?= htmlspecialchars($issue['title']) ?>
                                </h5>
                                <small><?= ViewHelper::timeElapsed($issue['created_at']) ?></small>
                            </div>
                            <p class="mb-1">#<?= $issue['id'] ?> opened by <?= htmlspecialchars($issue['author_name']) ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No <?= $status ?> issues found.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>