
<template>

        <div class="row py-5 px-5">
            <a class="btn btn-primary" href="{{ route('playRoom') }}">Play Game</a>
        </div>
        <div class="row py-2 px-5">
            <a class="btn btn-primary" href="{{ route('createRoom') }}">Create Room</a>
        </div>

</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";


</script>

<style>

</style>
