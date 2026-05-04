<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import MegaMenu from './MegaMenu.vue';

const page = usePage();
const scrolled = ref(false);
const menuOpen = ref(false);
const mobileOpen = ref(false);

const categories = computed(() => page.props.menuCategories ?? []);
const appName = computed(() => page.props.app?.name ?? 'Ulverston Mobile');

function handleScroll() {
    scrolled.value = window.scrollY > 8;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <header
        :class="[
            'sticky top-0 z-40 w-full transition-all duration-300 ease-apple',
            scrolled ? 'bg-white/80 backdrop-blur-xl border-b border-ink-100' : 'bg-white border-b border-transparent',
        ]"
        @mouseleave="menuOpen = false"
    >
        <div class="container-fluid">
            <nav class="flex h-20 items-center justify-between gap-6">
                <Link href="/" :aria-label="appName" class="flex items-center">
                    <img src="/img/logo.png" :alt="appName" class="h-12 sm:h-14 w-auto" />
                </Link>

                <div class="hidden md:flex items-center gap-1">
                    <button
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-ink-700 hover:text-brand-blue-700 transition"
                        @mouseenter="menuOpen = true"
                        @click="menuOpen = !menuOpen"
                    >
                        Shop
                    </button>
                    <Link href="/repair" class="px-3 py-2 text-sm font-medium text-ink-700 hover:text-brand-orange-600 transition">Repair</Link>
                    <Link href="/discounts" class="px-3 py-2 text-sm font-medium text-ink-700 hover:text-brand-blue-700 transition">Discounts</Link>
                    <Link href="/about" class="px-3 py-2 text-sm font-medium text-ink-700 hover:text-brand-blue-700 transition">About</Link>
                    <Link href="/contact" class="px-3 py-2 text-sm font-medium text-ink-700 hover:text-brand-blue-700 transition">Contact</Link>
                </div>

                <div class="hidden md:flex items-center gap-2">
                    <a
                        v-if="page.props.site?.whatsapp"
                        :href="`https://wa.me/${page.props.site.whatsapp.replace(/\D/g,'')}`"
                        target="_blank"
                        rel="noopener"
                        class="text-xs uppercase tracking-[0.2em] text-ink-500 hover:text-brand-orange-600 transition"
                    >
                        WhatsApp
                    </a>
                    <Link href="/repair" class="btn-accent text-xs">Book repair</Link>
                </div>

                <button
                    class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-full hover:bg-brand-blue-50"
                    aria-label="Menu"
                    @click="mobileOpen = !mobileOpen"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path v-if="!mobileOpen" d="M3 6h18M3 12h18M3 18h18" />
                        <path v-else d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </nav>
        </div>

        <MegaMenu :open="menuOpen" :categories="categories" @mouseenter="menuOpen = true" />

        <Transition
            enter-active-class="transition ease-apple duration-300"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-apple duration-200"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div v-if="mobileOpen" class="md:hidden border-t border-ink-100 bg-white">
                <div class="container-fluid py-6 space-y-1">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="`/products?category=${cat.slug}`"
                        class="block py-3 border-b border-ink-100 text-base font-medium"
                        @click="mobileOpen = false"
                    >
                        {{ cat.name }}
                    </Link>
                    <Link href="/repair" class="block py-3 border-b border-ink-100 text-base font-medium" @click="mobileOpen = false">Repair</Link>
                    <Link href="/products" class="block py-3 border-b border-ink-100 text-base font-medium" @click="mobileOpen = false">All devices</Link>
                    <Link href="/discounts" class="block py-3 border-b border-ink-100 text-base font-medium" @click="mobileOpen = false">Discounts</Link>
                    <Link href="/about" class="block py-3 border-b border-ink-100 text-base font-medium" @click="mobileOpen = false">About</Link>
                    <Link href="/contact" class="block py-3 text-base font-medium" @click="mobileOpen = false">Contact</Link>
                </div>
            </div>
        </Transition>
    </header>
</template>
