<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-container">
    <h2>Login</h2>
    <p>Please enter your credentials to login</p>
    <form action="<?php echo BASE_URL; ?>/auth/login" method="post">
        <div>
            <label for="email">Email: <sup>*</sup></label>
            <input type="email" name="email" value="<?php echo $data['email']; ?>">
            <span class="error"><?php echo $data['email_err']; ?></span>
        </div>
        <div>
            <label for="password">Password: <sup>*</sup></label>
            <input type="password" name="password" value="<?php echo $data['password']; ?>">
            <span class="error"><?php echo $data['password_err']; ?></span>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
        <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/auth/register">Register</a></p>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>