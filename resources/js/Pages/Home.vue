<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroCarousel from '@/Components/HeroCarousel.vue';
import ProductCard from '@/Components/ProductCard.vue';
import FeatureIcon from '@/Components/FeatureIcon.vue';

defineOptions({ layout: PublicLayout });

const props = defineProps({
    slides:        { type: Array,  default: () => [] },
    brands:        { type: Array,  default: () => [] },
    featured:      { type: Array,  default: () => [] },
    spotlights:    { type: Array,  default: () => [] },
    storeGallery:  { type: Array,  default: () => [] },
    repairGallery: { type: Array,  default: () => [] },
    about:         { type: Object, default: null },
});

const page = usePage();
const site = computed(() => page.props.site || {});

const mapsEmbed = computed(() => {
    if (!site.value.address) return null;
    const q = encodeURIComponent(site.value.address);
    return `https://www.google.com/maps?q=${q}&output=embed`;
});
</script>

<template>
    <Head>
        <title>Ulverston Mobile — Repair, restore, replace.</title>
        <meta
            name="description"
            content="Precision phone repair from certified technicians, plus authentic devices for your next upgrade. Same-day service. Genuine parts. 12-month warranty."
        />
    </Head>

    <HeroCarousel v-if="slides.length" :slides="slides" />

    <!-- Trusted By -->
    <section v-if="brands.length" class="container-fluid py-20 border-b border-ink-100">
        <p class="text-center text-xs uppercase tracking-[0.3em] text-brand-orange-500">Trusted by the brands you trust</p>
        <span class="block mx-auto mt-4 accent-rule"></span>
        <div class="mt-10 grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-x-6 gap-y-8 items-center">
            <div
                v-for="b in brands"
                :key="b.id"
                class="group flex h-16 items-center justify-center rounded-2xl bg-brand-blue-50/60 px-4 transition hover:bg-white hover:ring-2 hover:ring-brand-orange-300/60"
            >
                <img
                    v-if="b.logo_path"
                    :src="`/storage/${b.logo_path}`"
                    :alt="b.name"
                    class="max-h-8 max-w-full object-contain opacity-80 transition group-hover:opacity-100"
                />
                <span v-else class="text-sm font-medium tracking-tight text-ink-600">{{ b.name }}</span>
            </div>
        </div>
    </section>

    <!-- Book a Phone Repair banner -->
    <section class="container-fluid py-24">
        <div class="grid lg:grid-cols-2 gap-10 items-stretch">
            <div class="relative aspect-[4/3] lg:aspect-auto rounded-3xl overflow-hidden surface-deep">
                <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-brand-orange-500/30 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-brand-orange-400/20 blur-3xl"></div>
                <div class="absolute inset-0 flex items-center justify-center text-brand-orange-300/50">
                    <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.6">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.7-3.7a6 6 0 0 1-7.9 7.9L6.4 19.6a2.1 2.1 0 0 1-3-3l6.1-7.1a6 6 0 0 1 7.9-7.9L13.7 5.3z"/>
                    </svg>
                </div>
                <div class="relative z-10 h-full p-10 lg:p-14 flex flex-col justify-end text-white">
                    <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-300 font-semibold">Repair service</p>
                    <h2 class="mt-3 text-4xl sm:text-5xl font-semibold tracking-tightest leading-[1]">
                        Book a phone repair.
                    </h2>
                    <p class="mt-5 max-w-md text-sm text-white/80">
                        We offer the best quality guarantee for every phone repair we undertake. Certified technicians, manufacturer-grade parts, transparent pricing.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-ink-100 p-10 lg:p-14 flex flex-col justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">Save the wait</p>
                    <span class="mt-4 block accent-rule"></span>
                    <h3 class="mt-5 text-3xl sm:text-4xl font-semibold tracking-tightest">
                        Most repairs in 30–60 minutes.
                    </h3>
                    <p class="mt-5 text-ink-500">
                        Book in advance and your device is on the bench the moment you walk in. 12-month warranty on every repair, no exceptions.
                    </p>

                    <ul class="mt-8 space-y-3 text-sm text-ink-700">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-block h-1.5 w-1.5 rounded-full bg-brand-orange-500"></span>
                            Pre-booking available for same-day service
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-block h-1.5 w-1.5 rounded-full bg-brand-orange-500"></span>
                            Free initial diagnosis
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-block h-1.5 w-1.5 rounded-full bg-brand-orange-500"></span>
                            iPhone, Samsung, Pixel, Huawei and more
                        </li>
                    </ul>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <Link href="/repair" class="btn-primary">Book repair service</Link>
                    <Link href="/contact" class="btn-outline">Talk to us</Link>
                </div>
            </div>
        </div>
    </section>

    <!-- Spotlights (alternating) -->
    <template v-if="spotlights.length">
        <section
            v-for="(s, i) in spotlights"
            :key="s.id"
            :class="['py-32', i % 2 === 0 ? 'bg-brand-blue-700 text-white' : 'bg-white text-ink-900']"
        >
            <div class="container-fluid grid lg:grid-cols-2 gap-16 items-center">
                <div :class="i % 2 === 0 ? 'lg:order-1' : 'lg:order-2'">
                    <p
                        class="text-xs uppercase tracking-[0.3em]"
                        :class="i % 2 === 0 ? 'text-brand-orange-300' : 'text-brand-orange-500'"
                    >
                        Spotlight · {{ s.brand_name }}
                    </p>
                    <h2 class="mt-4 text-5xl sm:text-7xl font-semibold tracking-tightest leading-[0.95]">
                        {{ s.name }}
                    </h2>
                    <p
                        v-if="s.short_description"
                        class="mt-6 text-lg max-w-md"
                        :class="i % 2 === 0 ? 'text-ink-300' : 'text-ink-500'"
                    >
                        {{ s.short_description }}
                    </p>

                    <div v-if="s.features.length" class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div
                            v-for="f in s.features"
                            :key="f.id"
                            class="border-t pt-6"
                            :class="i % 2 === 0 ? 'border-white/10' : 'border-ink-200'"
                        >
                            <div
                                class="h-9 w-9 rounded-xl flex items-center justify-center mb-3"
                                :class="i % 2 === 0 ? 'bg-white/10 text-white' : 'bg-ink-50 text-ink-700'"
                            >
                                <FeatureIcon :name="f.icon" :size="18" />
                            </div>
                            <p class="text-sm font-semibold tracking-tight">{{ f.title }}</p>
                            <p
                                v-if="f.description"
                                class="mt-2 text-xs"
                                :class="i % 2 === 0 ? 'text-ink-400' : 'text-ink-500'"
                            >
                                {{ f.description }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-10 flex gap-3">
                        <Link
                            :href="`/products/${s.slug}`"
                            class="btn"
                            :class="i % 2 === 0 ? 'bg-white text-brand-blue-700 hover:bg-brand-blue-50' : 'bg-brand-blue-600 text-white hover:bg-brand-blue-700'"
                        >
                            View details
                        </Link>
                        <Link
                            :href="`/products/${s.slug}#inquire`"
                            class="btn-accent"
                        >
                            Inquire
                        </Link>
                    </div>
                </div>

                <div
                    :class="[
                        'relative aspect-square rounded-3xl overflow-hidden',
                        i % 2 === 0 ? 'bg-brand-blue-800 lg:order-2' : 'bg-brand-blue-50 lg:order-1',
                    ]"
                >
                    <img
                        v-if="s.primary_image"
                        :src="`/storage/${s.primary_image}`"
                        :alt="s.name"
                        class="absolute inset-0 h-full w-full object-cover"
                    />
                    <div
                        v-else
                        class="absolute inset-0 flex items-center justify-center"
                        :class="i % 2 === 0 ? 'text-ink-700' : 'text-ink-300'"
                    >
                        <svg width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5">
                            <rect x="6" y="2" width="12" height="20" rx="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </template>

    <!-- Hot Products -->
    <section v-if="featured.length" class="container-fluid py-24">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">Hot products</p>
                <span class="mt-4 block accent-rule"></span>
                <h2 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tightest">Curated for the everyday extraordinary.</h2>
            </div>
            <Link href="/products" class="hidden sm:inline-flex text-sm font-medium text-brand-orange-600 hover:text-brand-orange-700 transition">
                Shop all →
            </Link>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <ProductCard v-for="p in featured" :key="p.id" :product="p" />
        </div>
    </section>

    <!-- Repair gallery -->
    <section v-if="repairGallery.length" class="container-fluid py-24 border-t border-ink-100">
        <div class="text-center max-w-2xl mx-auto">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">Repair workshop</p>
            <span class="block mx-auto mt-4 accent-rule"></span>
            <h2 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tightest">A precision bench, for every device.</h2>
            <p class="mt-5 text-ink-500">
                From cracked screens to deep board-level work, every repair runs through a dedicated bench, calibrated tools, and certified technicians.
            </p>
        </div>
        <div class="mt-12 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <figure
                v-for="g in repairGallery"
                :key="g.id"
                class="group relative aspect-[4/5] overflow-hidden rounded-2xl bg-ink-100"
            >
                <img
                    v-if="g.image_path"
                    :src="`/storage/${g.image_path}`"
                    :alt="g.alt || g.caption || ''"
                    class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                />
                <figcaption
                    v-if="g.caption"
                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-xs text-white"
                >
                    {{ g.caption }}
                </figcaption>
            </figure>
        </div>
        <div class="mt-10 text-center">
            <Link href="/repair" class="btn-accent">Book a repair</Link>
        </div>
    </section>

    <!-- Store gallery -->
    <section v-if="storeGallery.length" class="container-fluid py-24">
        <div class="text-center max-w-2xl mx-auto">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">Store gallery</p>
            <span class="block mx-auto mt-4 accent-rule"></span>
            <h2 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tightest">Step inside.</h2>
            <p class="mt-5 text-ink-500">
                A considered space, designed for an unhurried look at the device that's right for you.
            </p>
        </div>
        <div class="mt-12 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <figure
                v-for="g in storeGallery"
                :key="g.id"
                class="group relative aspect-square overflow-hidden rounded-2xl bg-ink-100"
            >
                <img
                    v-if="g.image_path"
                    :src="`/storage/${g.image_path}`"
                    :alt="g.alt || g.caption || ''"
                    class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                />
                <figcaption
                    v-if="g.caption"
                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-xs text-white"
                >
                    {{ g.caption }}
                </figcaption>
            </figure>
        </div>
    </section>

    <!-- Why us strip -->
    <section class="container-fluid pb-24">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="group rounded-3xl border border-ink-100 hover:border-brand-orange-300 p-8 transition">
                <div class="h-12 w-12 rounded-2xl bg-brand-orange-50 text-brand-orange-600 flex items-center justify-center group-hover:bg-brand-orange-500 group-hover:text-white transition">
                    <FeatureIcon name="star" />
                </div>
                <h3 class="mt-6 text-xl font-semibold tracking-tight">Genuine, certified parts</h3>
                <p class="mt-2 text-sm text-ink-500">OEM-grade components, sourced through verified suppliers.</p>
            </div>
            <div class="group rounded-3xl border border-ink-100 hover:border-brand-blue-300 p-8 transition">
                <div class="h-12 w-12 rounded-2xl bg-brand-blue-50 text-brand-blue-700 flex items-center justify-center group-hover:bg-brand-blue-600 group-hover:text-white transition">
                    <FeatureIcon name="bolt" />
                </div>
                <h3 class="mt-6 text-xl font-semibold tracking-tight">Most repairs in 60 minutes</h3>
                <p class="mt-2 text-sm text-ink-500">In-store service with technician-grade calibration.</p>
            </div>
            <div class="group rounded-3xl border border-ink-100 hover:border-brand-orange-300 p-8 transition">
                <div class="h-12 w-12 rounded-2xl bg-brand-orange-50 text-brand-orange-600 flex items-center justify-center group-hover:bg-brand-orange-500 group-hover:text-white transition">
                    <FeatureIcon name="shield" />
                </div>
                <h3 class="mt-6 text-xl font-semibold tracking-tight">12-month warranty</h3>
                <p class="mt-2 text-sm text-ink-500">Every repair backed by a one-year service guarantee.</p>
            </div>
        </div>
    </section>

    <!-- About blurb -->
    <section v-if="about" class="relative overflow-hidden bg-gradient-to-br from-brand-blue-50 via-white to-brand-orange-50 py-24">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-orange-200/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-brand-blue-200/50 blur-3xl"></div>
        <div class="container-fluid max-w-3xl text-center relative">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">{{ about.eyebrow || 'About us' }}</p>
            <span class="block mx-auto mt-4 accent-rule"></span>
            <h2 class="mt-5 text-3xl sm:text-4xl font-semibold tracking-tightest">{{ about.title }}</h2>
            <p v-if="about.intro" class="mt-6 text-lg text-ink-700">{{ about.intro }}</p>
            <Link href="/about" class="btn-accent mt-8 inline-flex">Read our story</Link>
        </div>
    </section>

    <!-- Contact strip with map -->
    <section class="container-fluid py-24">
        <div class="grid lg:grid-cols-2 gap-10 items-stretch">
            <div class="rounded-3xl border border-ink-100 p-10 lg:p-14 flex flex-col justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500 font-semibold">Quick support</p>
                    <span class="mt-4 block accent-rule"></span>
                    <h2 class="mt-5 text-3xl sm:text-4xl font-semibold tracking-tightest">Visit, call, or message.</h2>
                    <p class="mt-5 text-ink-500">
                        Drop in for a hands-on look, or reach out and a real person will respond — usually within a few hours.
                    </p>
                </div>

                <dl class="mt-10 space-y-5 text-sm">
                    <div v-if="site.address" class="flex gap-4">
                        <dt class="w-20 text-ink-400 uppercase tracking-[0.2em] text-xs pt-0.5">Address</dt>
                        <dd class="flex-1 text-ink-800 whitespace-pre-line">{{ site.address }}</dd>
                    </div>
                    <div v-if="site.phone" class="flex gap-4">
                        <dt class="w-20 text-ink-400 uppercase tracking-[0.2em] text-xs pt-0.5">Phone</dt>
                        <dd class="flex-1 text-ink-800"><a :href="`tel:${site.phone}`" class="hover:text-ink-900">{{ site.phone }}</a></dd>
                    </div>
                    <div v-if="site.email" class="flex gap-4">
                        <dt class="w-20 text-ink-400 uppercase tracking-[0.2em] text-xs pt-0.5">Email</dt>
                        <dd class="flex-1 text-ink-800"><a :href="`mailto:${site.email}`" class="hover:text-ink-900">{{ site.email }}</a></dd>
                    </div>
                    <div v-if="site.hours" class="flex gap-4">
                        <dt class="w-20 text-ink-400 uppercase tracking-[0.2em] text-xs pt-0.5">Hours</dt>
                        <dd class="flex-1 text-ink-800 whitespace-pre-line">{{ site.hours }}</dd>
                    </div>
                </dl>

                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <Link href="/contact" class="btn-primary">Send a message</Link>
                    <a
                        v-if="site.whatsapp"
                        :href="`https://wa.me/${site.whatsapp.replace(/\D/g, '')}`"
                        target="_blank"
                        rel="noopener"
                        class="btn-accent"
                    >
                        Chat on WhatsApp
                    </a>
                </div>
            </div>

            <div class="relative aspect-[4/3] lg:aspect-auto min-h-[420px] rounded-3xl overflow-hidden bg-ink-100">
                <iframe
                    v-if="mapsEmbed"
                    :src="mapsEmbed"
                    class="absolute inset-0 h-full w-full"
                    style="border:0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Store location"
                ></iframe>
                <div v-else class="absolute inset-0 flex items-center justify-center text-ink-400">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.7">
                        <path d="M12 21s-7-7.5-7-12a7 7 0 0 1 14 0c0 4.5-7 12-7 12z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>
</template>
