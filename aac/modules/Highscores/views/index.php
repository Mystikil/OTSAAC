<h1>Highscores</h1>
<ol>
<?php foreach ($entries as $entry): ?>
    <li><?= htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8'); ?> - Level <?= (int) $entry['level']; ?></li>
<?php endforeach; ?>
</ol>
