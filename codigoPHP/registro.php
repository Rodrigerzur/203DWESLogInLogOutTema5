<?php
if (isset($_REQUEST['Cancelar'])) {
    header('Location: LogIn.php');
    exit;
}

$aIdiomas['es'] = ['saludo' => 'Bienvenido',
    'usuario' => 'Usuario: ',
    'descripcion' => 'Descripción: ',
    'password' => 'Contraseña: ',
    'passwordRepetida' => 'Repita la contraseña: ',
    'Aceptar' => 'Aceptar',
    'Cancelar' => 'Cancelar'];

$aIdiomas['en'] = ['saludo' => 'Welcome',
    'usuario' => 'User: ',
    'descripcion' => 'Description: ',
    'password' => 'Password: ',
    'passwordRepetida' => 'Repeat Password: ',
    'Aceptar' => 'Acept',
    'Cancelar' => 'Cancel'];

require_once '../core/210322ValidacionFormularios.php';
require_once "../config/confDBPDO.php";

//declaracion de variables universales
define("OBLIGATORIO", 1);
define("OPCIONAL", 0);
$entradaOK = true;


//Declaramos el array de errores y lo inicializamos a null
$aErrores = ['CodUsuario' => null,
    'Descripcion' => null,
    'Password' => null,
    'PasswordRepetida' => null];

