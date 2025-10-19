<?php use function App\csrf_token; ?>
<h1>Community Media</h1>
<div class="media-grid">
    <?php foreach ($media as $item): ?>
        <figure>
            <?php if ($item['type'] === 'image'): ?>
                <img src="/<?= htmlspecialchars($item['path']); ?>" alt="<?= htmlspecialchars($item['title']); ?>" loading="lazy">
            <?php else: ?>
                <a href="<?= htmlspecialchars($item['path']); ?>" target="_blank">Video: <?= htmlspecialchars($item['title']); ?></a>
            <?php endif; ?>
            <figcaption><?= htmlspecialchars($item['title']); ?></figcaption>
        </figure>
    <?php endforeach; ?>
</div>
<?php if (App\Auth::check()): ?>
<form method="post" action="/media/upload" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
    <label>Title <input type="text" name="title" required></label>
    <label>Image <input type="file" name="upload" accept="image/*" required></label>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>
<?php endif; ?>
