<template>
    <div v-if="showError" class="flash-error">{{ error }}</div>
    <div v-if="showSuccess" class="flash-success">{{ success }}</div>
    <div class="containerOfRegister">
        <h1 class="bigTitle">shortRL</h1>
        <form @submit.prevent="handleLogin" class="registerForm">
            <div class="goToLoginContainer" @click="goToSignUpPage">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="backToLogin">Sign Up</span>
            </div>
            <div class="goBackContainer" @click="goBackToHome">
                <span class="backToLogin">Home</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>

            <h2 class="formTitle">Login</h2>
            <div class="inputGroup">
                <label for="email">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    :class="{ error: form.errors.email }"
                />
                <span v-if="form.errors.email" class="error-message">
                    {{ form.errors.email }}
                </span>
            </div>

            <div class="inputGroup">
                <label for="password">Password</label>
                <div class="passwordWrapper">
                    <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        :class="{ error: form.errors.password }"
                    />
                    <i
                        @click="showPassword = !showPassword"
                        :class="
                            showPassword
                                ? 'fa-solid fa-eye-slash'
                                : 'fa-solid fa-eye'
                        "
                        class="passwordToggle"
                    ></i>
                </div>
                <span v-if="form.errors.password" class="error-message">
                    {{ form.errors.password }}
                </span>
            </div>

            <div class="checkboxGroup">
                <input v-model="form.remember" type="checkbox" id="remember" />
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" :disabled="form.processing">
                <span v-if="form.processing">Login...</span>
                <span v-else>Login</span>
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Layout from "@/Layouts/Layout.vue";

const page = usePage();
const error = computed(() => page.props.flash.error);
const success = computed(() => page.props.flash.success);

const showError = ref(false);
const showSuccess = ref(false);
const showPassword = ref(false);

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

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

function handleLogin() {
    form.post("/auth/login", {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
    });
}

function goBackToHome() {
    router.visit("/");
}
function goToSignUpPage() {
    router.visit("/register");
}

defineOptions({ layout: Layout });
</script>

<style scoped>
.containerOfRegister {
    width: 50%;
    display: flex;
    min-height: calc(100vh - 70px);
    flex-direction: column;
    align-items: center;
}

.bigTitle {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Unbounded", "sans-serif";
    margin: 10px;
    margin-top: 70px;
}

.registerForm {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 50px;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
    border-radius: 6px;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    background-color: #fafafa;
    position: relative;
}

.formTitle {
    font-family: "Poppins", "sans-serif";
    font-size: 1.2rem;
    color: #4a4a4a;
    margin: 0;
}

.inputGroup {
    width: 75%;
    min-width: 300px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.inputGroup label {
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    color: #4a4a4a;
}

.inputGroup input {
    height: 35px;
    padding: 0px 36px 0px 10px;
    width: 100%;
    border-radius: 6px;
    border: none;
    box-shadow: 0px 0px 0px 1px #4a4a4a;
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    background-color: #ffffff;
    box-sizing: border-box;
}

.inputGroup input::placeholder {
    color: #4a4a4a;
    font-family: "Poppins", "sans-serif";
}

.inputGroup input.error {
    box-shadow: 0px 0px 0px 1px red;
}

/* Password wrapper */
.passwordWrapper {
    position: relative;
    width: 100%;
}

.passwordWrapper input {
    width: 100%;
}

.passwordToggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #4a4a4a;
    font-size: 0.85rem;
    user-select: none;
}

.passwordToggle:hover {
    color: #2a2a2a;
}

.checkboxGroup {
    width: 75%;
    min-width: 300px;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 10px;
}

.checkboxGroup input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 36px;
    height: 20px;
    min-width: 36px;
    border-radius: 20px;
    background-color: #d0d0d0;
    box-shadow: none;
    border: none;
    cursor: pointer;
    position: relative;
    transition: background-color 0.3s;
    padding: 0;
}

.checkboxGroup input[type="checkbox"]::after {
    content: "";
    position: absolute;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: #fff;
    top: 3px;
    left: 3px;
    transition: left 0.3s;
}

.checkboxGroup input[type="checkbox"]:checked {
    background-color: #4a4a4a;
}

.checkboxGroup input[type="checkbox"]:checked::after {
    left: 19px;
}

.checkboxGroup label {
    font-family: "Poppins", "sans-serif";
    font-size: 0.9rem;
    color: #4a4a4a;
    cursor: pointer;
}

button {
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
    margin-top: 10px;
}

button:hover:not(:disabled) {
    background-color: #2a2a2a;
}

button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

.goBackContainer {
    gap: 10px;
    display: flex;
    align-items: center;
    justify-content: end;
    min-width: 200px;
    position: absolute;
    right: 20px;
    top: 20px;
    cursor: pointer;
    color: #4a4a4a;
}

.goToLoginContainer {
    gap: 10px;
    display: flex;
    align-items: center;
    justify-content: start;
    min-width: 200px;
    position: absolute;
    left: 20px;
    top: 20px;
    cursor: pointer;
    color: #4a4a4a;
}

@media (max-width: 1000px) {
    .containerOfRegister {
        width: 80%;
    }
    .flash-error,
    .flash-success {
        width: 80%;
    }
}
</style>
