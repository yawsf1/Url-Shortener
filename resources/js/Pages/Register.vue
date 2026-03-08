<script setup>
import RegisterForm from "@/Components/RegisterForm.vue";
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
    <RegisterForm />
</template>
<style scoped></style>
