<?php use function App\csrf_token; ?>
<h1>User Management</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Email</th><th>Username</th><th>Role</th><th>Demo</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= (int)$user['id']; ?></td>
            <td><?= htmlspecialchars($user['email']); ?></td>
            <td><?= htmlspecialchars($user['username']); ?></td>
            <td><?= htmlspecialchars($user['role']); ?></td>
            <td><?= $user['is_demo'] ? 'Yes' : 'No'; ?></td>
            <td>
                <form method="post" action="/admin/users/role">
                    <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
                    <input type="hidden" name="user_id" value="<?= (int)$user['id']; ?>">
                    <select name="role">
                        <option <?= $user['role']==='Player'?'selected':''; ?>>Player</option>
                        <option <?= $user['role']==='Tutor'?'selected':''; ?>>Tutor</option>
                        <option <?= $user['role']==='GM'?'selected':''; ?>>GM</option>
                        <option <?= $user['role']==='Admin'?'selected':''; ?>>Admin</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
