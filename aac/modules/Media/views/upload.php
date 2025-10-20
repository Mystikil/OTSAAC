<h1>Submit Media</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Title <input type="text" name="title" required></label>
    <label>Video URL <input type="url" name="url" placeholder="https://"></label>
    <button type="submit">Submit</button>
</form>
