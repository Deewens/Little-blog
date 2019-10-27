<?php

$connect = 'mysql:host=localhost;dbname=php_projet';
$user = 'root';
$pwd = '';

	try {
		$pdo = new PDO($connect, $user, $pwd);
	} catch (Exception $exception) {
		die($exception->getMessage());
	}