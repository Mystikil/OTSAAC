<h1>Offer #<?= (int) $offer['id']; ?></h1>
<ul>
    <li>Item: <?= htmlspecialchars($offer['item_name'], ENT_QUOTES, 'UTF-8'); ?></li>
    <li>Type: <?= htmlspecialchars($offer['type'], ENT_QUOTES, 'UTF-8'); ?></li>
    <li>Price: <?= (int) $offer['price']; ?></li>
    <li>Status: <?= htmlspecialchars($offer['status'], ENT_QUOTES, 'UTF-8'); ?></li>
</ul>
