<h1>Edit file: <?= htmlspecialchars($file_name) ?></h1>

<form method="post" action="?action=file/edit">
    <input type="hidden" name="repo_id" value="<?= htmlspecialchars($repo_id) ?>">
    <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
    <textarea name="content" rows="30" cols="100" style="font-family: monospace;"><?= htmlspecialchars(file_get_contents($file)) ?></textarea><br>
    <label for="message">Commit message:</label><br>
    <input type="text" name="message" id="message" required><br><br>
    <button type="submit">Save changes</button>
</form>
