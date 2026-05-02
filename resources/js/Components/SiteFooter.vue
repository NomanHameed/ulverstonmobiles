<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const site = computed(() => page.props.site ?? {});
const categories = computed(() => page.props.menuCategories ?? []);
const appName = computed(() => page.props.app?.name ?? 'Ulverston Mobile');
const year = new Date().getFullYear();
</script>

<template>
    <footer class="relative overflow-hidden border-t border-ink-100 bg-gradient-to-b from-brand-blue-50/40 to-white mt-24">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-brand-orange-200/30 blur-3xl pointer-events-none"></div>
        <div class="container-fluid relative py-16 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2 md:col-span-1">
                <Link href="/" :aria-label="appName" class="inline-flex items-center">
                    <img src="/img/logo.png" :alt="appName" class="h-10 w-auto" />
                </Link>
                <p class="mt-4 text-sm text-ink-500 max-w-sm">
                    Precision phone repair and authentic devices, with the polish of a flagship store and the discipline of a service department.
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

        <div class="relative border-t border-ink-100 bg-white/60">
            <span class="block h-1 w-full bg-gradient-to-r from-brand-blue-600 via-brand-orange-500 to-brand-blue-600"></span>
            <div class="container-fluid py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-500">
                <p>© {{ year }} Elitefone Services Ltd. All rights reserved.</p>
                <p>Precision repair · Authentic devices</p>
            </div>
        </div>
    </footer>
</template>
