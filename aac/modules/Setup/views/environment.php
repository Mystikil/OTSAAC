<h2>Environment Settings</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Site Name <input type="text" name="site_name" required></label>
    <label>Timezone <input type="text" name="timezone" value="UTC" required></label>
    <label>Base URL <input type="text" name="base_url" value=""></label>
    <label>Theme
        <select name="layout">
            <option value="default">Default</option>
            <option value="warzone">Warzone</option>
        </select>
    </label>
    <button type="submit">Continue</button>
</form>
