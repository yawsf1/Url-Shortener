<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import HorizontalNavBar from "@/Components/HorizontalNavBar.vue";

const page = usePage();
const currentComponent = computed(() => page.component);
</script>

<template>
    <header>
        <HorizontalNavBar
            v-if="currentComponent === 'Home' && page.props.auth.user"
            buttonText="logout"
        />
        <HorizontalNavBar
            v-else-if="
                currentComponent === 'Home' && page.props.auth.user === null
            "
            buttonText="register"
        />
    </header>
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
    margin-top: 70px;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: calc(100vh - 70px);
    width: 100%;
}
</style>
