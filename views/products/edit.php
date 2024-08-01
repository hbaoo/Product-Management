<?php include 'views/layout/header.php'; ?>

<h2 class="mb-4">Edit Product</h2>
<form action="index.php?module=product&action=edit&id=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" name="description" required><?php echo $product['description']; ?></textarea>
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <input type="checkbox" class="form-control" name="status" value="1" <?php echo $product['status'] == 1 ? 'checked' : ''; ?>>
    </div>
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control-file" name="image" id="imageInput">
        <img id="previewImage" src="uploads/<?php echo $product['image']; ?>?<?php echo uniqid(); ?>" class="img-thumbnail w-50">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function(){
            var dataURL = reader.result;
            var img = document.getElementById('previewImage');
            img.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);
    });
</script>

<?php include 'views/layout/footer.php'; ?>
