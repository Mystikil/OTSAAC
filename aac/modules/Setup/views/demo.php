<h2>Demo Content</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label><input type="checkbox" name="load_demo"> Load full demo data (recommended for exploration)</label>
    <button type="submit">Finish Setup</button>
</form>
