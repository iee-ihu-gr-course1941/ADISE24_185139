# Ataxx


## How to Play:
- Starting Setup:
  - Black pawns placed at cell a1 and cell g7.
  - White pawns placed at cell a7 and cell g1.

- Gameplay:
   - The player with the Black pawns begin. 
   - On their turn, a player can perform one of two actions with their pawn:
      a. Duplicate: Move to any adjacent cell (horizontally, vertically, or diagonally). This action leaves the original pawn in its place while creating a duplicate in the new cell.
      b. Move: Move exactly two cells away in any direction. This action leaves the starting cell empty.
   - If, after completing a move, any opposing pawns are adjacent to your newly placed pawn, are converted to your color.

- Winning the Game:
        - Full Board: If all cells are occupied, the player with the most pawns wins.
        - No Moves Available: If a player cannot make any valid move, they lose.
        - No Pawns Remaining: If a player has no remaining pawns on the board, they lose.

 - Enjoy and have fun. 

## Link: [https://users.iee.ihu.gr/~it185139/ADISE24_185139/index.php](https://users.iee.ihu.gr/~it185139/ADISE24_185139/index.php)


## API: 

- ```/board/``` GET 	- Returns your boards
- ```/move/{current cell}/{new cell}``` GET 	- Returns the board or the game status if someone won after executing your move
- ```/reset/``` GET 	- Resets board
- ```/status/``` GET 	- Returns game status

## Examples:

- I want to move from a1 to a3: https://users.iee.ihu.gr/~it185139/ADISE24_185139/index.php/move/a1/a3
- I want to check the game status: https://users.iee.ihu.gr/~it185139/ADISE24_185139/index.php/status
