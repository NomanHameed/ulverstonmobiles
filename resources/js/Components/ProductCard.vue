<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: { type: Object, required: true },
});
</script>

<template>
    <Link
        :href="`/products/${product.slug}`"
        class="group block"
    >
        <div class="relative aspect-square overflow-hidden rounded-3xl bg-ink-50">
            <img
                v-if="product.primary_image"
                :src="`/storage/${product.primary_image}`"
                :alt="product.name"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-700 ease-apple group-hover:scale-105"
            />
            <div v-else class="flex h-full w-full items-center justify-center text-ink-300">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <rect x="6" y="2" width="12" height="20" rx="2" />
                    <line x1="11" y1="18" x2="13" y2="18" />
                </svg>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-xs uppercase tracking-[0.2em] text-ink-400">{{ product.brand_name }}</p>
            <h3 class="mt-1 text-base font-semibold tracking-tight truncate">{{ product.name }}</h3>
            <p v-if="product.short_description" class="mt-1 text-xs text-ink-500 line-clamp-2">{{ product.short_description }}</p>
            <p class="mt-2 text-xs font-medium text-brand-orange-600 group-hover:text-brand-orange-700 transition">Inquire for price →</p>
        </div>
    </Link>
</template>
