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

    <section class="relative overflow-hidden bg-gradient-to-br from-brand-blue-50 via-white to-brand-orange-50">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-orange-200/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-brand-blue-200/50 blur-3xl"></div>
        <div class="container-fluid relative py-24 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">{{ page?.eyebrow || 'Our story' }}</p>
            <span class="block mx-auto mt-4 accent-rule"></span>
            <h1 class="mt-5 text-5xl sm:text-7xl font-semibold tracking-tightest">{{ page?.title || 'About Ulverston Mobile' }}</h1>
            <p v-if="page?.intro" class="mt-6 max-w-2xl mx-auto text-lg text-ink-600">{{ page.intro }}</p>
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
                Ulverston Mobile is the home of precision phone repair on the Cumbrian coast — a place where authenticity, craftsmanship and care meet. We
                service every major brand with the discipline of a manufacturer's workshop, and we stock authentic devices for those upgrading to their next.
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
        <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 text-center">Inside the store</p>
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

    <section class="relative overflow-hidden surface-deep">
        <div class="absolute -top-32 -right-24 h-80 w-80 rounded-full bg-brand-orange-500/25 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-brand-orange-400/15 blur-3xl"></div>
        <div class="container-fluid relative py-24 grid lg:grid-cols-3 gap-10 text-center lg:text-left">
            <div>
                <p class="text-5xl font-semibold tracking-tight text-brand-orange-300">2020</p>
                <p class="mt-2 text-sm text-white/75">Founded with a mission for authentic devices.</p>
            </div>
            <div>
                <p class="text-5xl font-semibold tracking-tight text-brand-orange-300">12-month</p>
                <p class="mt-2 text-sm text-white/75">Warranty included with every certified repair.</p>
            </div>
            <div>
                <p class="text-5xl font-semibold tracking-tight text-brand-orange-300">60-min</p>
                <p class="mt-2 text-sm text-white/75">Average turnaround for in-store services.</p>
            </div>
        </div>
    </section>
</template>
