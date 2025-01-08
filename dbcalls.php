<?php

function get_board($conn) {
  if ($result = $conn -> query("SELECT * FROM board")) {
    if ($result->num_rows > 0) {
      // output data of each row
      $json = '{'; 
      while($row = $result->fetch_assoc()) {
        $json = $json . '"a' . $row["stili"] . '":"' . $row["a"] . '", "b' . $row["stili"] . '":"' . $row["b"] . '", "c' . $row["stili"] . '":"' . $row["c"] . '", "d' . $row["stili"] . '":"' . $row["d"] . '","e' . $row["stili"] . '":"' . $row["e"] . '", "f' . $row["stili"] . '":"' . $row["f"] . '", "g' . $row["stili"] . '":"' . $row["g"] . '",';
      }
      $json = substr_replace($json, '', -1);
      $json = $json . '}';
      header("HTTP/1.1 200 Ok");
      header('Content-Type: application/json;');
      echo $json;
    }
  }
}

function get_move($conn, $original_position, $target_position, $currentPlayer) {

  // Validates move
  validate_move($conn, $original_position, $target_position, $currentPlayer);

  // Breaks original position into letter and number combination
  $original_position_letter = substr($original_position, 0, 1);
  $original_position_number = substr($original_position, 1, 1);

  $color = get_user_color($currentPlayer);

  $position_number_flag = false ;
  
  if ($target_position_number = $original_position_number || $target_position_number = $original_position_letter + 1 || $target_position_number = $original_position_letter - 1 ){
    $position_letter_flag = true;
  }

  $position_letter_flag = false;
  switch ($original_position_letter) {
    case "a":
      if ($target_position_letter = 'a' || $target_position_letter = 'b'){
        $position_letter_flag = true;
      }
      break;
    
    case "b":
      if ($target_position_letter = 'a' || $target_position_letter = 'b'  || $target_position_letter = 'c'){
        $position_letter_flag = true;
      }
      break;

    case "c":
      if ($target_position_letter = 'b' || $target_position_letter = 'c'  || $target_position_letter = 'd'){
        $position_letter_flag = true;
      }
      break;  
   
    case "d":
      if ($target_position_letter = 'c' || $target_position_letter = 'd'  || $target_position_letter = 'e'){
        $position_letter_flag = true;
      }
      break;
        
    case "e":
      if ($target_position_letter = 'd' || $target_position_letter = 'e'  || $target_position_letter = 'f'){
        $position_letter_flag = true;
      }
      break; 
      
    case "f":
      if ($target_position_letter = 'e' || $target_position_letter = 'f'  || $target_position_letter = 'g'){
        $position_letter_flag = true;
      }
      break; 

    case "g":
      if ($target_position_letter = 'f' || $target_position_letter = 'g'){
        $position_letter_flag = true;
      }
    break;   

    default: //404  letter not found
      header("HTTP/1.1 404 Letter not found");
      header('Content-Type: application/json;');
      echo '{"Response":"Letter not found", "StatusCode":404}';
      die();
  }

  if ($position_letter_flag != true || $position_number_flag != true) {
    $sql = "UPDATE board SET $original_position_letter='E' WHERE stili='$original_position_number';";

    if ($conn->query($sql) === TRUE) {
      
    }
  }

  switch ($target_position_letter) {
    case "a":
      $available_letters = ['a', 'b'];
      break;
    
    case "b":
      $available_letters = ['a', 'b', 'c'];
      break;

    case "c":
      $available_letters = ['b', 'c', 'd'];
      break;  
   
    case "d":
      $available_letters = ['c', 'd', 'e'];
      break;
        
    case "e":
      $available_letters = ['d', 'e', 'f'];
      break; 
      
    case "f":
      $available_letters = ['e', 'f', 'g'];
      break; 

    case "g":
      $available_letters = ['f', 'g'];
    break;   

    default: //406 Unacceptable movement
      header("HTTP/1.1 406 Unacceptable movement");
      header('Content-Type: application/json;');
      echo '{"Response":"Unacceptable movement", "StatusCode":406}';
      die();
  }

  $available_numbers = [$target_position_number - 1, $target_position_number, $target_position_number + 1];

  for ($x = 0; $x > count($available_numbers); $x++) {
    $number = $available_numbers[$x];
    for ($y = 0; $y > count($available_letters); $y++) {
      $letter = $available_letters[$y];
      $sql += "UPDATE board SET $letter='$color' WHERE stili='$number' AND $letter<>'E' AND $letter<>'$color';";
    }
  }

  // Updates board
  $mysqli->multi_query($query);
  do {
      if ($result = $mysqli->store_result()) {
          while ($row = $result->fetch_row()) {
          }
      }
      if ($mysqli->more_results()) {
      }
  } while ($mysqli->next_result());

  change_player_turn($conn, $currentPlayer);

  check_game_end($conn);

  // Returns new board
  get_board($conn);
}

