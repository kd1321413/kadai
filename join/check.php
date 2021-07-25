<?php
ini_set('display_errors', "On");
session_start();
require('../dbconnect.php');


if (!isset($_SESSION['join'])) {
    header('Location : index.php');
    exit();
}
if (!empty($_POST)) {
    //login
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
    echo $ret = $statement ->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);

    header('Location; thanks.php');
    exit();
}
?>

<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
    <dl>
        <dt>nickname</dt>
        <dd>
        <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
        </dd>
        <dt>mail address</dt>
        <dd>
        </dd>
        <dt>password</dt>
        <dd>
            {can not be displayed}
        </dd>
        <dt>images</dt>
        <dd>
        <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>" width="100" height="100" alt="" />
        </dd>
    </dl>
    <div><a href="index.php?action=rewrite">&laquo;&nbsp:edit</a> | <input type="submit" value ="login" /><div>
</form>