<template>
  <div class="container">
    <div class="header">
      <h1>Chat Room: {{ roomName }}</h1>
    </div>
    <div class="main-content d-flex justify-content-between">
        <!-- User Sec -->
        <div class="user-list" style="width: 5rem;">
            <h2>Users</h2>
            <ul>
            <li v-for="user in userList" :key="user">{{ user }}</li>
            </ul>
        </div>
      <div class="game-section" style="width: 50rem;">
        <h2>Game Section</h2>
        <!-- //game component will be here -->
        <TicTacToeGame />
      </div>
      <!-- Message Sec -->
      <div class="chat-interface" style="width: 10rem;">
            <h2 class="text-center">Chat Interface</h2>
            <div class="chat-box" style="max-height: 20rem; overflow-y: auto;">
            <p v-for="message in messages" :key="message.id" style="text-align: left">{{ message.userName }}: {{ message.content }}</p>
            </div>
            <form @submit.prevent="sendMessage">
            <input type="text" v-model="messageInput" class="form-control mb-2" placeholder="Type your message...">
            <button type="submit" class="btn btn-primary">Send</button>
            </form>
      </div>
    </div>
    <div class="footer mt-3 text-center">
        <p>Your referral link: http://127.0.0.1:8000/join-room/{{ room.code }}</p>
        <h3 @click="copyLink" style="cursor: pointer;">Copy the link</h3>
    </div>

  </div>
</template>


<script>
        import axios from 'axios';
        import Echo from 'laravel-echo'; // Import Echo from laravel-
        import  TicTacToeGame  from './TicTacToeGame.vue';
        import Pusher from 'pusher-js';

        export default {
                name: 'Join',
    //game component will be here
    components: {
                 'TicTacToeGame':TicTacToeGame,
                },
                data() {
                    return {
                    roomName: '',
                    userId:'',
                    room: {}, // Object to store room data
                    userList: [], // Array to store user list
                    messages: [],
                    messageInput: '' // Input field for sending messages
                    };
                },
                created() {
                    this.fetchRoomData();
                    Pusher.logToConsole = false;
                    this.pusher = new Pusher('06309dd11eee2c1d9bd8', {
                        cluster: 'ap2',
                    });
                    this.channel = this.pusher.subscribe(`tic-tac-toe-channel`);
                    this.channel.bind('NewMessage-' +  this.$route.params.code, (data) => {
                    // console.log("Received new message:", data); // console the data
                    const messageData = data.message;
                    const userId = messageData.userId;
                        const userName = messageData.userName;
                    const content = messageData.content;
                    const message = {
                        userName: userName,
                        userId: userId,
                        content: content
                    };
                        this.messages.push(message);
                    });
                },
                methods: {
                copyLink() {
                    // Generate the URL based on room.code
                    const referralLink = 'http://127.0.0.1:8000/join-room/' + this.$route.params.code;
                    // Create a temporary input element
                    const tempInput = document.createElement('input');
                    tempInput.value = referralLink;
                    document.body.appendChild(tempInput);
                    // Select the input value
                    tempInput.select();
                    tempInput.setSelectionRange(0, 99999); // For mobile devices
                    // Copy the selected text
                    document.execCommand('copy');
                    // Remove the temporary input
                    document.body.removeChild(tempInput);
                    // Optionally, provide feedback to the user
                    alert('Link copied to clipboard!');
                },
                        fetchRoomData() {
                            // Fetch room data using Axios
                            let code = this.$route.params.code;
                            this.roomName = code;
                            localStorage.setItem('roomCode', code); // Store the code in local storage
                            this.userId = localStorage.getItem('userId');
                            //   axios.get(`/api/join-room/${this.$route.params.code}`)
                            let result = axios.post(`/api/join-room/`, { 'code': code,'userId':this.userId})
                                .then(response =>
                                {
                                    this.userId = response.data.userId;

                                    localStorage.setItem('userId', this.userId);

                                    if (localStorage.getItem('creator') === 'creator') {
                                        localStorage.setItem('userName', response.data.usersNames[0]);
                                        localStorage.setItem('creatorName', response.data.usersNames[0]);
                                    } else {
                                        localStorage.setItem('userName', response.data.usersNames[1]);
                                        localStorage.setItem('joinerName', response.data.usersNames[1]);
                                    }
                                    this.room = response.data.room;
                                    this.userList = response.data.usersNames;
                                }
                            )
                            .catch(error => {
                            console.error('Error fetching room data:', error);
                            });
                        },
                        // send message
                        sendMessage() {
                            // Retrieve user ID from local storage
                            const userId = localStorage.getItem('userId');
                            axios.post(`/api/send-message`, {
                                userId: userId,
                                userName: localStorage.getItem('userName'),
                                roomId: this.$route.params.code,
                                content: this.messageInput
                            }).then(response => {
                                // Optional: Handle successful response if needed
                            }).catch(error => {
                                console.error('Error sending message:', error);
                            });

                            // Clear message input after sending
                            this.messageInput = '';
                        }

                    },
                    mounted() {
                        localStorage.removeItem('userId');
                        localStorage.removeItem('userName');
                        // Automatically scroll to bottom when component is mounted or messages array changes
                        this.$watch(() => this.messages, () => {
                            this.$nextTick(() => {
                                const chatBox = this.$refs.chatBox;
                                // Check if the messages array is defined before accessing its properties
                                if (chatBox && chatBox.scrollHeight) {
                                    chatBox.scrollTop = chatBox.scrollHeight;
                                }
                            });
                        });
                    }

            };
</script>
