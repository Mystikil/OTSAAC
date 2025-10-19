<?php use function App\csrf_token; ?>
<section class="wizard">
    <h1>Welcome to the AAC Setup Wizard</h1>
    <p>Please review the license agreement before continuing.</p>
    <div class="eula-box">
        <!-- placeholder for EULA content -->
        <p>By continuing you confirm that you are authorized to deploy this software, will configure it responsibly, and will com
ply with all applicable laws.</p>
    </div>
    <form method="post" action="/setup/eula">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label><input type="checkbox" name="accept" value="1" required> I agree to the terms.</label>
        <button type="submit" class="btn btn-primary">Continue</button>
    </form>
</section>
