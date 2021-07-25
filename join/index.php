<?php
require('../dbconnect.php');

session_start();

if (!empty($_POST)) {
    // error check
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
    $fileName = $_FILES['image']['name'];
    if (!empty($fileNmae)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif') {
            $error['image'] = 'type';
        }
    }

    // same check
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }

    if (empty($error)) {
        //image upload
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['timp_name'], '../member_picture/' . $image);

        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }
}

// edit
if ($_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<p>fill in the from</P>
<head>
    <meta charset="UTF-8">
</head>

<form action="" method="post" enctype="multipart/form-data">
    <dl>
        <dt>nickname<span class="required">necessary</span></dt>
        <dd>
            <input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
            <?php if ($error['name'] == 'blank'): ?>
            <p class="error"> * fill in the blank</p>
            <?php endif; ?>
        </dd>
        <dt>mail address<span class="required">necessary</span></dt>
        <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
            <?php if ($error['emil'] == 'blank'): ?>
            <p class="error"> * fill in the blank</p>
            <?php endif; ?>
            <?php if ($error['email'] == 'duplicate'): ?>
            <p class="error"> * This mail address has already been registered </p>
            <?php endif; ?>
        </dd>
        <dt>password<span class="required">necessary</span></dt>
        <dd>
            <input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
            <?php if ($error['password'] == 'blank'): ?>
            <p class="error"> * fill in the blank</p>
            <?php endif; ?>
            <?php if ($error['password'] == 'length') :?>
            <p class="error"> * the password must be more then 4 letters</p>
            <?php endif; ?>
        </dd>
        <dt>image</dt>
        <dd>
            <input type="file" name="image" size="35" />
            <?php if ($error['image'] == 'type'): ?>
            <p class="error"> * [.gif] or [.jpg] only </P>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
            <p class="error"> * please upload the image again</p>
            <?php endif; ?>
        </dd>
    </dt>
    <div><input type="submit" value="confirm" /></div>
</form>