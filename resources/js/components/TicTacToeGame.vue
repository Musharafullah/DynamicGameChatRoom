<template>
  <div class="container">
    <h1>Tic-Tac-Toe</h1>
    <div class="play-area">
      <div
        v-for="(row, rowIndex) in board"
        :key="rowIndex"
        class="row"
      >
        <div
          v-for="(cell, colIndex) in row"
          :key="colIndex"
          :id="`block_${rowIndex * 3 + colIndex}`"
          class="block"
          @click="cellClicked(rowIndex, colIndex)"
          :class="{ block: true, occupied: cell !== '', creator: cell === 'X' && isCreator,'no-hover': !isCurrentPlayer }">
          {{ cell }}
        </div>
      </div>
    </div>
    <div v-if="winner" class="message">{{ winner }} wins!</div>
    <div v-else-if="isDraw" class="message">It's a draw!</div>
    <button v-if="winner || isDraw" @click="resetGame">Restart Game</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentPlayer: "",
      turn: "",
      board: [
        ["", "", ""],
        ["", "", ""],
        ["", "", ""]
      ],
      winner: null,
        isDraw: false,
        isCreator: false
    };
    },
    created() {
        // ----------------------
        if (localStorage.getItem('creator') === 'creator') {
                this.currentPlayer = "X";
                this.turn = "X";
            } else {
                this.currentPlayer = "O";
                this.turn = "O";
        }
        // -----------------------
        // Initialize Pusher
        this.pusher = new Pusher('06309dd11eee2c1d9bd8', {
        cluster: 'ap2',
        encrypted: true
        });

        // Subscribe to the channel
        this.channel = this.pusher.subscribe('tic-tac-toe-channel');
        this.channel.bind('game-move-'+ this.$route.params.code, (data) => {

            this.isCreator = localStorage.getItem('creator') === 'creator';
            // Update the game board
            this.board = data.board;
            if (this.checkWinner()) {
                console.warn("board__:",this.board,"checkWinner__:",this.checkWinner())
                this.winner = this.checkWinner();
            } else if (this.isBoardFull())
            {
                console.warn("else if__:",this.isBoardFull())
                this.isDraw = false;
            }
            this.turn = data.player;
            if (data.player === this.currentPlayer) {
                this.$toast.open({
                    message: "Now it's your turn",
                    type: "success",
                    duration: 3000,
                    dismissible: true
                });
            }
        });
    },
    methods: {
    cellClicked(rowIndex, colIndex) {
      if (!this.board[rowIndex][colIndex] && !this.winner) {
          // Update current player based
          console.warn("this.currentPlayer", this.currentPlayer, '===', "this.turn:", this.turn);
        if (this.currentPlayer === "X" && this.turn === "X")
        {
            this.currentPlayer = "X";
            this.turn = "O";
            // here the X user when hover on board cell the hover color will not work

        } else if (this.currentPlayer === "O" && this.turn === "O")
        {
            this.currentPlayer = "O";
            this.turn = "X";
            // here the O user when hover on board cell the hover color will not work
        }
        else {
            return;
        }
        this.board[rowIndex][colIndex] = this.currentPlayer;

        this.$emit('game-move-' + this.$route.params.code, {board: this.board });

        // Send the entire updated game board array to the backend
        axios.post(`/api/send-game-move`, {
            roomId: this.$route.params.code,
            board:  this.board,
            player: this.turn,

        }).then(response => {

        }).catch(error => {
            console.error('Error sending game board:', error);
        });
      }
    },
        checkWinner() {
            // Check rows
            for (let i = 0; i < 3; i++) {
                if (
                    this.board[i][0] !== "" &&
                    this.board[i][0] === this.board[i][1] &&
                    this.board[i][0] === this.board[i][2]
                ) {
                    return this.board[i][0];
                }
            }
            // Check columns
            for (let i = 0; i < 3; i++) {
                if (
                    this.board[0][i] !== "" &&
                    this.board[0][i] === this.board[1][i] &&
                    this.board[0][i] === this.board[2][i]
                ) {
                    return this.board[0][i];
                }
            }
            // Check diagonals
            if (
                this.board[0][0] !== "" &&
                this.board[0][0] === this.board[1][1] &&
                this.board[0][0] === this.board[2][2]
            ) {
                return this.board[0][0];
            }
            if (
                this.board[0][2] !== "" &&
                this.board[0][2] === this.board[1][1] &&
                this.board[0][2] === this.board[2][0]
            ) {
                return this.board[0][2];
            }
            // If no winner
            return null;
        },
        isBoardFull() {
        return this.board.every(row => row.every(cell => cell !== ""));
        },
        resetGame() {
        this.board = [
            ["", "", ""],
            ["", "", ""],
            ["", "", ""]
        ];
            this.winner = null;
            this.isDraw = false;
        this.$emit('game-reset');
        },
    },
    computed: {
    isCurrentPlayer() {
        return this.currentPlayer === this.turn;
    }
}

};
</script>

<style scoped>
.no-hover {
  pointer-events: none; /* Disable pointer events */
  background-color: initial !important; /* Reset background color */
}

.container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #eee;
}

.play-area {
  display: grid;
  width: 300px;
  height: 300px;
  grid-template-columns: auto auto auto;
}

.block {
  display: flex;
  width: 100px;
  height: 100px;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  font-weight: bold;
  border: 3px solid black;
  transition: background 0.2s ease-in-out;
}

 /* For unoccupied cells when the creator is the current player */
  .block:not(.occupied):hover {
    cursor: pointer;
    background: #0ff30f; /* Green hover color */
  }

  /* For occupied cells by the creator */
  .block.occupied:hover {
    background: #ff3a3a; /* Red hover color */
  }

  /* For occupied cells by the other player */
  .block.occupied:not(.creator):hover {
    background: #ff3a3a; /* Red hover color */
  }

.message {
  font-size: 24px;
  color: green;
  margin-top: 10px;
}
</style>
