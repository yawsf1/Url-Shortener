<script setup>
import LoginForm from "../Components/LoginForm.vue";
import AuthLayout from "../Layouts/AuthLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const success = computed(() => page.props.flash.success);

defineOptions({
    layout: AuthLayout,
});
</script>
<template>
    <LoginForm />
</template>
<style scoped></style>
