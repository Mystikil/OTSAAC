<?php use function App\csrf_token; ?>
<h1>Register</h1>
<form method="post" action="/register">
    <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Username <input type="text" name="username" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit" class="btn btn-primary">Create Account</button>
</form>
