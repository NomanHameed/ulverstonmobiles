<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const dismissed = ref(false);

defineExpose({ dismissed });
</script>

<template>
    <div
        v-if="page.props.announcement && !dismissed"
        :style="{
            backgroundColor: page.props.announcement.background_color,
            color: page.props.announcement.text_color,
        }"
        class="relative w-full"
    >
        <div class="container-fluid flex items-center justify-center gap-3 py-2 text-xs sm:text-sm">
            <span class="text-center font-medium tracking-wide">
                {{ page.props.announcement.message }}
                <a
                    v-if="page.props.announcement.link_url"
                    :href="page.props.announcement.link_url"
                    class="ml-1 underline underline-offset-2 hover:opacity-80"
                >
                    {{ page.props.announcement.link_label || 'Learn more' }}
                </a>
            </span>
            <button
                v-if="page.props.announcement.is_dismissible"
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 opacity-60 hover:opacity-100 transition"
                aria-label="Dismiss"
                @click="dismissed = true"
            >
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </div>
</template>