function validate_move($conn, $original_position, $target_position, $currentPlayer) {
  // Breaks original position into letter and number combination
  $original_position_letter = substr($original_position, 0, 1);
  $original_position_number = substr($original_position, 1, 1);

  // Gets current user's color
  $color = get_user_color($currentPlayer);

  // Checks if original cell is playing user's color otherwise fails
  if ($result = $conn -> query("SELECT * FROM board WHERE stili= '$original_position_number';")) {
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if ($row[$original_position_letter] != $color) {
          header("HTTP/1.1 400 Bad Request");
          header('Content-Type: application/json;');
          echo '{"Response":"Original cell is not yours", "StatusCode":400}';
          die();
        } 
      }
    }
  }

  // Gets available columns for original position
  switch ($original_position_letter) {
    case "a":
      $available_letters = ['a', 'b', 'c'];
      break;
    
    case "b":
      $available_letters = ['a', 'b', 'c', 'd'];
      break;

    case "c":
      $available_letters = ['a', 'b', 'c', 'd', 'e'];
      break;  
   
    case "d":
      $available_letters = ['b', 'c', 'd', 'e', 'f'];
      break;
        
    case "e":
      $available_letters = [ 'c', 'd', 'e', 'f', 'g'];
      break; 
      
    case "f":
      $available_letters = ['d','e', 'f', 'g'];
      break; 

    case "g":
      $available_letters = ['e', 'f', 'g'];
    break;   

    default: //404  letter not found
      header("HTTP/1.1 404 Letter not found");
      header('Content-Type: application/json;');
      echo '{"Response":"Letter not found", "StatusCode":404}';
      die();
  }

  // Gets avilable rows for original position
  switch ($original_position_number) {
    case '1':
      $available_numbers = ['1', '2', '3'];
      break;

    case '2':
      $available_numbers = ['1', '2', '3', '4'];
      break;

    case '3':
      $available_numbers = ['1', '2', '3', '4', '5'];
      break;

    case '4':
      $available_numbers = [ '2', '3', '4', '5', '6'];
      break;

    case '5':
      $available_numbers = ['3', '4', '5', '6', '7'];
      break;

    case '6':
      $available_numbers = ['4', '5', '6', '7'];
      break;

    case '7':
      $available_numbers = ['5', '6', '7'];
      break; 

    default: // 404 number not found
      header("HTTP/1.1 404 Number not found");
      header('Content-Type: application/json;');
      echo '{"Response":"Number not found", "StatusCode":404}';
      die();
  }

  // Breaks target position into letter and number combination 
  $target_position_letter = substr($target_position, 0, 1);
  $target_position_number = substr($target_position, 1, 1);

  // Checks if target position is in range of original position otherwise fails (true-false flag)
  if (!in_array($target_position_letter, $available_letters) || !in_array($target_position_number, $available_numbers)) {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json;');
    echo '{"Response":"Target cell is not in range", "StatusCode":400}';
    die();
  }

  // Checks if target cell is empty otherwise fails
  if ($result = $conn -> query("SELECT * FROM board WHERE stili= '$target_position_number';")) {
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if ($row[$target_position_letter] != 'E') {
          header("HTTP/1.1 400 Bad Request");
          header('Content-Type: application/json;');
          echo '{"Response":"Target cell is not empty", "StatusCode":400}';
          die();
        } 
      }
    }
  }
}

function get_user_color($currentPlayer) {
  if ($currentPlayer == "Player1") {
    return "B";
  } else {
    return "W";
  }
}

function get_other_user_color($currentPlayer) {
  if ($currentPlayer == "Player1") {
    return "W";
  } else {
    return "B";
  }
}

function change_player_turn($conn, $currentPlayer) {
  $user_color = get_other_user_color($currentPlayer);
  $sql = "UPDATE status SET Player_turn = '$user_color';";
  if ($conn->query($sql) === TRUE) {
      
  }
}

function check_game_end($conn) {
  // Check deadlock / player won
}

function get_status($conn) {
  // Return game status
}

function reset_game($conn) {
  // Reset board / status
  $sql = "DELETE * FROM board;";
  $sql += "DELETE * FROM status;";

  $sql += "INSERT INTO status(Player_turn, Game_result) VALUES('B', NULL);";

  $sql += "INSERT INTO board(stili) VALUES(1);";
  $sql += "INSERT INTO board(stili) VALUES(2);";
  $sql += "INSERT INTO board(stili) VALUES(3);";
  $sql += "INSERT INTO board(stili) VALUES(4);";
  $sql += "INSERT INTO board(stili) VALUES(5);";
  $sql += "INSERT INTO board(stili) VALUES(6);";
  $sql += "INSERT INTO board(stili) VALUES(7);";
  
  $sql += "UPDATE board SET a = 'B', g = 'W' WHERE stili = 1;";
  $sql += "UPDATE board SET a = 'W', g = 'B' WHERE stili = 7;";

  $mysqli->multi_query($query);
  do {
      if ($result = $mysqli->store_result()) {
          while ($row = $result->fetch_row()) {
          }
      }
      if ($mysqli->more_results()) {
      }
  } while ($mysqli->next_result());

  header("HTTP/1.1 200 OK");
  header('Content-Type: application/json;');
  echo '{"Response":"Game Reset"}';
  die();
}

?>
