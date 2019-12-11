<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['username']==""){
      header('location:index.php');
    }else{
      if($_SESSION['role']=="Admin"){
        include_once'inc/header_all.php';
      }else{
          include_once'inc/header_all_operator.php';
      }
    }
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Producto
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
              <?php
                $id = $_GET['id'];

                $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
                $select->execute();
                while($row = $select->fetch(PDO::FETCH_OBJ)){ ?>

                <div class="col-md-6">
                  <ul class="list-group">

                    <center><p class="list-group-item list-group-item-success">Detalles del producto</p></center>
                    <li class="list-group-item"> <b>Código de producto</b>     :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
                    <li class="list-group-item"><b>Nombre del producto</b>    :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
                    <li class="list-group-item"><b>Categoría del producto</b>        :<span class="label label-primary pull-right"><?php echo $row->product_category; ?></span></li>
                    <li class="list-group-item"><b>Precio de capital</b>  :<span class="label label-warning pull-right">COP. <?php echo number_format($row->purchase_price); ?></span></li>
                    <li class="list-group-item"><b>Precio de venta</b>     :<span class="label label-warning pull-right">COP. <?php echo $row->sell_price; ?></span></li>
                    <li class="list-group-item"><b>Ganancia</b>           :<span class="label label-success pull-right">COP. <?php echo number_format(($row->sell_price - $row->purchase_price)); ?></span></li>
                    <li class="list-group-item"><b>Inventario </b>          :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
                    <li class="list-group-item"><b>Inventario Minimo </b>   :<span class="label label-default pull-right"><?php echo $row->min_stock; ?></span></li>
                    <li class="list-group-item"><b>Unidad</b>               :<span class="label label-default pull-right"><?php echo $row->product_satuan; ?></span></li>
                    <li class="list-group-item"><b>Descripción breve</b>    :</li>
                    <li class="list-group-item col-md-12"><span class="text-muted"><?php echo $row->description ?></span></li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="list-group">
                    <center><p class="list-group-item list-group-item-success">Imagen del producto</p></center>
                    <img src="upload/<?php echo $row->img?>" alt="Product Image" class="img-responsive">
                  </ul>
                </div>
              <?php
                }
              ?>
            </div>
            <div class="box-footer">
                <a href="product.php" class="btn btn-warning">Volver</a>
            </div>

        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'inc/footer_all.php';
 ?>