<h1>Recent PvP</h1>
<table class="table">
    <thead><tr><th>Killer</th><th>Victim</th><th>Location</th><th>Time</th></tr></thead>
    <tbody>
    <?php foreach ($kills as $kill): ?>
        <tr>
            <td><?= (int)$kill['killer_id']; ?></td>
            <td><?= (int)$kill['victim_id']; ?></td>
            <td><?= htmlspecialchars($kill['location']); ?></td>
            <td><?= htmlspecialchars($kill['occurred_at']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
