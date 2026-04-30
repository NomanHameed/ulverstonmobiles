<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: { type: Object, required: true },
});

function formatPrice(value, currency = 'USD') {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency, maximumFractionDigits: 0 }).format(value);
}
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
            <span
                v-if="product.is_on_sale"
                class="absolute left-4 top-4 rounded-full bg-ink-900 px-3 py-1 text-[10px] uppercase tracking-[0.2em] text-white"
            >
                Save
            </span>
        </div>

        <div class="mt-4 flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs uppercase tracking-[0.2em] text-ink-400">{{ product.brand_name }}</p>
                <h3 class="mt-1 text-base font-semibold tracking-tight truncate">{{ product.name }}</h3>
                <p v-if="product.short_description" class="mt-1 text-xs text-ink-500 line-clamp-2">{{ product.short_description }}</p>
            </div>
            <div class="text-right">
                <p class="text-base font-semibold">{{ formatPrice(product.current_price, product.currency) }}</p>
                <p v-if="product.is_on_sale" class="text-xs text-ink-400 line-through">
                    {{ formatPrice(product.base_price, product.currency) }}
                </p>
            </div>
        </div>
    </Link>
</template>
