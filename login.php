<?php
require_once('/includes/header.php');

if (!$Entity->validEntity) {
    require_once('/html/login_html.php');
}
else {
    echo ALREADY_LOGGED_IN;
}