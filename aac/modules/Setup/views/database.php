<h2>Database Configuration</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Host <input type="text" name="host" value="127.0.0.1" required></label>
    <label>Port <input type="text" name="port" value="3306" required></label>
    <label>User <input type="text" name="user" value="root" required></label>
    <label>Password <input type="password" name="pass"></label>
    <label>Database <input type="text" name="dbname" value="aac" required></label>
    <label>Table Prefix <input type="text" name="table_prefix" value="aac_"></label>
    <label>Game Schema Version
        <select name="schema_version">
            <?php foreach ($schemas as $value => $label): ?>
                <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Continue</button>
</form>
