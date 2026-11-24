 <!-- Main Footer -->
 <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
      
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2026 <a href="index.php">Sistema de Inventario y Mantenimiento</a>.</strong>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo $URL;?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    

$(function () {

    const claveMenus = 'sis_inv_mant_menus_abiertos';
    const selector = '.nav-sidebar > .nav-item[data-menu-key]';

    // Restaurar el último módulo abierto
    function restaurarMenu() {
        let guardado = null;

        try {
            const datos = JSON.parse(localStorage.getItem(claveMenus) || '[]');
            guardado = Array.isArray(datos) ? datos[0] : null;
        } catch (error) {
            guardado = null;
        }

        document.querySelectorAll(selector).forEach(function (menu) {
            const abierto = menu.dataset.menuKey === guardado;

            menu.classList.toggle('menu-open', abierto);

            const enlace = menu.querySelector(':scope > .nav-link');
            const submenu = menu.querySelector(':scope > .nav-treeview');

            if (enlace) {
                enlace.classList.toggle('active', abierto);
            }

            if (submenu) {
                submenu.style.display = abierto ? 'block' : 'none';
            }
        });
    }

    // Guardar solamente el módulo seleccionado
    function guardarMenu(nombre) {
        if (nombre) {
            localStorage.setItem(claveMenus, JSON.stringify([nombre]));
        } else {
            localStorage.removeItem(claveMenus);
        }
    }

    // Restaurar al cargar
    restaurarMenu();

    // Al seleccionar un módulo principal
    $(document).on(
        'click',
        '.nav-sidebar > .nav-item[data-menu-key] > .nav-link',
        function () {

            const menu = this.closest('[data-menu-key]');

            setTimeout(function () {
                if (menu.classList.contains('menu-open')) {
                    guardarMenu(menu.dataset.menuKey);
                } else {
                    guardarMenu(null);
                }
            }, 350);
        }
    );

    // Al seleccionar una opción del submenú
    $(document).on(
        'click',
        '.nav-sidebar .nav-treeview a.nav-link',
        function () {

            const menu = this.closest('.nav-item[data-menu-key]');

            if (menu) {
                guardarMenu(menu.dataset.menuKey);
            }
        }
    );

});


</script>
</body>

</html>
