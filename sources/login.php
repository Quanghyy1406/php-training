<?php
// Start the session
session_start();

// Kiểm tra xem file configs/session.php có tồn tại không
if (file_exists('configs/session.php')) {
    require_once 'configs/session.php';
} else {
    // Nếu file không tồn tại, bạn có thể bỏ qua hoặc ghi log
    // error_log("File configs/session.php not found!");
}

require_once 'models/UserModel.php';
$userModel = new UserModel();

if (!empty($_POST['submit'])) {
    $users = [
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ];

    $user = $userModel->auth($users['username'], $users['password']);

    if ($user) {
        // ✅ Login successful
        $_SESSION['id'] = $user[0]['id'];
        $_SESSION['username'] = $users['username'];
        $_SESSION['message'] = 'Login successful';

        // ✅ Kiểm tra Remember Me trước khi lưu vào LocalStorage
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            echo "<script>
                localStorage.setItem('username', '{$users['username']}');
            </script>";
        } else {
            echo "<script>
                localStorage.removeItem('username');
            </script>";
        }

        // Chuyển hướng ngay lập tức
        header('Location: list_users.php');
        exit;
    } else {
        // ❌ Login failed
        $_SESSION['message'] = 'Login failed';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php'; ?>
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="container">
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Login</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px">
                    <a href="#">Forgot password?</a>
                </div>
            </div>

            <div style="padding-top:30px" class="panel-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message'] === 'Login successful' ? 'success' : 'danger'; ?>">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="form-horizontal" role="form" id="loginForm">
                    <div class="margin-bottom-25 input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="username" placeholder="username or email">
                    </div>

                    <div class="margin-bottom-25 input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                    </div>

                    <div class="margin-bottom-25">
                        <input type="checkbox" tabindex="3" name="remember" id="remember">
                        <label for="remember"> Remember Me</label>
                    </div>

                    <div class="margin-bottom-25 input-group">
                        <div class="col-sm-12 controls">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                            <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 control">
                            Don't have an account!
                            <a href="form_user.php">Sign Up Here</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tự động điền username từ LocalStorage khi tải trang
    document.addEventListener('DOMContentLoaded', function() {
        const savedUsername = localStorage.getItem('username');
        if (savedUsername) {
            document.getElementById('login-username').value = savedUsername;
            document.getElementById('remember').checked = true;
        }
    });
</script>

</body>
</html>