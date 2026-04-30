<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const site = computed(() => page.props.site ?? {});
const categories = computed(() => page.props.menuCategories ?? []);
const appName = computed(() => page.props.app?.name ?? 'Fone Fitness');
const year = new Date().getFullYear();
</script>

<template>
    <footer class="border-t border-ink-100 bg-white mt-24">
        <div class="container-fluid py-16 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2 md:col-span-1">
                <Link href="/" class="flex items-center gap-2 font-display tracking-tight font-semibold text-lg">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-ink-900 text-white text-xs font-bold">F</span>
                    {{ appName }}
                </Link>
                <p class="mt-4 text-sm text-ink-500 max-w-sm">
                    Premium devices and certified repair, with the precision of a service department and the polish of a flagship store.
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Shop</p>
                <ul class="mt-4 space-y-2">
                    <li v-for="cat in categories.slice(0,5)" :key="cat.id">
                        <Link :href="`/products?category=${cat.slug}`" class="text-sm text-ink-700 hover:text-ink-900 transition">{{ cat.name }}</Link>
                    </li>
                </ul>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Company</p>
                <ul class="mt-4 space-y-2">
                    <li><Link href="/repair" class="text-sm text-ink-700 hover:text-ink-900 transition">Book a repair</Link></li>
                    <li><Link href="/discounts" class="text-sm text-ink-700 hover:text-ink-900 transition">Discounts</Link></li>
                    <li><Link href="/about" class="text-sm text-ink-700 hover:text-ink-900 transition">About us</Link></li>
                    <li><Link href="/contact" class="text-sm text-ink-700 hover:text-ink-900 transition">Contact</Link></li>
                </ul>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Contact</p>
                <ul class="mt-4 space-y-2 text-sm text-ink-700">
                    <li v-if="site.phone">
                        <a :href="`tel:${site.phone}`" class="hover:text-ink-900 transition">{{ site.phone }}</a>
                    </li>
                    <li v-if="site.whatsapp">
                        <a :href="`https://wa.me/${site.whatsapp.replace(/\D/g,'')}`" target="_blank" rel="noopener" class="hover:text-ink-900 transition">WhatsApp us</a>
                    </li>
                    <li v-if="site.email">
                        <a :href="`mailto:${site.email}`" class="hover:text-ink-900 transition">{{ site.email }}</a>
                    </li>
                    <li v-if="site.address" class="text-ink-500">{{ site.address }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-ink-100">
            <div class="container-fluid py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-400">
                <p>© {{ year }} {{ appName }}. All rights reserved.</p>
                <p>Premium devices · Precision repair</p>
            </div>
        </div>
    </footer>
</template>
