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

    if(isset($_POST['add_product'])){
        $code = $_POST['product_code'];
        $product = $_POST['product_name'];
        $category = $_POST['category'];
        $purchase = $_POST['purchase_price'];
        $sell = $_POST['sell_price'];
        $stock = $_POST['stock'];
        $min_stock = $_POST['min_stock'];
        $satuan = $_POST['satuan'];
        $desc = $_POST['description'];


        if(isset($_POST['product_code'])){
            $select = $pdo->prepare("SELECT product_code FROM tbl_product WHERE product_code='$code'");
            $select->execute();

            if($select->rowCount() > 0 ){
                echo'<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Código de producto ya registrado", "warning", {
                    button: "Continuar",
                        });
                    });
                    </script>';
            }elseif (strlen($code)>6 || strlen($code)<6) {
                    echo'<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "El código debe tener 6 caracteres, como mínimo de acuerdo con las reglas", "warning", {
                    button: "Continuar",
                        });
                    });
                    </script>';
            }else{
            $img = $_FILES['product_img']['name'];
            $img_tmp = $_FILES['product_img']['tmp_name'];
            $img_size = $_FILES['product_img']['size'];
            $img_ext = explode('.', $img);
            $img_ext = strtolower(end($img_ext));

            $img_new = uniqid().'.'. $img_ext;

            $store = "upload/".$img_new;

            if($img_ext == 'jpg' || $img_ext == 'jpeg' || $img_ext == 'png' || $img_ext == 'gif'){
                if($img_size>= 1000000){
                    $error ='<script type="text/javascript">
                            jQuery(function validation(){
                            swal("Error", "El archivo debe tener 1 MB.", "error", {
                            button: "Continuar",
                                });
                            });
                            </script>';
                    echo $error;
                }else{
                    if(move_uploaded_file($img_tmp,$store)){
                        $product_img = $img_new;
                        if(!isset($error)){

                            $insert = $pdo->prepare("INSERT INTO tbl_product(product_code,product_name,product_category,purchase_price,sell_price,stock,min_stock,product_satuan,description,img)
                            values(:product_code,:product_name,:product_category,:purchase_price,:sell_price,:stock,:min_stock,:satuan,:desc,:img)");

                            $insert->bindParam(':product_code', $code);
                            $insert->bindParam(':product_name', $product);
                            $insert->bindParam(':product_category', $category);
                            $insert->bindParam(':purchase_price', $purchase);
                            $insert->bindParam(':sell_price', $sell);
                            $insert->bindParam(':stock', $stock);
                            $insert->bindParam(':min_stock', $min_stock);
                            $insert->bindParam(':satuan', $satuan);
                            $insert->bindParam(':desc', $desc);
                            $insert->bindParam(':img', $product_img);

                            if($insert->execute()){
                                echo'<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Success", "Producto guardado con éxito", "success", {
                                        button: "Continuar",
                                            });
                                        });
                                        </script>';
                            }else{
                                echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Ocurrió un error", "error", {
                                        button: "Continuar",
                                            });
                                        });
                                        </script>';;
                            }

                        }else{
                            echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Ocurrió un error", "error", {
                                        button: "Continuar",
                                            });
                                        });
                                        </script>';;;
                        }
                    }

                }
            }else{
                $error = '<script type="text/javascript">
                jQuery(function validation(){
                swal("Error", "Sube una imagen con los siguientes formatos : jpg, jpeg, png, gif", "error", {
                button: "Continuar",
                    });
                });
                </script>';
                echo $error;

            }
            }
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
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Ingrese nuevos productos</h3>
            </div>
            <form action="" method="POST" name="form_product"
                enctype="multipart/form-data" autocomplete="off">
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Código de producto</label><br>
                            <span class="text-muted">Asegúrese de que los códigos de producto sean correctos</span>
                            <input type="text" class="form-control"
                            name="product_code">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre del producto</label>
                            <input type="text" class="form-control"
                            name="product_name">
                        </div>
                        <div class="form-group">
                            <label for="">Categoría</label>
                            <select class="form-control" name="category" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_category");
                                $select->execute();
                                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                                    extract($row)
                                ?>
                                    <option><?php echo $row['cat_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Precio de base</label>
                            <input type="number" min="1000" step="1000"class="form-control"
                            name="purchase_price" required>
                        </div>
                        <div class="form-group">
                            <label for="">Precio de venta</label>
                            <input type="number" class="form-control"
                            name="sell_price" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Inventario</label><br>
                            <span class="text-muted">Unidad según producto</span>
                            <input type="number" min="1" step="1"
                            class="form-control" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Inventario Minimo</label><br>
                            <input type="number" min="1" step="1"
                            class="form-control" name="min_stock" required>
                        </div>
                        <div class="form-group">
                            <label for="">Unidad</label>
                            <select class="form-control" name="satuan" required>
                                <?php
                                $select = $pdo->prepare("SELECT * FROM tbl_satuan");
                                $select->execute();
                                while($row = $select->fetch(PDO::FETCH_ASSOC)){
                                    extract($row)
                                ?>
                                    <option><?php echo $row['nm_satuan']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Descripción breve del producto</label>
                            <textarea name="description" id="description"
                            cols="30" rows="10" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Imagen del producto</label><br>
                            <br>
                            <input type="file" class="input-group"
                            name="product_img" onchange="readURL(this);" required> <br>
                            <img id="img_preview" src="upload/<?php echo $row->img?>" alt="Preview" class="img-responsive" />
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"
                    name="add_product">Agregar producto</button>
                    <a href="product.php" class="btn btn-warning">Volver</a>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_preview').attr('src', e.target.result)
                .width(250)
                .height(200);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

 <?php
    include_once'inc/footer_all.php';
 ?>