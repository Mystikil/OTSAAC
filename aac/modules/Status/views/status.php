<h1>Server Status</h1>
<table class="table">
    <thead><tr><th>Server</th><th>Online</th><th>Latency</th></tr></thead>
    <tbody>
    <?php foreach ($status as $server): ?>
        <tr>
            <td><?= htmlspecialchars($server['name']); ?></td>
            <td><?= $server['online'] ? 'Online' : 'Offline'; ?></td>
            <td><?= $server['latency'] ? $server['latency'] . 'ms' : 'n/a'; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
