<?php use function App\csrf_token; ?>
<section class="wizard">
    <h2>Demo Content</h2>
    <form method="post" action="/setup/demo">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label><input type="checkbox" name="load_demo" value="1"> Load full demo data (users, characters, guilds, market, PvP, media references)</label>
        <button type="submit" class="btn btn-primary">Continue</button>
    </form>
</section>
