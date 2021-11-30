
<?php
require_once '../config/confDBPDO.php'; //Archivo con configuracion de PDO
try {

    $miDB = new PDO(HOST, USER, PASSWORD);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<EOD
use dbs4868791;
CREATE TABLE IF NOT EXISTS Departamento(
CodDepartamento varchar(3) PRIMARY KEY,
DescDepartamento varchar(255) NOT NULL,
FechaBaja date NULL,
VolumenNegocio float NULL
)engine=innodb; 
EOD;

    $miDB->exec($sql);
} catch (PDOException $excepcion) {//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException

    echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion; //Muestro el codigo del error
    echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
?>