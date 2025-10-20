<h1>Media Gallery</h1>
<p><a href="/media/upload">Submit Media</a></p>
<div class="gallery">
<?php foreach ($media as $item): ?>
    <div class="gallery-item">
        <h3><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <?php if ($item['type'] === 'image' && $item['path']): ?>
            <img src="<?= asset($item['path']); ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
        <?php else: ?>
            <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Watch Video</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
