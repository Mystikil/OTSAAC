<h1>Create Character</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Name <input type="text" name="name" required></label>
    <label>Vocation
        <select name="vocation">
            <option>Knight</option>
            <option>Sorcerer</option>
            <option>Paladin</option>
            <option>Druid</option>
        </select>
    </label>
    <label>World <input type="text" name="world" value="Aurora"></label>
    <button type="submit">Create</button>
</form>
