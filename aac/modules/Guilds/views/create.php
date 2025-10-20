<h1>Create Guild</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Name <input type="text" name="name" required></label>
    <label>MOTD <input type="text" name="motd"></label>
    <button type="submit">Create</button>
</form>
