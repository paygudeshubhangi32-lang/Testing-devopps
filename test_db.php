<?php
require_once 'config/app.php';
$admin = dbFetchOne("SELECT username, email FROM users WHERE role = 'admin'");
print_r($admin);
