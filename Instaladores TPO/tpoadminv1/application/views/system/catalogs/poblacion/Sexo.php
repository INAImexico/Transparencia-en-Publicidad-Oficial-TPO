   <div class="page">
      <div style="width:90%;margin:auto;">
      <br>
<?php
    $debug_file_name = 'V->'.basename(__FILE__, ".php").'->> '; 
    include_once(DIR_ROOT . 'xcrud/xcrud.php');
    $xcrud = Xcrud::get_instance();
    $xcrud->table('cat_poblacion_sexo');
    $xcrud->table_name('Segmentación por sexo');

    $xcrud->unset_title();
    $xcrud->label('nombre_poblacion_sexo','Segmentación por sexo');
    $xcrud->label('active','Estatus');
    $xcrud->relation('active','sys_active','id_active','name_active');
    echo $xcrud->render();
?>
      </div>
   </div>

