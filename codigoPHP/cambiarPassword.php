<?php
session_start();

if (!isset($_SESSION['usuarioDAW203LogInLogOut'])) {
    header('Location: LogIn.php');
}

if (isset($_REQUEST['cancelar'])) {
    header('Location: editarPerfil.php');
    exit;
}

$aIdiomas['es'] = ['bienvenido' => 'Bienvenido',
    'password' => 'Contraseña actual: ',
    'NewPassword' => 'Contraseña nueva: ',
    'RepetirPassword' => 'Repetir nueva contraseña: ',
    'Aceptar' => 'Cambiar contraseña',
    'cancelar' => 'Cancelar'];

$aIdiomas['en'] = ['bienvenido' => 'Welcome',
    'password' => 'Current password: ',
    'NewPassword' => 'New password: ',
    'RepetirPassword' => 'Repeat new password: ',
    'Aceptar' => 'Change password',
    'cancelar' => 'Cancel'];

require_once '../core/210322ValidacionFormularios.php';
require_once "../config/confDBPDO.php";

//declaracion de variables universales
define("OBLIGATORIO", 1);
define("OPCIONAL", 0);
$entradaOK = true;


//Declaramos el array de errores y lo inicializamos a null
$aErrores = ['PasswordA' => null,
    'NewPassword' => null,
    'RepetirPassword' => null];

if (isset($_REQUEST['Aceptar'])) { //Comprobamos que el usuario haya enviado el formulario
    $aErrores['PasswordA'] = validacionFormularios::validarPassword($_REQUEST['PasswordA'], 8, 3, 1, OBLIGATORIO);
    $aErrores['NewPassword'] = validacionFormularios::validarPassword($_REQUEST['NewPassword'], 8, 3, 1, OBLIGATORIO);
    $aErrores['RepetirPassword'] = validacionFormularios::validarPassword($_REQUEST['RepetirPassword'], 8, 3, 1, OBLIGATORIO);
    try {
       /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlUsuario = "Select T01_Password from T01_Usuario where T01_CodUsuario=:CodUsuario";
        $consultaUsuario = $miDB->prepare($sqlUsuario); //Preparamos la consulta
        $parametrosUsuario = [":CodUsuario" => $_SESSION['usuarioDAW203LogInLogOut']];

        $consultaUsuario->execute($parametrosUsuario); 
        $registro = $consultaUsuario->fetchObject();
        $passwordUsuario = $registro->T01_Password;
        $passwordEncriptada = hash("sha256", ($_SESSION['usuarioDAW203LogInLogOut'] . $_REQUEST['PasswordA']));
        if ($passwordEncriptada != $passwordUsuario) {
            $aErrores['PasswordA'] = "Contraseña incorrecta";
        }

        if ($_REQUEST['NewPassword'] != $_REQUEST['RepetirPassword']) {
            $aErrores['RepetirPassword'] = "Error, las contraseñas no coinciden";
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
    try {
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "Update T01_Usuario set T01_Password = :Password where T01_CodUsuario=:CodUsuario";
        $consulta = $miDB->prepare($sql); //Preparamos la consulta
        $parametros = [":Password" => hash("sha256", ($_SESSION['usuarioDAW203LogInLogOut'] . $_REQUEST['NewPassword'])),
            ":CodUsuario" => $_SESSION['usuarioDAW203LogInLogOut']];

        $consulta->execute($parametros); //Ejecutamos la consulta

        header('Location: editarPerfil.php');
        exit;
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode(); 
        $mensajeExcepcion = $excepcion->getMessage(); 

        echo "<span style='color: red;'>Error: </span>" . $mensajeExcepcion . "<br>"; 
        echo "<span style='color: red;'>Código del error: </span>" . $errorExcepcion; 
    } finally {
        unset($miDB); 
    }
} else {//Si el usuario no ha rellenado el formulario correctamente volvera a rellenarlo
    ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cambiar contraseña</title>
        <link href="../webroot/css/style.css" rel="stylesheet"> 
    </head>
        <body>
            <header>
                <div class="titulo"><?php echo $aIdiomas[$_COOKIE['idioma']]['Aceptar']; ?></div>
            </header>
            <main class="mainEditar">
                <div class="contenido">
                    <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta">
                        <h3 style="text-align: center;"><?php echo $aIdiomas[$_COOKIE['idioma']]['bienvenido']; ?></h3>
                        <br>
                        <div>
                            <label style="font-weight: bold;" class="CodigoDepartamento" for="PasswordA"><?php echo $aIdiomas[$_COOKIE['idioma']]['password']; ?></label>
                            <input type="password" style="background-color: #D2D2D2" id="PasswordA" name="PasswordA" value="<?php echo(isset($_REQUEST['PasswordA']) ? $_REQUEST['PasswordA'] : null); ?>">
    <?php
    if ($aErrores['PasswordA'] != null) { 
        echo "<span style='color: red;'>" . $aErrores['PasswordA'] . "</span>";
    }
    ?>
                            <br><br>
                            <label style="font-weight: bold;" class="CodigoDepartamento" for="NewPassword"><?php echo $aIdiomas[$_COOKIE['idioma']]['NewPassword']; ?></label>
                            <input type="password" style="background-color: #D2D2D2" id="NewPassword" name="NewPassword" value="<?php echo(isset($_REQUEST['NewPassword']) ? $_REQUEST['NewPassword'] : null); ?>">
    <?php
    if ($aErrores['NewPassword'] != null) { 
        echo "<span style='color: red;'>" . $aErrores['NewPassword'] . "</span>";
    }
    ?>
                            <br><br>

                            <label style="font-weight: bold;" class="DescripcionDepartamento" for="RepetirPassword"><?php echo $aIdiomas[$_COOKIE['idioma']]['RepetirPassword']; ?></label>
                            <input type="password" style="background-color: #D2D2D2" id="RepetirPassword" name="RepetirPassword" value="<?php echo(isset($_REQUEST['RepetirPassword']) ? $_REQUEST['RepetirPassword'] : null); ?>">
    <?php
    if ($aErrores['RepetirPassword'] != null) { 
        echo "<span style='color: red;'>" . $aErrores['RepetirPassword'] . "</span>";
    }
    ?>
                            <br><br>
                        </div>
                        <div>
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['Aceptar']; ?>" name="Aceptar" style="background-color: rgba(17, 188, 20, 0.8)" class="Aceptar">
                            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['cancelar']; ?>" name="cancelar" style="background-color: rgba(207, 16, 16, 0.8)" class="Aceptar">
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>
    <?php
}
?>