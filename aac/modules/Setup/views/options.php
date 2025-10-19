<?php use function App\csrf_token; ?>
<?php
$defaults = $_SESSION['setup']['environment'] ?? [];
$appConfig = $config['app'] ?? [];
$selectedFeatures = $defaults['features'] ?? [];
?>
<section class="wizard">
    <h2>Site Options</h2>
    <p>Configure how your portal should appear and which modules should be enabled by default.</p>
    <form method="post" action="/setup/options">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>Site Name <input type="text" name="site_name" value="<?= htmlspecialchars($defaults['site_name'] ?? $appConfig['site_name'] ?? 'AAC', ENT_QUOTES, 'UTF-8'); ?>" required></label>
        <label>Timezone <input type="text" name="timezone" value="<?= htmlspecialchars($defaults['timezone'] ?? $appConfig['timezone'] ?? 'UTC', ENT_QUOTES, 'UTF-8'); ?>" required></label>
        <label>Base URL <input type="url" name="base_url" value="<?= htmlspecialchars($defaults['base_url'] ?? $appConfig['base_url'] ?? 'http://localhost/aac/public', ENT_QUOTES, 'UTF-8'); ?>" required></label>
        <label>Theme
            <select name="layout">
                <?php foreach (['default', 'warzone'] as $theme): ?>
                    <option value="<?= $theme; ?>" <?= (($defaults['layout'] ?? $appConfig['layout'] ?? 'default') === $theme) ? 'selected' : ''; ?>><?= ucfirst($theme); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <fieldset>
            <legend>Features</legend>
            <?php foreach (['Market','CharacterTrade','Media','PvP','GuildWars','EmailVerification','Captcha','TwoFactorAuth'] as $feature): ?>
                <label><input type="checkbox" name="features[]" value="<?= $feature; ?>" <?= in_array($feature, $selectedFeatures, true) ? 'checked' : ''; ?>> <?= $feature; ?></label>
            <?php endforeach; ?>
        </fieldset>
        <button type="submit" class="btn btn-primary">Save and Continue</button>
    </form>
</section>
