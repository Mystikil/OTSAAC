<h1><?= htmlspecialchars($guild['name']); ?></h1>
<p><?= htmlspecialchars($guild['motd']); ?></p>
<h2>Members</h2>
<ul>
    <?php foreach ($members as $member): ?>
        <li><?= htmlspecialchars($member['rank']); ?> — Character #<?= (int)$member['character_id']; ?></li>
    <?php endforeach; ?>
</ul>
