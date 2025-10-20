<h1>Create Market Offer</h1>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <label>Item Name <input type="text" name="item_name" required></label>
    <label>Type
        <select name="type">
            <option value="sell">Sell</option>
            <option value="buy">Buy</option>
            <option value="auction">Auction</option>
        </select>
    </label>
    <label>Price <input type="number" name="price" min="0" required></label>
    <button type="submit">Create</button>
</form>
