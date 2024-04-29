<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>
    <title>Game Play</title>
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
    <div id="app">
        <app-component></app-component>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- calendar min js -->
    <script src="{{ asset('assets/libs/fullcalendar/main.min.js') }}"></script>

    <!-- Calendar init -->
    <script src="{{ asset('assets/js/pages/calendar.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
