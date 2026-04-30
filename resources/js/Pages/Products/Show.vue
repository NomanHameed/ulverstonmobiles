<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import InquiryModal from '@/Components/InquiryModal.vue';
import FeatureIcon from '@/Components/FeatureIcon.vue';

defineOptions({ layout: PublicLayout });

const props = defineProps({
    product: { type: Object, required: true },
});

const page = usePage();

const activeImage = ref(props.product.images.find((i) => i.is_primary)?.id ?? props.product.images[0]?.id ?? null);
const activeVariantId = ref(
    props.product.variants.find((v) => v.is_default)?.id
    ?? props.product.variants[0]?.id
    ?? null,
);

const activeVariant = computed(() =>
    props.product.variants.find((v) => v.id === activeVariantId.value) ?? null,
);

const colors = computed(() => {
    const seen = new Set();
    return props.product.variants
        .filter((v) => v.color)
        .filter((v) => !seen.has(v.color) && (seen.add(v.color), true));
});

const storages = computed(() => {
    if (!activeVariant.value) return [];
    return props.product.variants.filter((v) => v.color === activeVariant.value.color && v.storage);
});

function pickColor(color) {
    const next = props.product.variants.find((v) => v.color === color) ?? null;
    if (next) activeVariantId.value = next.id;
}

const inquireOpen = ref(false);

function formatPrice(value, currency = props.product.currency) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency, maximumFractionDigits: 0 }).format(value);
}

const displayPrice = computed(() =>
    activeVariant.value ? activeVariant.value.effective_price : props.product.current_price,
);

const whatsappLink = computed(() => {
    const number = (page.props.site?.whatsapp || '').replace(/\D/g, '');
    if (!number) return null;
    const variantText = activeVariant.value ? ` (${activeVariant.value.label})` : '';
    const text = encodeURIComponent(`Hi! I'm interested in the ${props.product.name}${variantText}. Could you share availability?`);
    return `https://wa.me/${number}?text=${text}`;
});

const stockText = computed(() => {
    if (props.product.stock_status === 'pre_order') return 'Available for pre-order';
    if (props.product.stock_status === 'out_of_stock') return 'Currently out of stock';
    return 'In stock — ships in 1–2 business days';
});
</script>

