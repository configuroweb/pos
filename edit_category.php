<?php
  include_once'db/connect_db.php';
  session_start();
  if($_SESSION['role']!=="Admin"){
    header('location:index.php');
  }

  if(isset($_POST['btn_edit'])){
      $category_name = $_POST['category'];
      $update = $pdo->prepare("UPDATE tbl_category SET cat_name='$category_name' WHERE cat_id='".$_GET['id']."' ");
      $update->bindParam(':cat_name', $category_name);
      if($update->execute()){
        echo'<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Category Has Been Updated", "success", {
        button: "Continue",
            });
        });
        </script>';
      }else{
        echo'<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Kategori Sudah Ada", "success", {
        button: "Continue",
            });
        });
        </script>';
      }
  }

  if($id=$_GET['id']){
    $select = $pdo->prepare("SELECT * FROM tbl_category WHERE cat_id = '".$_GET['id']."' ");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);
    $cat_name = $row->cat_name;
  }else{
    header('location:category.php');
  }

  include_once'inc/header_all.php';

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Categorías de productos
      </h1>
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
       <!-- Category Form-->
      <div class="col-md-4">
            <div class="box box-warning">
                <!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="category">Nombre de categoría</label>
                      <input type="text" class="form-control" name="category" placeholder="Ingrese nombre categoría"
                      value="<?php echo $cat_name; ?>" required>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary" name="btn_edit">Actualizar</button>
                      <a href="category.php" class="btn btn-warning">Volver</a>
                  </div>
                </form>
            </div>
      </div>

      <div class="col-md-8">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Lista de categorias</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          <table class="table table-striped">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nombre de Categoría</th>
                  </tr>
              </thead>
              <tbody>
              <?php
              $select = $pdo->prepare('SELECT * FROM tbl_category');
              $select->execute();
              while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                <tr>
                  <td><?php echo $row->cat_id; ?></td>
                  <td><?php echo $row->cat_name; ?></td>
                </tr>
              <?php
              }
              ?>

              </tbody>
          </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
    include_once'inc/footer_all.php';
?>
