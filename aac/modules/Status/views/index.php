<h1>Server Status</h1>
<ul class="status-list">
<?php foreach ($servers as $server): ?>
    <li>
        <?= htmlspecialchars($server['name'], ENT_QUOTES, 'UTF-8'); ?>:
        <span class="status-pill <?= $server['online'] ? 'online' : 'offline'; ?>">
            <?= $server['online'] ? 'Online' : 'Offline'; ?>
        </span>
    </li>
<?php endforeach; ?>
</ul>
