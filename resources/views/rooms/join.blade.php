<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Join Room: {{ $room->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
        }

        .main-content {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .user-list {
            flex: 1;
            width: 20rem;
            background-color: #f0f0f0;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-list h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .game-section {
            flex: 1;
            width: 50rem;
            background-color: #eaeaea;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chat-interface {
            flex: 1;
            width: 30rem;
            background-color: #ffffff;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chat-box {
            background-color: #f9f9f9;
            padding: 10px;
            box-sizing: border-box;
            overflow-y: auto;
            height: calc(100% - 40px);
            /* Subtract height of input and button */
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .chat-box p {
            margin: 5px 0;
        }

        .message-input {
            display: flex;
            margin-top: 10px;
        }

        .message-input input {
            flex: 1;
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .message-input button {
            padding: 6px 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .footer {
            padding: 10px;
            background-color: #f0f0f0;
            text-align: center;
            border-top: 1px solid #ccc;
            box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Chat Room: {{ $room->name }}</h1>
        </div>
        <div class="main-content">
            <div class="user-list">
                <h2>Users</h2>
                <ul id="user-list">
                    <li>{{ $randomName }}</li> <!-- Display random name -->
                </ul>
            </div>
            <div class="game-section">
                <h2>Game Section</h2>
                <!-- Add game content here -->
            </div>
            <div class="chat-interface">
                <h2>Chat Interface</h2>
                <div class="chat-box" id="chat-box">
                    <!-- Chat messages will be displayed here -->
                </div>
                <form class="message-input" id="chat-form">
                    @csrf <!-- Include the CSRF token -->
                    <input type="text" id="message-input" placeholder="Type your message...">
                    <button type="submit">Send</button>
                    <input type="hidden" id="room-code" value="{{ $room->code }}">
                </form>
            </div>
        </div>
        <div class="footer">
            <p>Your referral link:</p>
            <a
                href="http://127.0.0.1:8000/join-room/{{ $room->code }}">http://127.0.0.1:8000/join-room/{{ $room->code }}</a>
        </div>
    </div>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <!-- Your JavaScript code -->
    <script>
        // Define roomCode variable
        let roomCode = '{{ $room->code }}';

        // Initialize Pusher with your credentials
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true,
        });

        // Subscribe to the chat room channel
        const channel = pusher.subscribe('chat-room.{{ $room->id }}');
        console.log(channel);
        // Function to display messages in the chat box
        function displayMessages(messages) {
            let chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear previous messages

            messages.forEach(message => {
                let messageElement = document.createElement('p');
                messageElement.textContent = message.content;
                chatBox.appendChild(messageElement);
            });

            // Scroll the chat box to the bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Listen for new messages
        channel.bind('new-message', function(data) {
            // Append the new message to the chat box
            let chatBox = document.getElementById('chat-box');
            let messageElement = document.createElement('p');
            messageElement.textContent = data.message.content;
            chatBox.appendChild(messageElement);

            // Scroll the chat box to the bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        });


        // Submit message form via AJAX
        $('#chat-form').submit(function(event) {
            event.preventDefault();
            console.log('Form submitted');

            let messageInput = $('#message-input').val();

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '/send-message',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    room_code: roomCode,
                    message: messageInput
                },
                success: function(response) {
                    // Clear the message input field on success
                    $('#message-input').val('');

                    // Display all messages in the chat box
                    displayMessages(response.all_messages);
                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                }
            });
        });

        //Ajax call when tab destroy
        $(window).on('beforeunload', function() {
            // Send an AJAX request to remove the session data
            $.ajax({
                url: '{{ route('remove.session.data') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Session data removed successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to remove session data:', error);
                }
            });
        });
    </script>
</body>

</html>
