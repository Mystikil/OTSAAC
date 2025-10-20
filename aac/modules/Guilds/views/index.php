<h1>Guilds</h1>
<p><a href="/guilds/create">Create Guild</a></p>
<ul>
<?php foreach ($guilds as $guild): ?>
    <li><?= htmlspecialchars($guild['name'], ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($guild['motd'], ENT_QUOTES, 'UTF-8'); ?></li>
<?php endforeach; ?>
</ul>
