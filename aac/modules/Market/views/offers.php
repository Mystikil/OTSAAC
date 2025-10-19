<h1>All Offers</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Type</th><th>Subject</th><th>Price</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($offers as $offer): ?>
        <tr>
            <td><?= (int)$offer['id']; ?></td>
            <td><?= htmlspecialchars($offer['type']); ?></td>
            <td><?= htmlspecialchars($offer['subject_type']); ?> #<?= (int)$offer['subject_id']; ?></td>
            <td><?= (int)$offer['price']; ?></td>
            <td><?= htmlspecialchars($offer['status']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p>Page <?= (int)$page; ?></p>
