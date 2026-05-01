<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';

defineOptions({ layout: PublicLayout });

const props = defineProps({
    products:   { type: Object, required: true },
    filters:    { type: Object, default: () => ({}) },
    brands:     { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const state = reactive({
    brand:    props.filters.brand || '',
    category: props.filters.category || '',
    sort:     props.filters.sort || 'newest',
});

let timer = null;
watch(state, (val) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get('/products', {
            brand:    val.brand || undefined,
            category: val.category || undefined,
            sort:     val.sort,
        }, { preserveState: true, replace: true, preserveScroll: true });
    }, 150);
});

function clearFilters() {
    state.brand = '';
    state.category = '';
    state.sort = 'newest';
}
</script>

<template>
    <Head title="Shop devices" />

    <div class="container-fluid pt-12 pb-24">
        <header class="mb-12">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500">Catalog</p>
            <h1 class="mt-3 text-5xl sm:text-6xl font-semibold tracking-tightest">All devices.</h1>
            <p class="mt-3 text-base text-ink-500 max-w-xl">Authentic. Certified. Ready to ship — or pick up at the storefront.</p>
        </header>

        <div class="flex flex-col lg:flex-row gap-12">
            <aside class="lg:w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-8">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Category</p>
                        <div class="mt-3 space-y-1">
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-ink-700">
                                <input type="radio" v-model="state.category" value="" class="accent-ink-900" />
                                <span>All</span>
                            </label>
                            <label v-for="c in categories" :key="c.id" class="flex items-center gap-2 cursor-pointer text-sm text-ink-700 hover:text-ink-900">
                                <input type="radio" v-model="state.category" :value="c.slug" class="accent-ink-900" />
                                <span>{{ c.name }}</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Brand</p>
                        <div class="mt-3 space-y-1">
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-ink-700">
                                <input type="radio" v-model="state.brand" value="" class="accent-ink-900" />
                                <span>All</span>
                            </label>
                            <label v-for="b in brands" :key="b.id" class="flex items-center gap-2 cursor-pointer text-sm text-ink-700 hover:text-ink-900">
                                <input type="radio" v-model="state.brand" :value="b.slug" class="accent-ink-900" />
                                <span>{{ b.name }}</span>
                            </label>
                        </div>
                    </div>

                    <button
                        v-if="state.brand || state.category || state.sort !== 'newest'"
                        @click="clearFilters"
                        class="text-xs underline underline-offset-2 text-ink-500 hover:text-ink-900"
                    >
                        Clear filters
                    </button>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-ink-100">
                    <p class="text-sm text-ink-500">{{ products.total }} {{ products.total === 1 ? 'device' : 'devices' }}</p>
                    <div class="flex items-center gap-2 text-sm">
                        <label class="text-ink-500" for="sort">Sort</label>
                        <select id="sort" v-model="state.sort" class="border-ink-200 rounded-full text-sm focus:border-ink-900 focus:ring-ink-900">
                            <option value="newest">Newest</option>
                            <option value="price_asc">Price · Low to high</option>
                            <option value="price_desc">Price · High to low</option>
                            <option value="name">Name</option>
                        </select>
                    </div>
                </div>

                <div v-if="products.data.length" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <ProductCard v-for="p in products.data" :key="p.id" :product="p" />
                </div>
                <div v-else class="rounded-3xl border border-dashed border-ink-200 p-16 text-center">
                    <p class="text-base text-ink-500">No devices match these filters.</p>
                    <button class="mt-4 btn-outline" @click="clearFilters">Clear filters</button>
                </div>

                <nav v-if="products.last_page > 1" class="mt-12 flex justify-center gap-2">
                    <Link
                        v-for="link in products.links"
                        :key="link.label"
                        :href="link.url || ''"
                        v-html="link.label"
                        :class="[
                            'min-w-[2.5rem] h-10 px-3 inline-flex items-center justify-center rounded-full text-sm transition',
                            link.active ? 'bg-ink-900 text-white' : 'text-ink-700 hover:bg-ink-50',
                            !link.url ? 'opacity-30 pointer-events-none' : '',
                        ]"
                        preserve-scroll
                    />
                </nav>
            </div>
        </div>
    </div>
</template>
