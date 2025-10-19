<?php use function App\csrf_token; ?>
<h1>Account</h1>
<section>
    <h2>Profile</h2>
    <form method="post" action="/account/profile">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>Email <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required></label>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</section>
<section>
    <h2>Security</h2>
    <form method="post" action="/account/security">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>New Password <input type="password" name="password" required></label>
        <button type="submit" class="btn btn-secondary">Update Password</button>
    </form>
</section>
