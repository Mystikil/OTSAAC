<h1>Your Characters</h1>
<p><a href="/characters/create">Create Character</a></p>
<div class="table-responsive">
<table>
    <thead>
        <tr><th>Name</th><th>Vocation</th><th>Level</th><th>World</th><th>Delete At</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($characters as $character): ?>
        <tr>
            <td><?= htmlspecialchars($character['name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($character['vocation'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= (int) $character['level']; ?></td>
            <td><?= htmlspecialchars($character['world'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($character['delete_at'] ?? 'Active', ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <?php if (empty($character['delete_at'])): ?>
                    <form method="post" action="/characters/delete/<?= (int) $character['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                        <button type="submit">Delete</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="/characters/cancel/<?= (int) $character['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                        <button type="submit">Cancel</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
