/* Game Board creation */ 
DROP TABLE IF EXISTS `board`;

create table board (

 stili int NOT NULL,
/* table cells a,b,...,g fill up with 'B' for player with black pawns, 'W' for player with white pawns and 'E' for empty spots  */
 a enum('B', 'W', 'E') DEFAULT 'E',
 b enum('B', 'W', 'E') DEFAULT 'E',
 c enum('B', 'W', 'E') DEFAULT 'E',
 d enum('B', 'W', 'E') DEFAULT 'E',
 e enum('B', 'W', 'E') DEFAULT 'E',
 f enum('B', 'W', 'E') DEFAULT 'E',
 g enum('B', 'W', 'E') DEFAULT 'E',
 PRIMARY KEY (stili)
 );

 /* Status table*/

DROP TABLE IF EXISTS status;

create table status  (
 Player_turn enum('W','B') ,
 Game_result enum('W','B') 

 );