if (isset($_REQUEST['Aceptar'])) { //Comprobamos que el usuario haya enviado el formulario
    $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['CodUsuario'], 15, 3, OBLIGATORIO);
    $aErrores['Descripcion'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['Descripcion'], 255, 3, OBLIGATORIO);
    $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 3, 1, OBLIGATORIO);
    $aErrores['PasswordRepetida'] = validacionFormularios::validarPassword($_REQUEST['PasswordRepetida'], 8, 3, 1, OBLIGATORIO);
    try {
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlUsuario = "Select * from T01_Usuario where T01_CodUsuario=:CodUsuario";
        $consultaUsuario = $miDB->prepare($sqlUsuario); //Preparamos la consulta
        $parametrosUsuario = [":CodUsuario" => $_REQUEST['CodUsuario']];

        $consultaUsuario->execute($parametrosUsuario); //Pasamos los parámetros a la consulta
        $registro = $consultaUsuario->fetchObject();

        if ($consultaUsuario->rowCount() > 0) {
            $aErrores['CodUsuario'] = "El usuario ya existe";
        }
        if ($_REQUEST['Password'] != $_REQUEST['PasswordRepetida']) {
            $aErrores['PasswordRepetida'] = "Error, las contraseñas no coinciden";
        }
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode();
        $mensajeExcepcion = $excepcion->getMessage();

        echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>";
        echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion;
    } finally {
        unset($miDB);
    }

    // Recorremos el array de errores
    foreach ($aErrores as $campo => $error) {
        if ($error != null) { // Comprobamos que el campo no esté vacio
            $entradaOK = false;
            $_REQUEST[$campo] = ""; //Limpiamos los campos del formulario
        }
    }
} else {
    $entradaOK = false;
}
if ($entradaOK) {
    try {//validamos que la CodUsuario sea correcta
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "Insert into T01_Usuario (T01_CodUsuario, T01_DescUsuario, T01_Password) values (:CodUsuario, :Descripcion, :Password)";
        $consulta = $miDB->prepare($sql); //Preparamos la consulta
        $parametros = [":CodUsuario" => $_REQUEST['CodUsuario'],
            ":Descripcion" => $_REQUEST['Descripcion'],
            ":Password" => hash("sha256", ($_REQUEST['CodUsuario'] . $_REQUEST['Password']))];

        $consulta->execute($parametros); //Ejecutamos la consulta

        $sqlUpdate = "Update T01_Usuario set T01_NumConexiones = :NumConexiones, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion where T01_CodUsuario=:CodUsuario";
        $consultaUpdate = $miDB->prepare($sqlUpdate); //Preparamos la consulta
        $parametrosUpdate = [":NumConexiones" => (1),
            ":FechaHoraUltimaConexion" => ($oFechaHoraActual = new DateTime)->format('Y-m-d H:i:s'),
            ":CodUsuario" => $_REQUEST['CodUsuario']];
        $consultaUpdate->execute($parametrosUpdate); //Pasamos los parámetros a la consulta

        session_start(); //Iniciamos la sesión
        $_SESSION['usuarioDAW203LogInLogOut'] = $_REQUEST['CodUsuario'];
        $_SESSION['FechaHoraUltimaConexion'] = null;

        header('Location: programa.php');
        exit;
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode();
        $mensajeExcepcion = $excepcion->getMessage();

        echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>";
        echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion;
    } finally {
        unset($miDB);
    }
} else {//Si el usuario no ha rellenado el formulario correctamente 
    ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registrarse</title>
            <link href="../webroot/css/style.css" rel="stylesheet"> 
        </head>
        <body>
            <header>
                <div class="titulo">Registrarse</div>
            </header>
            <main class="mainEditar">
                <div class="contenido">
                    <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta">
                        <h3 style="text-align: center;"><?php echo $aIdiomas[$_COOKIE['idioma']]['saludo']; ?></h3>
                        <br>
                        <div>
                            <label style="font-weight: bold;" class="CodigoDepartamento" for="CodUsuario"><?php echo $aIdiomas[$_COOKIE['idioma']]['usuario']; ?></label>
                            <input type="text" style="background-color: #D2D2D2" name="CodUsuario" value="<?php echo(isset($_REQUEST['CodUsuario']) ? $_REQUEST['CodUsuario'] : null); ?>">
    <?php
    if ($aErrores['CodUsuario'] != null) {
        echo "<span style='color: red;'>" . $aErrores['CodUsuario'] . "</span>";
    }
    ?>
                            <br><br>
                            <label style="font-weight: bold;" class="CodigoDepartamento" for="Descripcion"><?php echo $aIdiomas[$_COOKIE['idioma']]['descripcion']; ?></label>
                            <input type="text" style="background-color: #D2D2D2" name="Descripcion" value="<?php echo(isset($_REQUEST['Descripcion']) ? $_REQUEST['Descripcion'] : null); ?>">
    <?php
    if ($aErrores['Descripcion'] != null) {
        echo "<span style='color: red;'>" . $aErrores['Descripcion'] . "</span>";
    }
    ?>
                            <br><br>

                            <label style="font-weight: bold;" class="DescripcionDepartamento" for="Password"><?php echo $aIdiomas[$_COOKIE['idioma']]['password']; ?></label>
                            <input type="password" style="background-color: #D2D2D2" name="Password" value="<?php echo(isset($_REQUEST['Password']) ? $_REQUEST['Password'] : null); ?>">
    <?php
    if ($aErrores['Password'] != null) {
        echo "<span style='color: red;'>" . $aErrores['Password'] . "</span>";
    }
    ?>
                            <br><br>
                            <label style="font-weight: bold;" class="DescripcionDepartamento" for="PasswordRepetida"><?php echo $aIdiomas[$_COOKIE['idioma']]['passwordRepetida']; ?></label>
                            <input type="password" style="background-color: #D2D2D2" name="PasswordRepetida" value="<?php echo(isset($_REQUEST['PasswordRepetida']) ? $_REQUEST['PasswordRepetida'] : null); ?>">
    <?php
    if ($aErrores['PasswordRepetida'] != null) {
        echo "<span style='color: red;'>" . $aErrores['PasswordRepetida'] . "</span>";
    }
    ?>
                            <br><br>
                        </div>
                        <div>
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['Aceptar']; ?>" name="Aceptar" style="background-color: rgba(17, 188, 20, 0.8)" class="Aceptar">
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['Cancelar']; ?>" name="Cancelar" style="background-color: rgba(207, 16, 16, 0.8)" class="Aceptar">
                        </div>
                    </form>
                </div>
            </main>
            <footer>
                <div><a href="https://daw203.ieslossauces.es/index.php">Rodrigo Geras Zurrón</a></div><div><a href="https://github.com/Rodrigerzur/203DWESLogInLogOutTema5">Github</a></div>
            </footer>
        </body>
    </html>
    <?php
}
?>