<h1>Register</h1>
<form method="post" action="/register">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Email <input type="email" name="email" required></label>
    <label>Username <input type="text" name="username" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Create Account</button>
</form>
<p><a href="/login">Already registered?</a></p>
