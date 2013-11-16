<?php
if (!defined('IN_SCRIPT')) {
    die('External access denied.');
}
?>
<form action="/processes/login.process.php" method="post">
    <input type="hidden" name="process" value="true" />
    Username: <input type="text" name="username" maxlength="<?php echo Core::$config['max_username']; ?>" />
    Password: <input type="password" name="password" maxlength="<?php echo Core::$config['max_password']; ?>" />
    Remember? <input type="checkbox" name="remember" value="1" />
    <input type="submit" value="Login" />
</form>