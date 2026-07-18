<?php
require_once 'config/app.php';
$user = dbFetchOne("SELECT * FROM users WHERE username='admin'");
echo 'admin verify (1=pass, 0=fail): ' . (int)password_verify('admin123', $user['password']) . PHP_EOL;
$user2 = dbFetchOne("SELECT * FROM users WHERE username='teacher'");
echo 'teacher verify (1=pass, 0=fail): ' . (int)password_verify('teacher123', $user2['password']) . PHP_EOL;
