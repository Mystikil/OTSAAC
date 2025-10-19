<h1>Welcome</h1>
<p>Server status overview:</p>
<ul>
    <?php foreach ($status as $server): ?>
        <li><?= htmlspecialchars($server['name']); ?> — <?= $server['online'] ? 'Online' : 'Offline'; ?> (<?= $server['latency'] ? $server['latency'] . 'ms' : 'n/a'; ?>)</li>
    <?php endforeach; ?>
</ul>
