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
  $original_position_letter = substr($original_position, 1, 1);
  $original_position_number = substr($original_position, 2, 1);

  $color = get_user_color($conn);

  // Updates board
  $sql = "UPDATE board SET  $original_position_letter =  $color   WHERE stili= $original_position_number";

  if ($conn->query($sql) === TRUE) {
    
  } 

  // Returns new board
  get_board($conn);
}

function validate_move($conn, $original_position, $target_position, $currentPlayer) {
  // Breaks original position into letter and number combination
  $original_position_letter = substr($original_position, 1, 1);
  $original_position_number = substr($original_position, 2, 1);

  // Gets current user's color
  $color = get_user_color($currentPlayer);


  // Checks if original cell is playing user's color otherwise fails
  if ($result = $conn -> query("SELECT * FROM board WHERE stili= $target_position_number")) {
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if ($row[$target_position_letter] != $color) {
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
  $target_position_letter = substr($target_position, 1, 1);
  $target_position_number = substr($target_position, 2, 1);

  // Checks if target position is in range of original position otherwise fails (true-false flag)
  if (!in_array($target_position_letter, $available_letters) || !in_array($target_position_number, $available_numbers)) {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json;');
    echo '{"Response":"Target cell is not in range", "StatusCode":400}';
    die();
  }

  // Checks if target cell is empty otherwise fails
  if ($result = $conn -> query("SELECT * FROM board WHERE stili= $target_position_number")) {
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

?>