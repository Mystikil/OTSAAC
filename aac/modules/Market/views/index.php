<h1>Market Overview</h1>
<p>Recent offers from the community marketplace.</p>
<ul>
    <?php foreach ($offers as $offer): ?>
        <li><a href="/market/offer/<?= (int)$offer['id']; ?>"><?= htmlspecialchars($offer['type']); ?> <?= htmlspecialchars($offer['subject_type']); ?> #<?= (int)$offer['subject_id']; ?> for <?= (int)$offer['price']; ?></a></li>
    <?php endforeach; ?>
</ul>
