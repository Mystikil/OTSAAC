<?php use function App\csrf_token; ?>
<h1>Login</h1>
<form method="post" action="/login">
    <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
