<h1>Offer Details</h1>
<?php if ($offer): ?>
    <dl>
        <dt>Type</dt><dd><?= htmlspecialchars($offer['type']); ?></dd>
        <dt>Subject</dt><dd><?= htmlspecialchars($offer['subject_type']); ?> #<?= (int)$offer['subject_id']; ?></dd>
        <dt>Price</dt><dd><?= (int)$offer['price']; ?></dd>
        <dt>Status</dt><dd><?= htmlspecialchars($offer['status']); ?></dd>
    </dl>
<?php else: ?>
    <p>Offer not found.</p>
<?php endif; ?>
