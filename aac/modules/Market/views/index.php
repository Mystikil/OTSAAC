<h1>Market</h1>
<p><a href="/market/create">Create Offer</a></p>
<div class="table-responsive">
<table>
    <thead><tr><th>Item</th><th>Type</th><th>Price</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($offers as $offer): ?>
        <tr>
            <td><a href="/market/offer/<?= (int) $offer['id']; ?>"><?= htmlspecialchars($offer['item_name'], ENT_QUOTES, 'UTF-8'); ?></a></td>
            <td><?= htmlspecialchars($offer['type'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= (int) $offer['price']; ?></td>
            <td><?= htmlspecialchars($offer['status'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
