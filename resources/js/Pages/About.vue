<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

defineProps({
    page: { type: Object, default: null },
    gallery: { type: Array, default: () => [] },
});
</script>

<template>
    <Head>
        <title>{{ page?.meta_title || page?.title || 'About us' }}</title>
        <meta name="description" :content="page?.meta_description || page?.intro || ''" />
    </Head>

    <section class="bg-ink-50">
        <div class="container-fluid py-24 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-ink-400">{{ page?.eyebrow || 'Our story' }}</p>
            <h1 class="mt-3 text-5xl sm:text-7xl font-semibold tracking-tightest">{{ page?.title || 'About Fone Fitness' }}</h1>
            <p v-if="page?.intro" class="mt-6 max-w-2xl mx-auto text-lg text-ink-500">{{ page.intro }}</p>
        </div>
    </section>

    <section class="container-fluid py-20">
        <div
            v-if="page?.body"
            class="prose prose-lg max-w-3xl mx-auto text-ink-700"
            v-html="page.body"
        />
        <div v-else class="max-w-3xl mx-auto space-y-6 text-base text-ink-700 leading-relaxed">
            <p>
                Fone Fitness is a destination for the modern phone enthusiast — a place where authenticity, craftsmanship and care meet. We
                stock the latest devices from the brands that matter, and we service them with the precision of a flagship workshop.
            </p>
            <p>
                Whether you're upgrading to the newest flagship or restoring a beloved daily driver, every interaction is handled by certified
                technicians using OEM-grade parts. Same-day repairs, transparent pricing, and a 12-month warranty come standard.
            </p>
            <p>
                We believe technology should feel personal. From the moment you walk in to the day you walk out with your device perfectly
                tuned, our promise is the same: quality without compromise.
            </p>
        </div>
    </section>

    <section v-if="gallery.length" class="container-fluid pb-24">
        <p class="text-xs uppercase tracking-[0.3em] text-ink-400 text-center">Inside the store</p>
        <h2 class="mt-3 text-center text-4xl sm:text-5xl font-semibold tracking-tightest">Gallery</h2>

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <figure
                v-for="img in gallery"
                :key="img.id"
                class="aspect-square overflow-hidden rounded-3xl bg-ink-50"
            >
                <img
                    :src="`/storage/${img.image_path}`"
                    :alt="img.alt || img.caption || 'Store gallery'"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-700 ease-apple hover:scale-105"
                />
            </figure>
        </div>
    </section>

    <section class="bg-ink-950 text-white">
        <div class="container-fluid py-24 grid lg:grid-cols-3 gap-10 text-center lg:text-left">
            <div>
                <p class="text-4xl font-semibold tracking-tight">2020</p>
                <p class="mt-2 text-sm text-ink-300">Founded with a mission for authentic devices.</p>
            </div>
            <div>
                <p class="text-4xl font-semibold tracking-tight">12-month</p>
                <p class="mt-2 text-sm text-ink-300">Warranty included with every certified repair.</p>
            </div>
            <div>
                <p class="text-4xl font-semibold tracking-tight">60-min</p>
                <p class="mt-2 text-sm text-ink-300">Average turnaround for in-store services.</p>
            </div>
        </div>
    </section>
</template>
