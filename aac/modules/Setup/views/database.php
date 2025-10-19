<?php use function App\csrf_token; ?>
<section class="wizard">
    <h2>Database Configuration</h2>
    <form method="post" action="/setup/database">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>Host <input type="text" name="db_host" value="127.0.0.1" required></label>
        <label>Port <input type="number" name="db_port" value="3306" required></label>
        <label>User <input type="text" name="db_user" value="root" required></label>
        <label>Password <input type="password" name="db_pass"></label>
        <label>Database Name <input type="text" name="db_name" value="aac" required></label>
        <label>Table Prefix <input type="text" name="db_prefix" value=""></label>
        <label>Schema Version
            <select name="schema_version">
                <option value="1098">10.98</option>
                <option value="860">8.60</option>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Run Migrations</button>
    </form>
</section>
