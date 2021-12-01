/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  daw2
 * Created: 4 nov. 2021
 */

USE DB203DWESProyectoTema4;
-- Insertar datos en la tabla Departamento de la base de datos DAW203DBDepartamentos
INSERT INTO T02_Departamento(T02_CodDepartamento,T02_DescDepartamento,T02_FechaCreacionDepartamento,T02_VolumenNegocio) VALUES 
('FOL', 'departamento FOL', 1406149672, 102.4),
('DAW', 'departamento DAW', 1406149672, 1000.3),
('DIW', 'departamento DIW', 1406149672, 289.3);

/*insert datos en la tabla usuarios*/

INSERT INTO T01_Usuario(T01_CodUsuario, T01_Password, T01_DescUsuario) VALUES
('albertoF',SHA2('albertoFpaso',256),'AlbertoF'),
('outmane',SHA2('outmanepaso',256),'Outmane'),
('rodrigo',SHA2('rodrigopaso',256),'Rodrigo'),
('isabel',SHA2('isabelpaso',256),'Isabel'),
('david',SHA2('davidpaso',256),'David'),
('aroa',SHA2('aroapaso',256),'Aroa'),
('johanna',SHA2('johannapaso',256),'Johanna'),
('oscar',SHA2('oscarpaso',256),'Oscar'),
('sonia',SHA2('soniapaso',256),'Sonia'),
('heraclio',SHA2('heracliopaso',256),'Heraclio'),
('amor',SHA2('amorpaso',256),'Amor'),
('antonio',SHA2('antoniopaso',256),'Antonio'),
('albertoB',SHA2('albertoBpaso',256),'AlbertoB');