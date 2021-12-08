<?php
session_start();

if (!isset($_SESSION['usuarioDAW203LogInLogOut'])) {
    header('Location: LogIn.php');
    exit;
}

if (isset($_REQUEST['CambiarPass'])) {
    header('Location: cambiarPassword.php');
    exit;
}

if (isset($_REQUEST['salir'])) {
    session_destroy();
    header('Location: LogIn.php');
    exit;
}

if (isset($_REQUEST['Cancelar'])) {
    header('Location: programa.php');
    exit;
}

$aIdiomas['es'] = ['bienvenido' => 'Bienvenido',
    'usuario' => 'Usuario: ',
    'descripcion' => 'Descripción: ',
    'fecha' => 'Fecha Hora Última conexión: ',
    'conexiones' => 'Número de conexiones: ',
    'password' => 'Contraseña: ',
    'cambiarPassword' => 'Cambiar Contraseña',
    'cerrarSesion' => 'Cerrar Sesión',
    'imagen' => 'Imagen: ',
    'eliminarCuenta' => 'Eliminar Cuenta',
    'Aceptar' => 'Aceptar',
    'Cancelar' => 'Cancelar',
    'editarPerfil' => 'Editar Perfil',
    '3mensaje'=>'La ultima conexion se realizo en ',
        '4mensaje'=>'Fecha-Hora de la primera conexion:   '];

$aIdiomas['en'] = ['bienvenido' => 'Welcome',
    'usuario' => 'User: ',
    'descripcion' => 'Description: ',
    'fecha' => 'Date Time Last connection: ',
    'conexiones' => 'Number of connections: ',
    'password' => 'Password: ',
    'cambiarPassword' => 'Change Password',
    'cerrarSesion' => 'logoff',
    'imagen' => 'Image: ',
    'eliminarCuenta' => 'Delete Account',
    'Aceptar' => 'Acept',
    'Cancelar' => 'Cancel',
    'editarPerfil' => 'Edit Profile',
    '3mensaje'=>'Last connection was made on ',
        '4mensaje'=>'First connection was made on   '
];

require_once '../core/210322ValidacionFormularios.php';
require_once "../config/confDBPDO.php";

try {
    /* Establecemos la connection con pdo */
    $miDB = new PDO(HOST, USER, PASSWORD);
    /* configurar las excepcion */
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "Select T01_DescUsuario, T01_NumConexiones from T01_Usuario where T01_CodUsuario=:CodUsuario";
    $consulta = $miDB->prepare($sql); //Preparamos la consulta
    $parametros = [":CodUsuario" => $_SESSION['usuarioDAW203LogInLogOut']];

    $consulta->execute($parametros); //Ejecutamos la consulta
    $oResultado = $consulta->fetchObject();

    $descripcionUsuario = $oResultado->T01_DescUsuario;
    $numConexiones = $oResultado->T01_NumConexiones;
} catch (PDOException $excepcion) {
    $errorExcepcion = $excepcion->getCode();
    $mensajeExcepcion = $excepcion->getMessage();

    echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>";
    echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion;
} finally {
    unset($miDB);
}
//declaracion de variables universales
define("OBLIGATORIO", 1);
define("OPCIONAL", 0);
$entradaOK = true;


$errorDescripcion = "";

if (isset($_REQUEST['eliminarCuenta'])) {
    try {
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE from T01_Usuario where T01_CodUsuario=:CodUsuario";
        $consulta = $miDB->prepare($sql); //Preparamos la consulta
        $parametros = [":CodUsuario" => $_SESSION['usuarioDAW203LogInLogOut']];

        $consulta->execute($parametros); //Ejecutamos la consulta
        session_destroy();
        header('Location: LogIn.php');
        exit;
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode();
        $mensajeExcepcion = $excepcion->getMessage();

        echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>";
        echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion;
    } finally {
        unset($miDB);
    }
}

