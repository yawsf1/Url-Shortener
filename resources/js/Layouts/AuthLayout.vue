<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import HorizontalNavBar from "@/Components/HorizontalNavBar.vue";

const page = usePage();
const currentComponent = computed(() => page.component);
const user = computed(() => page.props.auth.user);
</script>

<template>
    <main>
        <slot />
    </main>
</template>

<style scoped>
header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #fafafa;
    z-index: 1000;
    min-height: 60px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0px 1px 1px 0px #4a4a4a;
}

main {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    height: auto;
    width: 100%;
}
</style>
