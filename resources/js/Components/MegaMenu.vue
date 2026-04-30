<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    open: { type: Boolean, default: false },
    categories: { type: Array, default: () => [] },
});

const iconMap = {
    smartphone: 'M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm5 17h.01',
    headphones: 'M3 12a9 9 0 1 1 18 0v6a3 3 0 0 1-3 3h-1v-9h4M3 12v6a3 3 0 0 0 3 3h1v-9H3',
    tablet:     'M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 16h.01',
    watch:      'M9 3h6l1 4v10l-1 4H9l-1-4V7l1-4zm0 4h6v10H9V7z',
    cable:      'M4 8h6v8H4zm10 0h6v8h-6zM10 12h4',
};
</script>

<template>
    <Transition
        enter-active-class="transition ease-apple duration-300"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-apple duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="open"
            class="absolute inset-x-0 top-full bg-white border-t border-ink-100 shadow-lift"
            @click.stop
        >
            <div class="container-fluid py-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-10">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="`/products?category=${cat.slug}`"
                        class="group flex items-start gap-4 hover:bg-ink-50 -m-2 p-2 rounded-xl transition"
                    >
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink-50 text-ink-700 group-hover:bg-ink-900 group-hover:text-white transition">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path :d="iconMap[cat.icon] || iconMap.smartphone" />
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold tracking-tight">{{ cat.name }}</p>
                            <p v-if="cat.description" class="mt-1 text-xs text-ink-500 line-clamp-2">{{ cat.description }}</p>
                            <p v-else class="mt-1 text-xs text-ink-400">Browse {{ cat.name.toLowerCase() }}</p>
                        </div>
                    </Link>
                </div>

                <div class="mt-12 pt-8 border-t border-ink-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Link href="/repair" class="group flex items-center justify-between rounded-2xl bg-ink-900 text-white p-6 hover:bg-ink-700 transition">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-ink-300">Service</p>
                            <p class="mt-2 text-2xl font-semibold">Book a precision repair</p>
                            <p class="mt-1 text-sm text-ink-300">Most repairs in under 60 minutes.</p>
                        </div>
                        <span class="opacity-60 group-hover:opacity-100 group-hover:translate-x-1 transition">→</span>
                    </Link>
                    <Link href="/products" class="group flex items-center justify-between rounded-2xl border border-ink-200 p-6 hover:border-ink-900 transition">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Shop</p>
                            <p class="mt-2 text-2xl font-semibold">All devices</p>
                            <p class="mt-1 text-sm text-ink-500">Authentic, certified, ready to ship.</p>
                        </div>
                        <span class="opacity-40 group-hover:opacity-100 group-hover:translate-x-1 transition">→</span>
                    </Link>
                </div>
            </div>
        </div>
    </Transition>
</template>
