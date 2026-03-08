import { defineStore } from "pinia";
import { ref } from "vue";

export const useUrlStore = defineStore("url", () => {
    const newUrl = ref("");

    function shortenTheUrl(url) {
        count.value++;
    }
    const urls = ref([]);

    return { count, increment };
});
