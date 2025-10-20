<h1>Login</h1>
<form method="post" action="/login">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Login</button>
</form>
<p><a href="/register">Need an account?</a></p>
