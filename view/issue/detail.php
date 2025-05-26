<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>user/profile?id=<?= $repo['user_id'] ?>"><?= htmlspecialchars($repo['username']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>"><?= htmlspecialchars($repo['name']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>">Issues</a></li>
            <li class="breadcrumb-item active" aria-current="page">#<?= $issue['id'] ?></li>
        </ol>
    </nav>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <span class="badge <?= $issue['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?> me-2">
                        <?= ucfirst($issue['status']) ?>
                    </span>
                    <?= htmlspecialchars($issue['title']) ?>
                </h3>
                
                <?php if ($canEdit): ?>
                    <form action="<?= BASE_URL ?>issue/update-status" method="post">
                        <input type="hidden" name="id" value="<?= $issue['id'] ?>">
                        <input type="hidden" name="status" value="<?= $issue['status'] === 'open' ? 'closed' : 'open' ?>">
                        <button type="submit" class="btn btn-sm <?= $issue['status'] === 'open' ? 'btn-danger' : 'btn-success' ?>">
                            <?= $issue['status'] === 'open' ? 'Close' : 'Reopen' ?> issue
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <img src="https://via.placeholder.com/40" alt="Avatar" class="rounded-circle">
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong><?= htmlspecialchars($issue['author_name']) ?></strong>
                    <div class="text-muted">opened this issue <?= ViewHelper::timeElapsed($issue['created_at']) ?></div>
                </div>
            </div>
            
            <div class="issue-content mb-5">
                <?= nl2br(htmlspecialchars($issue['description'])) ?>
            </div>
            
            <hr>
            
            <h5 class="mb-3">Comments</h5>
            
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <img src="https://via.placeholder.com/40" alt="Avatar" class="rounded-circle">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <strong><?= htmlspecialchars($comment['author_name']) ?></strong>
                            <div class="text-muted">commented <?= ViewHelper::timeElapsed($comment['created_at']) ?></div>
                            <div class="mt-2">
                                <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">No comments yet.</div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION["user_id"])): ?>
                <form action="<?= BASE_URL ?>issue/comment" method="post">
                    <input type="hidden" name="issue_id" value="<?= $issue['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">Leave a comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Comment</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info">
                    <a href="<?= BASE_URL ?>login">Sign in</a> to leave a comment.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>