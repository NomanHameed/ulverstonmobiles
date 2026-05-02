<script setup>
import { ref, watch, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    open: { type: Boolean, default: false },
    productId: { type: Number, default: null },
    variantId: { type: Number, default: null },
    productName: { type: String, default: '' },
    variantLabel: { type: String, default: '' },
});

const emit = defineEmits(['close']);
const page = usePage();

const form = useForm({
    customer_name: '',
    customer_phone: '',
    customer_email: '',
    customer_whatsapp: '',
    preferred_contact: 'whatsapp',
    message: '',
    product_id: props.productId,
    product_variant_id: props.variantId,
});

watch(() => props.productId, (v) => (form.product_id = v));
watch(() => props.variantId, (v) => (form.product_variant_id = v));

const success = ref(null);

function submit() {
    form.transform((data) => ({
        ...data,
        product_id: props.productId,
        product_variant_id: props.variantId,
    }))
    .post('/inquiries/product', {
        preserveScroll: true,
        onSuccess: (response) => {
            success.value = page.props.flash?.success ?? null;
            form.reset('customer_name', 'customer_phone', 'customer_email', 'customer_whatsapp', 'message');
        },
    });
}

const whatsappLink = computed(() => {
    const number = (page.props.site?.whatsapp || '').replace(/\D/g, '');
    if (!number) return null;
    const text = encodeURIComponent(`Hi! I'm interested in the ${props.productName}${props.variantLabel ? ' (' + props.variantLabel + ')' : ''}. Could you share availability and pricing?`);
    return `https://wa.me/${number}?text=${text}`;
});
</script>

<template>
    <Transition
        enter-active-class="transition ease-apple duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-apple duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="open" class="fixed inset-0 z-50 bg-brand-blue-950/60 backdrop-blur-sm" @click.self="emit('close')">
            <Transition
                enter-active-class="transition ease-apple duration-400"
                enter-from-class="opacity-0 translate-y-8"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-apple duration-200"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-4"
                appear
            >
                <div class="absolute inset-0 flex items-end sm:items-center justify-center p-4">
                    <div class="w-full max-w-lg bg-white rounded-3xl shadow-lift overflow-hidden">
                        <header class="flex items-start justify-between p-6 pb-4 border-b border-ink-100">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-ink-400">Inquiry</p>
                                <h3 class="mt-1 text-xl font-semibold tracking-tight">{{ productName }}</h3>
                                <p v-if="variantLabel" class="text-sm text-ink-500">{{ variantLabel }}</p>
                            </div>
                            <button type="button" class="text-ink-400 hover:text-ink-900 transition" @click="emit('close')" aria-label="Close">
                                <svg width="18" height="18" viewBox="0 0 14 14" fill="none"><path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </button>
                        </header>

                        <div v-if="success" class="p-6">
                            <div class="rounded-2xl bg-ink-50 p-6 text-center">
                                <p class="text-2xl">✓</p>
                                <p class="mt-2 text-base font-semibold">{{ success.message || 'Inquiry received.' }}</p>
                                <p v-if="success.tracking_id" class="mt-2 text-sm text-ink-500">
                                    Tracking ID: <span class="font-mono">{{ success.tracking_id }}</span>
                                </p>
                                <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener" class="btn-primary mt-6">
                                    Continue on WhatsApp
                                </a>
                            </div>
                        </div>

                        <form v-else @submit.prevent="submit" class="p-6 space-y-4">
                            <div>
                                <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Name</label>
                                <input v-model="form.customer_name" type="text" required class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                                <p v-if="form.errors.customer_name" class="mt-1 text-xs text-red-600">{{ form.errors.customer_name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Phone</label>
                                    <input v-model="form.customer_phone" type="tel" required class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                                    <p v-if="form.errors.customer_phone" class="mt-1 text-xs text-red-600">{{ form.errors.customer_phone }}</p>
                                </div>
                                <div>
                                    <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Email</label>
                                    <input v-model="form.customer_email" type="email" class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Preferred contact</label>
                                <div class="mt-2 flex gap-2">
                                    <label v-for="opt in ['whatsapp','phone','email']" :key="opt" class="flex-1">
                                        <input type="radio" v-model="form.preferred_contact" :value="opt" class="peer sr-only" />
                                        <span class="block text-center py-2 px-3 rounded-xl border border-ink-200 text-xs uppercase tracking-[0.15em] cursor-pointer peer-checked:bg-brand-blue-600 peer-checked:text-white peer-checked:border-brand-blue-600 transition">
                                            {{ opt }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Message <span class="text-ink-400 normal-case tracking-normal">(optional)</span></label>
                                <textarea v-model="form.message" rows="3" class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900"></textarea>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                                <button type="button" class="btn-outline flex-1" @click="emit('close')">Cancel</button>
                                <button type="submit" class="btn-primary flex-1" :disabled="form.processing">
                                    {{ form.processing ? 'Sending…' : 'Send inquiry' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
