<?php
// Start the session
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = $_GET['id'];
    $user = $userModel->findUserById($_id);//Update existing user
}


if (!empty($_POST['submit'])) {

    if (!empty($_id)) {
        $userModel->updateUser($_POST);
    } else {
        $userModel->insertUser($_POST);
    }
    header('location: list_users.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php'?>
    <div class="container">

            <?php if ($user || !isset($_id)) { ?>
                <div class="alert alert-warning" role="alert">
                    User form
                </div>
                <form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_id); ?>">

    <div class="form-group">
        <label for="name">Name (username)</label>
        <input type="text" class="form-control" name="name"
               value="<?php echo !empty($user[0]['name']) ? htmlspecialchars($user[0]['name']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="fullname">Full name</label>
        <input type="text" class="form-control" name="fullname"
               value="<?php echo !empty($user[0]['fullname']) ? htmlspecialchars($user[0]['fullname']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email"
               value="<?php echo !empty($user[0]['email']) ? htmlspecialchars($user[0]['email']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="type">Type</label>
        <select class="form-control" name="type">
            <option value="user"  <?php echo (!empty($user[0]['type']) && $user[0]['type']=='user') ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo (!empty($user[0]['type']) && $user[0]['type']=='admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới nếu muốn đổi">
    </div>

    <div class="form-group">
        <label for="version">Version</label>
        <input type="number" class="form-control" name="version"
               value="<?php echo isset($user[0]['version']) ? htmlspecialchars($user[0]['version']) : 1; ?>">
    </div>

    <button type="submit" name="submit" value="submit" class="btn btn-primary">Lưu</button>
</form>

            <?php } else { ?>
                <div class="alert alert-success" role="alert">
                    User not found!
                </div>
            <?php } ?>
    </div>
</body>
</html>