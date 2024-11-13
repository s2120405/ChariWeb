<?php
include 'config.php';
include 'class/categoryclass.php';

$category = new Category();
$categories = $category->get_categories(); // Fetch the categories from the database

// Handle form submission for adding a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $newCategoryName = trim($_POST['category_name']);
    $newCategoryDesc = trim($_POST['category_desc']); // Retrieve category description
}
?>

<div class="category-container">
    <!-- Form for adding new categories -->
    <div class="category-form">
        <h2>Add New Category</h2>
        <form method="POST" action="processes/process.category.php?action=newcat">
            <label for="category_name">Category Name/Tags:</label>
            <input type="text" id="category_name" name="category_name" required>

            <label for="category_desc">Category Description:</label>
            <textarea id="category_desc" name="category_desc" placeholder="Optional description"></textarea>

            <button type="submit">Add Category</button>
        </form>

        <?php if (isset($error_message)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>

    <!-- Display existing categories -->
    <div class="category-list">
        <h2>Existing Categories</h2>
        <ul>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                        <?php if (!empty($cat['category_desc'])): ?>
                            - <em><?php echo htmlspecialchars($cat['category_desc']); ?></em>
                        <?php endif; ?>
                        <!-- Link to delete the category -->
                        <a href="processes/delcategory.php?category_id=<?php echo $cat['category_id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">
                            Delete
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No categories found.</li> <!-- Message when no categories exist -->
            <?php endif; ?>
        </ul>
    </div>
</div>
