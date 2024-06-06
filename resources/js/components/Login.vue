<template>
  <div>
    <div class="row py-5 px-5">
      <div class="container">
        <div>
          <label for="name">Name:</label>
          <input type="text" id="roomCode" name="name" />
        </div>
        <div>
          <label for="language">Select Language:</label>
          <select id="language" name="language">
            <option value="english" selected>English</option>
            <!-- Add more language options here if needed -->
          </select>
        </div>
        <div class="d-grid gap-2 mt-4">
          <button class="btn btn-primary" @click="playRoom">Play Game</button>
          <button
            class="btn btn-primary"
            style="margin-left: 10px"
            @click="createRoom"
          >
            Create Room
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Join",

  methods: {
    async createRoom() {
      try {
        localStorage.removeItem("roomCode");
        localStorage.removeItem("userId");
        localStorage.removeItem("userName");
        localStorage.removeItem("ceator");
        localStorage.removeItem("moveData");

        const response = await axios.get("/api/create-room");
        const code = response.data.data.code;
        localStorage.setItem("roomCode", code);
        localStorage.setItem("creator", "creator");
        this.$router.push({ name: "Join", params: { code } });
      } catch (error) {
        console.error("Error:", error);
      }
    },
    playRoom() {
      // Implement playRoom method functionality if needed
    },
  },
  mounted() {
    localStorage.removeItem("roomCode");
    localStorage.removeItem("userId");
    localStorage.removeItem("userName");
    localStorage.removeItem("ceator");
    localStorage.removeItem("moveData");
  },
};
</script>

<style>
.container {
  text-align: center;
}

img {
  max-width: 100%;
  height: auto;
  margin-top: 20px;
}

button {
  margin-top: 20px;
}
</style>
