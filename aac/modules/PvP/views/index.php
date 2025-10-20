<h1>Recent PvP</h1>
<ul>
<?php foreach ($kills as $kill): ?>
    <li><?= htmlspecialchars($kill['occurred_at'], ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($kill['killer'], ENT_QUOTES, 'UTF-8'); ?> defeated <?= htmlspecialchars($kill['victim'], ENT_QUOTES, 'UTF-8'); ?> at <?= htmlspecialchars($kill['location'], ENT_QUOTES, 'UTF-8'); ?></li>
<?php endforeach; ?>
</ul>
