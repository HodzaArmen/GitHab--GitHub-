<div class="container mt-5">
    <h2>New file in <?= htmlspecialchars($repo["name"]) ?></h2>

    <form method="post" action="<?= BASE_URL ?>file/new" enctype="multipart/form-data">
        <input type="hidden" name="repo_id" value="<?= $repo["id"] ?>">

        <div class="mb-3">
            <label for="fileupload" class="form-label">Choose a file to upload</label>
            <input type="file" class="form-control" id="file" name="files[]" multiple required>
        </div>

        <input type="text" name="message" class="form-control mb-3" placeholder="Commit message (e.g. 'Upload file')" required>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
