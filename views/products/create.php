<?php include 'views/layout/header.php'; ?>
    <h2 class="mb-4">Create Product</h2>
    <form action="index.php?action=create" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" class="form-control" maxlength="500" required></textarea>
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="status" value="1">
            <label class="form-check-label" for="status">Active</label>
        </div>
        
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" name="image" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
<?php include 'views/layout/footer.php'; ?>
