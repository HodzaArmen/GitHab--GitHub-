<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>"><?= htmlspecialchars($repo['username']) ?>/<?= htmlspecialchars($repo['name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Create commit</li>
        </ol>
    </nav>
    
    <h2>Create a new commit</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>commit/create" method="post">
        <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
        
        <div class="mb-3">
            <label for="branch_id" class="form-label">Branch</label>
            <select class="form-select" id="branch_id" name="branch_id" required>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="message" class="form-label">Commit message</label>
            <input type="text" class="form-control" id="message" name="message" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Files to commit</label>
            <div class="card">
                <div class="card-body">
                    <p>File changes would be displayed here in a real application.</p>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Commit changes</button>
        <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>