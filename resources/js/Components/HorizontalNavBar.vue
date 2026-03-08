<script setup>
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
defineProps({
    buttonText: String,
});

function moveToRegister() {
    router.visit("/register");
}
function moveToHome() {
    router.visit("/");
}
function logOut() {
    router.post("/auth/logout");
}
</script>
<template>
    <nav class="navigationBar">
        <div
            v-if="buttonText == 'register'"
            @click="moveToRegister"
            class="goToLoginContainer"
        >
            <span class="backToLogin">{{ buttonText }}</span>
            <i class="fa-solid fa-arrow-right"></i>
        </div>
        <div
            v-else-if="buttonText == 'home'"
            @click="moveToHome"
            class="goToLoginContainer"
        >
            <span class="backToLogin">{{ buttonText }}</span>
            <i class="fa-solid fa-arrow-right"></i>
        </div>
        <div
            v-else-if="buttonText == 'logout'"
            @click="logOut"
            class="goToLoginContainer"
        >
            <span class="backToLogin">{{ buttonText }}</span>
            <i class="fa-solid fa-arrow-right"></i>
        </div>
    </nav>
</template>

<style scoped>
.navigationBar {
    width: 60%;
    position: relative;
    align-items: center;
    display: flex;
    justify-content: flex-end;
}
.navigationBar .loginButton {
    border-radius: 6px;
    border: none;
    background-color: #fafafa;
    font-family: "Poppins", "sans-serif";
    font-weight: 400;
    color: black;
    transition: all 0.6s;
    font-size: 1.1rem;
    width: 110px;
    height: 35px;
    cursor: pointer;
}
.navigationBar .loginButton:hover {
    color: #fafafa;
    background-color: #4a4a4a;
}
.goToLoginContainer {
    gap: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    right: 20px;
    cursor: pointer;
    height: 40px;
    min-width: 140px;
    color: #fafafa;
    border-radius: 3px;
    background-color: #4a4a4a;
}
</style>
