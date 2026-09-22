<?php
// Get category data
$name = filter_input(INPUT_POST, 'name');

// Validate input
if ($name == null) {
    $error = "Invalid category data. Check field and try again.";
    include('error.php');
} else {
    require_once('database.php');

    // Add category to database
    $query = 'INSERT INTO categories (categoryName)
              VALUES (:name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->execute();
    $statement->closeCursor();

    // Display the Category List page
    include('category_list.php');
}
?>