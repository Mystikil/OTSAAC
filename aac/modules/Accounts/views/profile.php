<h1>Your Profile</h1>
<p>Username: <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></p>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>"></label>
    <button type="submit">Update</button>
</form>
