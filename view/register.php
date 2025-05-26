<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Create your account</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>register" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($username ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full name (optional)</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= htmlspecialchars($full_name ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Make sure it's at least 8 characters including a number and a lowercase letter.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign up for GitHab</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="<?= BASE_URL ?>login">Sign in</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>