<template>
    <Head>
        <title>{{ product.meta_title || product.name }}</title>
        <meta name="description" :content="product.meta_description || product.short_description || ''" />
    </Head>

    <div class="container-fluid pt-8 pb-24">
        <nav class="text-xs text-ink-400 mb-8 flex gap-2">
            <Link href="/products" class="hover:text-ink-900">Shop</Link>
            <span>/</span>
            <Link v-if="product.category" :href="`/products?category=${product.category.slug}`" class="hover:text-ink-900">
                {{ product.category.name }}
            </Link>
            <span v-if="product.category">/</span>
            <span class="text-ink-700">{{ product.name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <div class="lg:sticky lg:top-24">
                <div class="aspect-square overflow-hidden rounded-3xl bg-ink-50">
                    <img
                        v-if="activeImage && product.images.find((i) => i.id === activeImage)"
                        :src="`/storage/${product.images.find((i) => i.id === activeImage).path}`"
                        :alt="product.images.find((i) => i.id === activeImage)?.alt || product.name"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="flex h-full items-center justify-center text-ink-300">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.7"><rect x="6" y="2" width="12" height="20" rx="2"/></svg>
                    </div>
                </div>
                <div v-if="product.images.length > 1" class="mt-4 grid grid-cols-5 gap-3">
                    <button
                        v-for="img in product.images"
                        :key="img.id"
                        type="button"
                        :class="[
                            'aspect-square rounded-2xl overflow-hidden border-2 transition',
                            activeImage === img.id ? 'border-ink-900' : 'border-transparent hover:border-ink-200',
                        ]"
                        @click="activeImage = img.id"
                    >
                        <img :src="`/storage/${img.path}`" :alt="img.alt || ''" class="h-full w-full object-cover" />
                    </button>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-ink-400">{{ product.brand?.name }}</p>
                <h1 class="mt-3 text-4xl sm:text-5xl font-semibold tracking-tightest">{{ product.name }}</h1>
                <p v-if="product.short_description" class="mt-4 text-lg text-ink-500">{{ product.short_description }}</p>

                <div class="mt-8 flex items-baseline gap-3">
                    <p class="text-3xl font-semibold tracking-tight">{{ formatPrice(displayPrice) }}</p>
                    <p v-if="product.is_on_sale" class="text-base text-ink-400 line-through">
                        {{ formatPrice(product.base_price) }}
                    </p>
                </div>
                <p class="mt-2 text-sm" :class="product.stock_status === 'in_stock' ? 'text-emerald-600' : 'text-amber-600'">
                    {{ stockText }}
                </p>

                <div v-if="colors.length" class="mt-10">
                    <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Color</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <button
                            v-for="v in colors"
                            :key="v.color"
                            type="button"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-full border transition',
                                activeVariant?.color === v.color ? 'border-ink-900 bg-ink-50' : 'border-ink-200 hover:border-ink-400',
                            ]"
                            @click="pickColor(v.color)"
                        >
                            <span v-if="v.color_hex" class="h-4 w-4 rounded-full border border-black/10" :style="{ backgroundColor: v.color_hex }"></span>
                            <span class="text-sm">{{ v.color }}</span>
                        </button>
                    </div>
                </div>

                <div v-if="storages.length > 1" class="mt-8">
                    <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Storage</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <button
                            v-for="v in storages"
                            :key="v.id"
                            type="button"
                            :class="[
                                'px-5 py-3 rounded-full border text-sm transition',
                                activeVariantId === v.id ? 'border-ink-900 bg-ink-900 text-white' : 'border-ink-200 hover:border-ink-400',
                            ]"
                            @click="activeVariantId = v.id"
                        >
                            <span class="font-medium">{{ v.storage }}</span>
                            <span class="ml-2 opacity-70">{{ formatPrice(v.effective_price) }}</span>
                        </button>
                    </div>
                </div>

                <div id="inquire" class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button class="btn-primary" @click="inquireOpen = true">Inquire about this</button>
                    <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener" class="btn-outline">Quick chat on WhatsApp</a>
                </div>

                <div v-if="product.description" class="mt-12 prose prose-sm max-w-none text-ink-700" v-html="product.description" />
            </div>
        </div>

        <section v-if="product.features.length" class="mt-32">
            <p class="text-xs uppercase tracking-[0.3em] text-ink-400 text-center">Features</p>
            <h2 class="mt-3 text-center text-4xl sm:text-5xl font-semibold tracking-tightest">Engineered for the way you live.</h2>

            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="f in product.features"
                    :key="f.id"
                    class="rounded-3xl border border-ink-100 p-8 hover:border-ink-300 transition"
                >
                    <img
                        v-if="f.image_path"
                        :src="`/storage/${f.image_path}`"
                        :alt="f.title"
                        class="h-12 w-12 rounded-2xl object-cover"
                    />
                    <div v-else class="h-12 w-12 rounded-2xl bg-ink-50 flex items-center justify-center text-ink-700">
                        <FeatureIcon :name="f.icon" />
                    </div>
                    <h3 class="mt-6 text-xl font-semibold tracking-tight">{{ f.title }}</h3>
                    <p v-if="f.description" class="mt-2 text-sm text-ink-500">{{ f.description }}</p>
                </div>
            </div>
        </section>
    </div>

    <InquiryModal
        :open="inquireOpen"
        :product-id="product.id"
        :variant-id="activeVariant?.id ?? null"
        :product-name="product.name"
        :variant-label="activeVariant?.label || ''"
        @close="inquireOpen = false"
    />
</template>
