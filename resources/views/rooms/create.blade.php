<!-- resources/views/rooms/create.blade.php -->
<form action="{{ route('store.room') }}" method="post">
    @csrf
    <label for="name">Room Name:</label>
    <input type="text" name="name" id="name">
    <button type="submit">Create Room</button>
</form>
