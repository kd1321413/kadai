<?php
require('dbconnect.php');

//ini_set('display_errors', "On");
session_start();

if ($_COOKIE['email'] != '') {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
}

if (!empty($_POST)) {
    // login
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();

            if ($member) {
                //login sec
                $_SESSION['id'] = $member['id'];
                $_SESSION['time'] = time();

                //login save
                if ($_POST['save'] == 'on') {
                    setcookie('email', $_POST['email'], time()+60*60*24*14);
                    setcookie('password', $_POST['password'], time()+60*60*24*14);
                }

                header('Location: post/index.php'); exit();
            } else {
                $error['login'] = 'failed';
            }
        } else {
            $error['login'] = 'blank';
        }
}
?>

<div id="lead">
<p>Please fill in the mail address and password</p>
<p>Registration</p>
<p>&raquo;<a href="join/">Registration</a></p>
</div>
<form action="" method="post">
    <dl>
        <dt>email address</dt>
        <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
            <?php if ($error['login'] == 'blank'): ?>
            <p class="error"> * please fill in email and password</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'failed'): ?>
            <p class ="error"> * fail to login. please login again. </p>
            <?php endif; ?>    
        </dd>
        <dt>password</dt>
        <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
        </dd>
        <dt>login record</dt>
        <dd>
        <input id="save" type="checkbox" name="save" value="on"><label for="save">login automatically </label>
        </dd>
    </dl>
    <div><input type="submit" value="login" /></div>
</form>