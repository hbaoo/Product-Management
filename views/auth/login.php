<?php include 'views/layout/header.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4">Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form action="index.php?action=login" method="post">
    <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
</div>
<?php include 'views/layout/footer.php'; ?>
