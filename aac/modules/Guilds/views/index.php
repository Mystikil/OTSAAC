<h1>Guilds</h1>
<ul>
    <?php foreach ($guilds as $guild): ?>
        <li><a href="/guild/<?= (int)$guild['id']; ?>"><?= htmlspecialchars($guild['name']); ?></a></li>
    <?php endforeach; ?>
</ul>
