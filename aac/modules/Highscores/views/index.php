<h1>Highscores</h1>
<table class="table">
    <thead><tr><th>Rank</th><th>Name</th><th>Vocation</th><th>Level</th></tr></thead>
    <tbody>
    <?php $rank = 1; foreach ($scores as $row): ?>
        <tr>
            <td><?= $rank++; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['vocation']); ?></td>
            <td><?= (int)$row['level']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
