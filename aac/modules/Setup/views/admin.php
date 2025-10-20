<h2>Create Admin Account</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Username <input type="text" name="username" required></label>
    <label>Password <input type="password" name="password" required></label>
    <label><input type="checkbox" name="two_factor"> Enable Two-Factor Authentication (configure later)</label>
    <button type="submit">Continue</button>
</form>
