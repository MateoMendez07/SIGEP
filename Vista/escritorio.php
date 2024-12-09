<?php 
// vista/index.php
session_start();
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Escritorio";

// Buffer para capturar el contenido
ob_start();
?>  
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border" align="center">
  <h1 class="box-title" ><img src="../Recursos/foci.jpeg" width="770" height="497" /></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->

<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php
// Capturar el contenido
$contenido = ob_get_clean();

// Incluir la plantilla
require "layout.php";
?>
