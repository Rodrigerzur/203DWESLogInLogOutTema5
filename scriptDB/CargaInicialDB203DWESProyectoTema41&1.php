<?php
require_once '../config/confDBPDO.php'; //Archivo con configuracion de PDO
try {

    $miDB = new PDO(HOST, USER, PASSWORD);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<EOD
USE dbs4868791;
INSERT INTO Departamento (CodDepartamento, DescDepartamento, FechaBaja, VolumenNegocio) VALUES
('INF','Departamento de Informatica',null,1.5),
('BIO','Departamento de Biologia',null,2.5),
('ING','Departamento de Inglés',null,3.5),
('LEN','Departamento de Lengua',null,4.5),
('MUS','Departamento de Musica',null,1.5);
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
