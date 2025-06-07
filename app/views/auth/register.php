<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-container">
    <h2>Create An Account</h2>
    <p>Please fill out this form to register</p>
    <form action="<?php echo BASE_URL; ?>/auth/register" method="post">
        <div>
            <label for="name">Name: <sup>*</sup></label>
            <input type="text" name="name" value="<?php echo $data['name']; ?>">
            <span class="error"><?php echo $data['name_err']; ?></span>
        </div>
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
            <label for="confirm_password">Confirm Password: <sup>*</sup></label>
            <input type="password" name="confirm_password" value="<?php echo $data['confirm_password']; ?>">
            <span class="error"><?php echo $data['confirm_password_err']; ?></span>
        </div>
        <div>
            <label for="role">Register as: <sup>*</sup></label>
            <select name="role" id="role">
                <option value="student" <?php echo ($data['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                <option value="instructor" <?php echo ($data['role'] == 'instructor') ? 'selected' : ''; ?>>Instructor</option>
            </select>
        </div>
        <div>
            <input type="submit" value="Register">
        </div>
        <p>Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login">Login</a></p>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>