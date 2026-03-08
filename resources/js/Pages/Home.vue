<template>
    <div v-if="showError" class="flash-error">{{ error }}</div>
    <div v-if="showSuccess" class="flash-success">{{ success }}</div>
    <template v-if="shouldSignIn">
        <div class="containerOfShouldSignIn">
            <h2 class="bigTitle">shortRL</h2>
            <div class="signInBox">
                <h3>Sign in to add shortened URLs</h3>
                <button @click="signIn">Register</button>
                <i
                    @click="deleteSignInMessage"
                    class="fa-solid fa-xmark deleteIcon"
                ></i>
            </div>
        </div>
    </template>

    <div class="containerOfHome">
        <h1 class="bigTitle">shortRL</h1>
        <form @submit.prevent="makeTheUrlShort" class="containerOfUrlShortner">
            <div class="inputGroup">
                <input
                    v-model="form.name"
                    type="text"
                    name="nomUrl"
                    id="nomUrl"
                    placeholder="Enter Name of URL"
                    :class="{ error: form.errors.name }"
                />
                <span v-if="form.errors.name" class="error-message">
                    {{ form.errors.name }}
                </span>
            </div>

            <div class="inputGroup">
                <input
                    v-model="form.original_url"
                    type="text"
                    name="OriginUrl"
                    id="OriginUrl"
                    placeholder="Enter URL to shorten (e.g., https://example.com/very/long/path)"
                    :class="{ error: form.errors.original_url }"
                />
                <span v-if="form.errors.original_url" class="error-message">
                    {{ form.errors.original_url }}
                </span>
            </div>

            <button id="shortenButton" type="submit">Shorten</button>

            <template v-if="!user">
                <div class="urlCardList">
                    <div
                        v-for="(url, index) in urls"
                        :key="index"
                        class="urlCard"
                    >
                        <div v-if="url.name" class="urlCard-row">
                            <span class="urlCard-label">Name</span>
                            <span class="urlCard-value">{{ url.name }}</span>
                        </div>
                        <div v-if="url.original" class="urlCard-row">
                            <span class="urlCard-label">Original URL</span>
                            <a
                                :href="url.original"
                                target="_blank"
                                class="urlCard-value urlCard-link"
                                >{{ url.original }}</a
                            >
                        </div>
                        <div v-if="url.shortened" class="urlCard-row">
                            <span class="urlCard-label">Shortened URL</span>
                            <a
                                :href="url.shortened"
                                class="urlCard-value urlCard-link"
                                target="_blank"
                                >{{ url.shortened }}</a
                            >
                        </div>
                        <i
                            @click="deleteContainer(url.id)"
                            class="fa-solid fa-xmark deleteIcon"
                        ></i>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="urlCardList">
                    <div
                        v-for="(url, index) in authUrls"
                        :key="index"
                        class="urlCard"
                    >
                        <div class="urlCard-row">
                            <span class="urlCard-label">Name</span>
                            <span class="urlCard-value">{{ url.name }}</span>
                        </div>
                        <div class="urlCard-row">
                            <span class="urlCard-label">Original URL</span>
                            <a
                                :href="url.original_url"
                                target="_blank"
                                class="urlCard-value urlCard-link"
                                >{{ url.original_url }}</a
                            >
                        </div>
                        <div class="urlCard-row">
                            <span class="urlCard-label">Shortened URL</span>
                            <a
                                :href="url.short_url"
                                class="urlCard-value urlCard-link"
                                target="_blank"
                                >{{ url.short_url }}</a
                            >
                        </div>
                        <i
                            @click="deleteContainer(url.id)"
                            class="fa-solid fa-xmark deleteIcon"
                        ></i>
                    </div>
                </div>
            </template>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import axios from "axios";

import Layout from "@/Layouts/Layout.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);
const success = computed(() => page.props.flash.success);
const error = computed(() => page.props.flash.error);

const showSuccess = ref(false);
const showError = ref(false);

watch(
    success,
    (val) => {
        if (val) {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 3000);
        }
    },
    { immediate: true },
);

watch(
    error,
    (val) => {
        if (val) {
            showError.value = true;
            setTimeout(() => {
                showError.value = false;
            }, 3000);
        }
    },
    { immediate: true },
);

const form = useForm({ name: "", original_url: "" });
const urls = reactive(JSON.parse(localStorage.getItem("urls")) || []);
const i = ref(0);
const shouldSignIn = ref(false);

