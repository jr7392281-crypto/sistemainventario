<?php
include('../../config.php');
include('../../solo_administrador.php');

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$nombres = $_POST['nombres'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$id_area = (int) ($_POST['id_area'] ?? 0);
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];

if ($id_area < 1) {
    $_SESSION['mensaje'] = 'Debes seleccionar un área.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

if ($password_user === $password_repeat) {
    $password_user = password_hash($password_user, PASSWORD_DEFAULT);

    $sentencia = $pdo->prepare(
        'INSERT INTO tb_usuarios (nombres, email, id_rol, id_area, password_user)
         VALUES (:nombres, :email, :id_rol, :id_area, :password_user)'
    );
    $sentencia->bindParam(':nombres', $nombres);
    $sentencia->bindParam(':email', $email);
    $sentencia->bindParam(':id_rol', $rol);
    $sentencia->bindValue(':id_area', $id_area, PDO::PARAM_INT);
    $sentencia->bindParam(':password_user', $password_user);
    $sentencia->execute();

    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $_SESSION['mensaje'] = 'Se registró el usuario correctamente.';
        $_SESSION['icono'] = 'success';
        header('Location: ' . $URL . '/usuarios/');

    } else{
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $_SESSION['mensaje'] = 'Las contraseñas no coinciden.';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/usuarios/');

    }
