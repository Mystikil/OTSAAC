<?php use function App\csrf_token; ?>
<h1>Your Characters</h1>
<table class="table">
    <thead>
        <tr><th>Name</th><th>Vocation</th><th>Level</th><th>World</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($characters as $character): ?>
        <tr>
            <td><?= htmlspecialchars($character['name']); ?></td>
            <td><?= htmlspecialchars($character['vocation']); ?></td>
            <td><?= (int)$character['level']; ?></td>
            <td><?= htmlspecialchars($character['world']); ?></td>
            <td>
                <?php if ($character['deletion_requested_at']): ?>
                    <form method="post" action="/characters/cancel-delete">
                        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
                        <input type="hidden" name="character_id" value="<?= (int)$character['id']; ?>">
                        <button type="submit" class="btn btn-warning">Cancel Delete</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="/characters/delete">
                        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
                        <input type="hidden" name="character_id" value="<?= (int)$character['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<section>
    <h2>Create Character</h2>
    <form method="post" action="/characters/create">
        <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
        <label>Name <input type="text" name="name" required></label>
        <label>Vocation
            <select name="vocation">
                <option>Knight</option>
                <option>Druid</option>
                <option>Paladin</option>
                <option>Sorcerer</option>
            </select>
        </label>
        <label>World <input type="text" name="world" value="Default"></label>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</section>
