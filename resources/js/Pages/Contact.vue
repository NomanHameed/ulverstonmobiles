<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

defineProps({
    page: { type: Object, default: null },
});

const page = usePage();
const success = ref(null);

const form = useForm({
    customer_name: '',
    customer_phone: '',
    customer_email: '',
    customer_whatsapp: '',
    preferred_contact: 'whatsapp',
    subject: '',
    message: '',
});

function submit() {
    form.post('/inquiries/general', {
        preserveScroll: true,
        onSuccess: () => {
            success.value = page.props.flash?.success ?? null;
            form.reset();
        },
    });
}

const site = computed(() => page.props.site ?? {});

const whatsappLink = computed(() => {
    const number = (site.value.whatsapp || '').replace(/\D/g, '');
    return number ? `https://wa.me/${number}` : null;
});
</script>

<template>
    <Head>
        <title>{{ page?.meta_title || page?.title || 'Contact' }}</title>
        <meta name="description" :content="page?.meta_description || page?.intro || 'Get in touch with the Ulverston Mobile team.'" />
    </Head>

    <section class="bg-ink-50">
        <div class="container-fluid py-24 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500">{{ page?.eyebrow || 'Reach the team' }}</p>
            <h1 class="mt-3 text-5xl sm:text-7xl font-semibold tracking-tightest">{{ page?.title || 'Contact us' }}</h1>
            <p class="mt-6 max-w-xl mx-auto text-base text-ink-500">
                {{ page?.intro || 'We love hearing from our customers. Reach out and we’ll respond within one business day.' }}
            </p>
        </div>
    </section>

    <div class="container-fluid -mt-12 pb-24">
        <div class="mx-auto max-w-5xl grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 rounded-3xl bg-white border border-ink-100 shadow-soft p-8 sm:p-10">
                <div v-if="success" class="text-center py-12">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-2xl">✓</div>
                    <h2 class="mt-6 text-2xl font-semibold tracking-tight">Message sent.</h2>
                    <p class="mt-2 text-ink-500">{{ success.message }}</p>
                    <p v-if="success.tracking_id" class="mt-3 text-sm text-ink-700">
                        Reference: <span class="font-mono font-semibold">{{ success.tracking_id }}</span>
                    </p>
                </div>

                <form v-else @submit.prevent="submit" class="space-y-4">
                    <h2 class="text-2xl font-semibold tracking-tight">Send a message</h2>
                    <p class="text-sm text-ink-500">A real person will read this — usually a reply within a few hours.</p>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Name</label>
                        <input v-model="form.customer_name" type="text" required class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                        <p v-if="form.errors.customer_name" class="mt-1 text-xs text-red-600">{{ form.errors.customer_name }}</p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Phone</label>
                            <input v-model="form.customer_phone" type="tel" required class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                            <p v-if="form.errors.customer_phone" class="mt-1 text-xs text-red-600">{{ form.errors.customer_phone }}</p>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Email <span class="normal-case tracking-normal text-ink-400">(optional)</span></label>
                            <input v-model="form.customer_email" type="email" class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Subject <span class="normal-case tracking-normal text-ink-400">(optional)</span></label>
                        <input v-model="form.subject" type="text" class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" placeholder="What's this about?" />
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Preferred contact</label>
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            <label v-for="opt in ['whatsapp','phone','email']" :key="opt">
                                <input type="radio" v-model="form.preferred_contact" :value="opt" class="peer sr-only" />
                                <span class="block text-center py-2 px-3 rounded-xl border border-ink-200 text-xs uppercase tracking-[0.15em] cursor-pointer peer-checked:bg-ink-900 peer-checked:text-white peer-checked:border-ink-900 transition">
                                    {{ opt }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Message</label>
                        <textarea v-model="form.message" rows="5" required class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" placeholder="Tell us how we can help…"></textarea>
                        <p v-if="form.errors.message" class="mt-1 text-xs text-red-600">{{ form.errors.message }}</p>
                    </div>

                    <button type="submit" class="btn-primary w-full" :disabled="form.processing">
                        {{ form.processing ? 'Sending…' : 'Send message' }}
                    </button>
                </form>
            </div>

            <aside class="lg:col-span-2 space-y-6">
                <section class="rounded-3xl bg-ink-900 text-white p-8">
                    <p class="text-xs uppercase tracking-[0.25em] text-ink-300">Store information</p>
                    <ul class="mt-6 space-y-5 text-sm">
                        <li v-if="site.email">
                            <p class="text-xs uppercase tracking-[0.2em] text-ink-300">Email</p>
                            <a :href="`mailto:${site.email}`" class="mt-1 block hover:opacity-80">{{ site.email }}</a>
                        </li>
                        <li v-if="site.phone">
                            <p class="text-xs uppercase tracking-[0.2em] text-ink-300">Sales & support</p>
                            <a :href="`tel:${site.phone}`" class="mt-1 block hover:opacity-80">{{ site.phone }}</a>
                        </li>
                        <li v-if="site.whatsapp">
                            <p class="text-xs uppercase tracking-[0.2em] text-ink-300">WhatsApp</p>
                            <a :href="whatsappLink" target="_blank" rel="noopener" class="mt-1 block hover:opacity-80">{{ site.whatsapp }}</a>
                        </li>
                        <li v-if="site.address">
                            <p class="text-xs uppercase tracking-[0.2em] text-ink-300">Storefront</p>
                            <p class="mt-1">{{ site.address }}</p>
                        </li>
                        <li v-if="site.hours">
                            <p class="text-xs uppercase tracking-[0.2em] text-ink-300">Opening hours</p>
                            <p class="mt-1 whitespace-pre-line">{{ site.hours }}</p>
                        </li>
                    </ul>
                </section>

                <section class="rounded-3xl border border-ink-100 p-8 text-center">
                    <p class="text-sm text-ink-500">Prefer a quick chat?</p>
                    <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener" class="btn-primary mt-4">
                        Message us on WhatsApp
                    </a>
                </section>
            </aside>
        </div>
    </div>
</template>
