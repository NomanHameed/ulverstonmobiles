<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

defineProps({
    page: { type: Object, default: null },
    programs: { type: Array, default: () => [] },
});

const iconPaths = {
    heart:    'M12 21s-7-4.5-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.5-9.5 9-9.5 9z',
    shield:   'M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z',
    book:     'M4 5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-2V5z M4 5h12v14H4',
    star:     'M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 4 2-7L2 9h7z',
    sparkles: 'M12 2v4M12 18v4M2 12h4M18 12h4M5 5l3 3M16 16l3 3M19 5l-3 3M8 16l-3 3',
};
</script>

<template>
    <Head>
        <title>{{ page?.meta_title || page?.title || 'Special discounts' }}</title>
        <meta name="description" :content="page?.meta_description || page?.intro || 'Exclusive discount programs at Ulverston Mobile — for the people who keep our community moving.'" />
    </Head>

    <section class="relative overflow-hidden bg-gradient-to-br from-brand-blue-50 via-white to-brand-orange-50">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-orange-200/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-brand-blue-200/50 blur-3xl"></div>
        <div class="container-fluid relative py-24 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">{{ page?.eyebrow || 'For our community' }}</p>
            <span class="block mx-auto mt-4 accent-rule"></span>
            <h1 class="mt-5 text-5xl sm:text-7xl font-semibold tracking-tightest">{{ page?.title || 'Special discounts.' }}</h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg text-ink-600">
                {{ page?.intro || 'We value the dedication of those who serve our community. Show your eligibility in store and we’ll take care of the rest.' }}
            </p>
        </div>
    </section>

    <section class="container-fluid py-20">
        <div v-if="programs.length" class="grid sm:grid-cols-2 lg:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <article
                v-for="program in programs"
                :key="program.id"
                class="rounded-3xl border border-ink-100 hover:border-ink-300 transition p-8 flex flex-col"
            >
                <div class="h-12 w-12 rounded-2xl bg-brand-orange-50 text-brand-orange-600 flex items-center justify-center">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path :d="iconPaths[program.icon] || iconPaths.star" />
                    </svg>
                </div>
                <h2 class="mt-6 text-2xl font-semibold tracking-tight">{{ program.name }}</h2>
                <p v-if="program.eligibility" class="mt-1 text-xs uppercase tracking-[0.2em] text-ink-400">{{ program.eligibility }}</p>
                <p v-if="program.description" class="mt-3 text-sm text-ink-500 flex-1">{{ program.description }}</p>
                <div v-if="program.cta_url" class="mt-6">
                    <a :href="program.cta_url" class="btn-outline">{{ program.cta_label || 'Learn more' }}</a>
                </div>
            </article>
        </div>

        <div v-else class="max-w-3xl mx-auto rounded-3xl border border-dashed border-ink-200 p-16 text-center">
            <p class="text-base text-ink-500">No discount programs are currently active.</p>
        </div>

        <div class="relative overflow-hidden mt-20 mx-auto max-w-3xl rounded-3xl surface-deep p-10 text-center">
            <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full bg-brand-orange-500/30 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-brand-orange-400/15 blur-3xl"></div>
            <div class="relative">
                <p class="text-xs uppercase tracking-[0.25em] text-brand-orange-300 font-semibold">Have something else?</p>
                <h3 class="mt-3 text-3xl font-semibold tracking-tight">Bulk orders & corporate accounts.</h3>
                <p class="mt-3 text-sm text-white/80 max-w-xl mx-auto">
                    Outfitting a team or stocking a fleet of devices? We offer dedicated pricing and account support. Reach out and we’ll tailor a quote.
                </p>
                <Link href="/contact" class="btn-accent mt-6">Get in touch</Link>
            </div>
        </div>
    </section>
</template>
