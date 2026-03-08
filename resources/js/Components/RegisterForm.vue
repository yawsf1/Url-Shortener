<template>
    <div v-if="showError" class="flash-error">{{ error }}</div>
    <div v-if="showSuccess" class="flash-success">{{ success }}</div>
    <div class="containerOfRegister">
        <h1 class="bigTitle">shortRL</h1>
        <form @submit.prevent="handleRegister" class="registerForm">
            <div class="goBackContainer" @click="goBackToHome">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="backToLogin">Go back</span>
            </div>
            <div class="goToLoginContainer" @click="goToSignUpPage">
                <span class="backToLogin">Login</span>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
            <h2 class="formTitle">Register</h2>

            <div class="inputGroup">
                <label for="name">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Enter your name"
                    :class="{ error: form.errors.name }"
                />
                <span v-if="form.errors.name" class="error-message">
                    {{ form.errors.name }}
                </span>
            </div>

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
                <span
                    v-if="
                        form.errors.password &&
                        form.errors.password !==
                            'The password field confirmation does not match.'
                    "
                    class="error-message"
                >
                    {{ form.errors.password }}
                </span>
            </div>

            <div class="inputGroup">
                <label for="password_confirmation">Password Confirmation</label>
                <div class="passwordWrapper">
                    <input
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Confirm your password"
                        :class="{ error: form.errors.password }"
                    />
                    <i
                        @click="showConfirmPassword = !showConfirmPassword"
                        :class="
                            showConfirmPassword
                                ? 'fa-solid fa-eye-slash'
                                : 'fa-solid fa-eye'
                        "
                        class="passwordToggle"
                    ></i>
                </div>
                <span
                    v-if="
                        form.errors.password ===
                        'The password field confirmation does not match.'
                    "
                    class="error-message"
                >
                    {{ form.errors.password }}
                </span>
            </div>

            <button type="submit" :disabled="form.processing">
                <span v-if="form.processing">Registering...</span>
                <span v-else>Register</span>
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const error = computed(() => page.props.flash.error);
const success = computed(() => page.props.flash.success);

const showError = ref(false);
const showSuccess = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

function handleRegister() {
    form.post("/auth/register", {
        preserveScroll: true,
        onSuccess: () => {
            form.reset("password", "password_confirmation");
        },
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
    });
}

function goToSignUpPage() {
    router.visit("/login");
}
function goBackToHome() {
    router.visit("/");
}

defineOptions({ layout: undefined });
</script>

<style scoped>
.containerOfRegister {
    width: 50%;
    display: flex;
    min-height: 100vh;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.bigTitle {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Unbounded", "sans-serif";
    margin: 10px;
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
    min-width: 200px;
    position: absolute;
    left: 20px;
    top: 20px;
    cursor: pointer;
    color: #4a4a4a;
}

.goToLoginContainer {
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
