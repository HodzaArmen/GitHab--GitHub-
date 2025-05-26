<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>commit?repo_id=<?= $repo['id'] ?>">Commits</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= substr($commit['hash'], 0, 7) ?></li>
        </ol>
    </nav>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= htmlspecialchars($commit['message']) ?></h5>
                <span class="badge bg-secondary"><?= substr($commit['hash'], 0, 7) ?></span>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    <img src="https://via.placeholder.com/40" alt="Avatar" class="rounded-circle">
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong><?= htmlspecialchars($commit['username']) ?></strong>
                    <div class="text-muted">committed <?= ViewHelper::timeElapsed($commit['created_at']) ?></div>
                </div>
            </div>
            
            <div class="mb-3">
                <span class="badge bg-info">Branch: <?= htmlspecialchars($branch['name']) ?></span>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6>Changes</h6>
                </div>
                <div class="card-body">
                    <p>File changes would be displayed here in a real application.</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($canEdit): ?>
        <div class="card">
            <div class="card-header">
                <h5>Create a new commit based on this one</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>commit/create" method="post">
                    <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                    <input type="hidden" name="branch_id" value="<?= $branch['id'] ?>">
                    <input type="hidden" name="parent_commit_id" value="<?= $commit['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Commit message</label>
                        <input type="text" class="form-control" id="message" name="message" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create new commit</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>