function saveToStorage() {
    localStorage.setItem("urls", JSON.stringify(urls));
}

async function makeTheUrlShort() {
    if (!user.value) {
        shouldSignIn.value = true;
    } else {
        form.post("/shorten", {
            onSuccess: () => form.reset(),
            onError: (errors) => console.log(errors),
        });
    }
}

function deleteContainer(index) {
    if (!user.value) {
        urls.splice(index, 1);
        saveToStorage();
    }
    router.delete(`/shorten/${index}`);
}

function deleteSignInMessage() {
    shouldSignIn.value = false;
}
function signIn() {
    router.visit("/register");
}

defineOptions({ layout: Layout });
defineProps({ authUrls: Array });
</script>
<style>
.containerOfHome {
    width: 50%;
    display: flex;
    min-height: calc(100vh - 70px);
    flex-direction: column;
    align-items: center;
}
.containerOfHome .bigTitle {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Unbounded", "sans-serif";
    margin: 20px;
}
.containerOfHome .containerOfUrlShortner {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 20px;
    justify-content: center;
    flex-direction: column;
    gap: 20px;
    border-radius: 6px;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    background-color: #fafafa;
}

.containerOfShouldSignIn {
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: rgba(250, 250, 250, 0.92);
    backdrop-filter: blur(2px);
}
.containerOfShouldSignIn .bigTitle {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Unbounded", "sans-serif";
    margin: 20px;
}
.signInBox {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 50px;
    justify-content: center;
    flex-direction: column;
    gap: 20px;
    border-radius: 6px;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    background-color: #fafafa;
    position: relative;
}
.signInBox h3 {
    font-size: 1rem;
    font-weight: 400;
    font-family: "Poppins", "sans-serif";
    color: #4a4a4a;
    margin: 0;
}
.containerOfUrlShortner .inputGroup {
    width: 100%;
    min-width: 300px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.containerOfUrlShortner input {
    height: 35px;
    padding: 0px 10px;
    width: 100%;
    border-radius: 6px;
    border: none;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    background-color: #ffffff;
    box-sizing: border-box;
}
.containerOfUrlShortner input::placeholder {
    color: #4a4a4a;
    font-family: "Poppins", "sans-serif";
}
.containerOfUrlShortner input.error {
    box-shadow: 0px 0px 0px 1px red;
}
.containerOfUrlShortner label {
    width: 75%;
    align-self: flex-start;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    color: #4a4a4a;
}

.containerOfHome button,
.signInBox button {
    border-radius: 6px;
    border: none;
    background-color: #4a4a4a;
    font-family: "Poppins", "sans-serif";
    font-weight: 500;
    color: #fafafa;
    transition: all 0.6s;
    font-size: 1rem;
    width: 110px;
    height: 35px;
    cursor: pointer;
}
.containerOfHome button:hover,
.signInBox button:hover {
    background-color: #2a2a2a;
}

.urlCardList {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.urlCard {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 16px 20px;
    border-radius: 6px;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    background-color: #ffffff;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    color: #4a4a4a;
    box-sizing: border-box;
    position: relative;
}
.urlCard-row {
    display: flex;
    flex-direction: row;
    gap: 10px;
    align-items: baseline;
    flex-wrap: wrap;
}
.urlCard-label {
    font-weight: 600;
    min-width: 110px;
    flex-shrink: 0;
    color: #4a4a4a;
}
.urlCard-value {
    flex: 1;
    word-break: break-all;
    color: #4a4a4a;
}
.urlCard-link {
    color: #4a4a4a;
    text-decoration: underline;
}
.urlCard-link:hover {
    color: #2a2a2a;
}
.deleteIcon {
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
    color: #4a4a4a;
}

.error-message {
    color: red;
    font-size: 0.8rem;
    font-family: "Poppins", "sans-serif";
}

.flash-error {
    width: 50%;
    color: red;
    padding: 1rem;
    background: #ede1d4;
    border-radius: 6px;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    text-align: center;
    box-sizing: border-box;
    margin-bottom: 10px;
}

.flash-success {
    width: 50%;
    color: green;
    padding: 1rem;
    background: #d4edda;
    border-radius: 6px;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    text-align: center;
    box-sizing: border-box;
    margin-bottom: 10px;
}

@media (max-width: 1000px) {
    .containerOfHome {
        width: 80%;
    }
    .signInBox {
        width: 80%;
    }
    .flash-error,
    .flash-success {
        width: 80%;
    }
}
</style>
