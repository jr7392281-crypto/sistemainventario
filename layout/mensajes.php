<?php
if (isset($_SESSION['mensaje'], $_SESSION['icono'])) {
    $respuesta = $_SESSION['mensaje'];
    $icono = $_SESSION['icono'];
?>
<script>
Swal.fire({
    position: 'top-end',
    icon: <?php echo json_encode($icono); ?>,
    title: <?php echo json_encode($respuesta); ?>,
    showConfirmButton: false,
    timer: 1500
});
</script>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
}
?>