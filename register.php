<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['role']!=="Admin"){
        header('location:index.php');
    }
    include_once'inc/header_all.php';

    error_reporting(0);

    $id = $_GET['id'];

    $delete = $pdo->prepare("DELETE FROM tbl_user WHERE user_id=".$id);

    if($delete->execute()){
        echo'<script type="text/javascript">
            jQuery(function validation(){
            swal("Mensaje Informativo", "El usuario ha sido eliminado satisfactoriamente", "info", {
            button: "Continuar",
                });
            });
            </script>';
    }

    if(isset($_POST['submit'])){

        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $password = $_POST['password'];
        $role = $_POST['select_option'];
        $status = $_POST['status'];

        //check if the email already exist
        if(isset($_POST['username'])){
            $select = $pdo->prepare("SELECT username FROM tbl_user WHERE username='$username'");
            $select->execute();

            if($select->rowCount() > 0 ){
                echo'<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "El nombre de usuario ya existe", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
            } else {
                //insert query here
                $insert = $pdo->prepare("INSERT INTO tbl_user(username,fullname,password,role,is_active) VALUES(:name,:fullname,:pass,:role,1)");

                //binding the values parameter with input from user
                $insert->bindParam(':name',$username);
                $insert->bindParam(':fullname',$fullname);
                $insert->bindParam(':pass',$password);
                $insert->bindParam(':role',$role);

                //if execution $insert
                if($insert->execute()){
                    echo'<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Registro Exitoso", "Usuario ingresado satisfactoriamente", "success", {
                        button: "Continue",
                            });
                        });
                        </script>';
                }
            }
        }
    }
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <form action="" method="POST">
            <!-- Registration Form -->
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Registrar una nueva cuenta de Usuario</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                        <div class="box-body">
                                <div class="form-group">
                                    <label for="username">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese el Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="fname">Nombre completo</label>
                                    <input type="text" class="form-control" id="fname" name="fullname" placeholder="Ingrese el nombre completo" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese una contraseña" required>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="password" name="status" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label>Rango </label>
                                    <select class="form-control" name="select_option" required>
                                        <option>Admin</option>
                                        <option>Operator</option>
                                    </select>
                                </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Registered Table -->
            <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                <h3 class="box-title">Lista de usuarios</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped" id="myRegister">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre de usuario</th>
                                    <th>Nombre completo</th>
                                    <th>Rango</th>
                                    <th>Eliminar Usuario</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $select = $pdo->prepare("SELECT * FROM tbl_user");
                                $select->execute();
                                while($row=$select->fetch(PDO::FETCH_OBJ)){
                                ?>
                                    <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->username; ?></td>
                                    <td><?php echo $row->fullname; ?></td>
                                    <td><?php echo $row->role; ?></td>
                                    <td>
                                        <a href="register.php?id=<?php echo $row->user_id; ?>"
                                        onclick="return confirm('Realmente desea eliminar este usuario?')"
                                        class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        <!-- <a href="deactivate.php?id=" class="btn btn-info btn-sm"
                                        onclick="return confirm('Are You Sure, You Want To Deactivate The Account?')" name="deactivate">
                                        <i class="fa fa-power-off"></i></a> -->
                                    </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            </div>
        </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
  $(document).ready( function () {
      $('#myRegister').DataTable();
  } );
  </script>

 <?php
    include_once'inc/footer_all.php';
 ?>