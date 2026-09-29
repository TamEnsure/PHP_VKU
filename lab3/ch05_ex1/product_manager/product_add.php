<?php include '../view/header.php'; ?>
<main>
    <h1>Add Product</h1>
    <form action="index.php" method="post" id="add_product_form">
        <input type="hidden" name="action" value="add_product">

        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id">
        <?php foreach ($categories as $category ) : ?>
            <option value="<?php echo $category['categoryID']; ?>">
                <?php echo htmlspecialchars($category['categoryName']); ?>
            </option>
        <?php endforeach; ?>
        </select>
        <br>

        <label for="code">Code:</label>
        <input type="text" name="code" id="code" />
        <br>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <br>

        <label for="price">List Price:</label>
        <input type="text" name="price" id="price" />
        <br>

        <label>&nbsp;</label>
        <input type="submit" value="Add Product" />
        <br>
    </form>
    <p class="last_paragraph">
        <a href="index.php?action=list_products">View Product List</a>
    </p>

</main>
<?php include '../view/footer.php'; ?>
