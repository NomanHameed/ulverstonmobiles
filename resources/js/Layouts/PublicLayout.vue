<script setup>
import AnnouncementBar from '@/Components/AnnouncementBar.vue';
import SiteHeader from '@/Components/SiteHeader.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
</script>

<template>
    <div class="min-h-screen bg-white text-ink-900 flex flex-col">
        <AnnouncementBar />
        <SiteHeader />
        <main class="flex-1">
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>
