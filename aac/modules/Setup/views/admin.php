<?php use function App\csrf_token; ?>
<section class="wizard">
    <h2>Create Administrator Account</h2>
    <p>Passwords must contain at least 8 characters.</p>
    <form method="post" action="/setup/admin">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>Email <input type="email" name="email" required></label>
        <label>Username <input type="text" name="username" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit" class="btn btn-primary">Create Admin</button>
    </form>
</section>
