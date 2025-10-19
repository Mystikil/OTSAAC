<?php use function App\csrf_token; ?>
<section class="wizard">
    <h2>Finalize Setup</h2>
    <p>We are ready to write your configuration files.</p>
    <form method="post" action="/setup/finish">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <button type="submit" class="btn btn-success">Write Config & Complete</button>
    </form>
</section>