if (isset($_REQUEST['Aceptar'])) { //Comprobamos que el usuario haya enviado el formulario
    $errorDescripcion = validacionFormularios::comprobarAlfaNumerico($_REQUEST['Descripcion'], 255, 3, OBLIGATORIO);


    // Recorremos el array de errores
    if ($errorDescripcion != null) {
        $entradaOK = false;
        $_REQUEST['Descripcion'] = "";
    }
} else {
    $entradaOK = false;
}
if ($entradaOK) {
    try {
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "Update T01_Usuario set T01_DescUsuario = :DescUsuario where T01_CodUsuario=:CodUsuario";
        $consulta = $miDB->prepare($sql); //Preparamos la consulta
        $parametros = [":DescUsuario" => $_REQUEST['Descripcion'],
            ":CodUsuario" => $_SESSION['usuarioDAW203LogInLogOut']];

        $consulta->execute($parametros); //Ejecutamos la consulta

        header('Location: programa.php');
        exit;
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode(); //Almacenamos el código del error de la excepción en la variable $errorExcepcion
        $mensajeExcepcion = $excepcion->getMessage(); //Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

        echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>"; //Mostramos el mensaje de la excepción
        echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion; //Mostramos el código de la excepción
    } finally {
        unset($miDB); //cerramos la conexion con la base de datos
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Perfil</title>
            <link href="../webroot/css/style.css" rel="stylesheet"> 
        </head>
        <body>
            <header>
                <div class="titulo"><?php echo $aIdiomas[$_COOKIE['idioma']]['editarPerfil']; ?></div>
            </header>
            <main class="mainEditar">
                <div class="contenido">
                    <h3 style="text-align: center;"><?php echo $aIdiomas[$_COOKIE['idioma']]['bienvenido']; ?></h3>
                    <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta" >

                        <div>
                            <label style="font-weight: bold;" class="CodigoDepartamento" for="CodUsuario"><?php echo $aIdiomas[$_COOKIE['idioma']]['usuario']; ?></label>
                            <input type="text" style="background-color: transparent; border: 0px;" id="CodUsuario" name="CodUsuario" value="<?php echo $_SESSION['usuarioDAW203LogInLogOut']; ?>" readonly>

                            <br><br>

                            <label style="font-weight: bold;" class="CodigoDepartamento" for="Descripcion"><?php echo $aIdiomas[$_COOKIE['idioma']]['descripcion']; ?></label>
                            <input type="text" style="background-color: #D2D2D2" id="Descripcion" name="Descripcion" value="<?php echo(isset($_REQUEST['Descripcion']) ? $_REQUEST['Descripcion'] : $descripcionUsuario); ?>">
                            <?php
                            if ($errorDescripcion != null) {
                                echo "<span style='color: red;'>" . $errorDescripcion . "</span>";
                            }
                            ?>
                            <br><br>

                            <label style="font-weight: bold;" class="CodigoDepartamento" for="NConexiones"><?php echo $aIdiomas[$_COOKIE['idioma']]['conexiones']; ?></label>
                            <input type="text" style="background-color: transparent; border: 0px;" id="NConexiones" name="NConexiones" value="<?php echo $numConexiones; ?>" readonly>

                            <br><br>
                           <?php
                if (!is_null($_SESSION['FechaHoraUltimaConexionAnterior'])) {
                    ?> <?php if(($oResultado->T01_NumConexiones) >1){
                        echo $aIdiomas[$_COOKIE['idioma']]['3mensaje'] ;}?>  
                        <?php
                    echo $_SESSION['FechaHoraUltimaConexionAnterior'];
                }else{
                        echo $aIdiomas[$_COOKIE['idioma']]['4mensaje'];echo ($oFechaHoraActual = new DateTime)->format('d-m-Y H:i:s');
                    }
                ?>.

                            <br><br>
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['cambiarPassword']; ?>" name="CambiarPass" style="background-color: rgba(0, 215, 230, 0.78);" class="Aceptar">
                            <br><br>
                            <input type="submit" style="background-color: rgba(242, 231, 87, 0.78);" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['eliminarCuenta']; ?>" name="eliminarCuenta" class="Aceptar">
                            <br><br>
                        </div>
                        <div>
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['Aceptar']; ?>" name="Aceptar" style="background-color: rgba(17, 188, 20, 0.8)" class="Aceptar">
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['Cancelar']; ?>" name="Cancelar" style="background-color: rgba(207, 16, 16, 0.8)" class="Aceptar">
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>
    <?php
}
?>