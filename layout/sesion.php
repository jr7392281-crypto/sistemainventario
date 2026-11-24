<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['sesion_email'])) {
  $email_sesion = $_SESSION['sesion_email'];
  $sql = "SELECT us.id_usuario, us.nombres, us.email, us.debe_cambiar_password, rol.rol
          FROM tb_usuarios AS us
          INNER JOIN tb_roles AS rol ON us.id_rol = rol.id_rol
          WHERE us.email = :email";
  $query = $pdo->prepare($sql);
  $query->execute([':email' => $email_sesion]);
  $usuario = $query->fetch(PDO::FETCH_ASSOC);

  if ($usuario) {
    if ((int) $usuario['debe_cambiar_password'] === 1) {
      header('Location: ' . $URL . '/login/nueva_contrasena_temporal.php');
      exit;
    }
    $id_usuario_sesion = $usuario['id_usuario'];
    $nombres_sesion = $usuario['nombres'];
    $rol_sesion = $usuario['rol'];
  } else {
    session_destroy();
    header('Location: ' . $URL . '/login/');
    exit;
  }
} else {
  header('Location: ' . $URL . '/login/');
  exit;
}
?>
