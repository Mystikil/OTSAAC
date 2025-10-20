<section class="setup">
    <h1>Welcome to the AAC Setup Wizard</h1>
    <form method="post" action="/setup">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <label><input type="checkbox" name="agree" required> I accept the end-user license agreement.</label>
        <button type="submit">Start Setup</button>
    </form>
</section>
