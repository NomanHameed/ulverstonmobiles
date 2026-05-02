<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

const props = defineProps({
    brands: { type: Array, default: () => [] },
    issues: { type: Array, default: () => [] },
});

const page = usePage();

const STEPS = [
    { id: 1, key: 'brand',   label: 'Brand' },
    { id: 2, key: 'model',   label: 'Model' },
    { id: 3, key: 'issue',   label: 'Issue' },
    { id: 4, key: 'contact', label: 'Contact' },
];

const step = ref(1);
const success = ref(null);

const form = useForm({
    repair_brand_id: null,
    repair_model_id: null,
    repair_issue_id: null,
    customer_name: '',
    customer_phone: '',
    customer_email: '',
    customer_whatsapp: '',
    preferred_contact: 'whatsapp',
    message: '',
});

const selectedBrand = computed(() =>
    props.brands.find((b) => b.id === form.repair_brand_id) ?? null,
);
const selectedModel = computed(() =>
    selectedBrand.value?.models.find((m) => m.id === form.repair_model_id) ?? null,
);
const selectedIssue = computed(() =>
    props.issues.find((i) => i.id === form.repair_issue_id) ?? null,
);

function pickBrand(brand) {
    form.repair_brand_id = brand.id;
    form.repair_model_id = null;
    next();
}

function pickModel(model) {
    form.repair_model_id = model.id;
    next();
}

function pickIssue(issue) {
    form.repair_issue_id = issue.id;
    next();
}

function next() { if (step.value < STEPS.length) step.value++; }
function back() { if (step.value > 1) step.value--; }

function submit() {
    form.post('/inquiries/repair', {
        preserveScroll: true,
        onSuccess: () => {
            success.value = page.props.flash?.success ?? null;
        },
    });
}

const progress = computed(() => Math.round(((step.value - 1) / (STEPS.length - 1)) * 100));
</script>

<template>
    <Head title="Book a precision repair" />

    <div class="bg-ink-50">
        <div class="container-fluid pt-16 pb-12 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-orange-500">Service</p>
            <h1 class="mt-3 text-5xl sm:text-6xl font-semibold tracking-tightest">Book a precision repair.</h1>
            <p class="mt-4 text-base text-ink-500 max-w-xl mx-auto">
                Tell us about your device. Most repairs are completed in under 60 minutes with OEM-grade parts.
            </p>
        </div>
    </div>

    <div class="container-fluid -mt-6 pb-24">
        <div class="mx-auto max-w-3xl rounded-3xl bg-white shadow-lift border border-ink-100 overflow-hidden">

            <div class="p-6 sm:p-8 border-b border-ink-100">
                <ol class="flex items-center justify-between">
                    <li
                        v-for="(s, idx) in STEPS"
                        :key="s.id"
                        class="flex items-center gap-3"
                        :class="idx < STEPS.length - 1 ? 'flex-1' : ''"
                    >
                        <div class="flex items-center gap-3">
                            <span
                                :class="[
                                    'h-7 w-7 rounded-full inline-flex items-center justify-center text-xs font-semibold transition',
                                    step > s.id ? 'bg-brand-orange-500 text-white' : (step === s.id ? 'bg-brand-blue-600 text-white ring-4 ring-brand-orange-200' : 'bg-ink-100 text-ink-400'),
                                ]"
                            >
                                <svg v-if="step > s.id" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6.5l3 3 5-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span v-else>{{ s.id }}</span>
                            </span>
                            <span :class="['text-xs uppercase tracking-[0.2em] hidden sm:inline', step >= s.id ? 'text-ink-900' : 'text-ink-400']">
                                {{ s.label }}
                            </span>
                        </div>
                        <div v-if="idx < STEPS.length - 1" class="hidden sm:block flex-1 h-px bg-ink-100"></div>
                    </li>
                </ol>
                <div class="sm:hidden mt-4 h-1 rounded-full bg-ink-100 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-brand-blue-600 to-brand-orange-500 transition-all duration-500 ease-apple" :style="{ width: `${progress}%` }"></div>
                </div>
            </div>

            <div v-if="success" class="p-12 text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-2xl">✓</div>
                <h2 class="mt-6 text-3xl font-semibold tracking-tight">Booking received.</h2>
                <p class="mt-2 text-base text-ink-500">{{ success.message }}</p>
                <p v-if="success.tracking_id" class="mt-4 text-sm text-ink-700">
                    Your tracking ID: <span class="font-mono font-semibold">{{ success.tracking_id }}</span>
                </p>
                <p class="mt-6 text-sm text-ink-500">
                    A technician will reach out via your preferred contact method shortly.
                </p>
            </div>

            <div v-else>

            <Transition
                mode="out-in"
                enter-active-class="transition ease-apple duration-300"
                enter-from-class="opacity-0 translate-x-4"
                enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition ease-apple duration-200 absolute"
                leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 -translate-x-4"
            >
                <div v-if="step === 1" key="brand" class="p-6 sm:p-8">
                    <h2 class="text-2xl font-semibold tracking-tight">Which brand is your device?</h2>
                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <button
                            v-for="brand in brands"
                            :key="brand.id"
                            type="button"
                            class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-ink-100 hover:border-ink-900 p-8 transition aspect-square"
                            @click="pickBrand(brand)"
                        >
                            <img v-if="brand.logo" :src="`/storage/${brand.logo}`" :alt="brand.name" class="h-12" />
                            <span v-else class="text-2xl font-semibold tracking-tight">{{ brand.name }}</span>
                            <span class="text-xs text-ink-500 group-hover:text-ink-900 transition">{{ brand.models.length }} models</span>
                        </button>
                    </div>
                </div>

                <div v-else-if="step === 2" key="model" class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold tracking-tight">Pick your model</h2>
                        <button @click="back" class="text-sm text-ink-500 hover:text-ink-900 transition">← Change brand</button>
                    </div>
                    <p class="text-sm text-ink-500 mb-6">{{ selectedBrand?.name }} — {{ selectedBrand?.models.length }} models supported</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <button
                            v-for="model in selectedBrand?.models"
                            :key="model.id"
                            type="button"
                            class="rounded-2xl border border-ink-100 hover:border-ink-900 p-4 text-left transition"
                            @click="pickModel(model)"
                        >
                            <p class="text-sm font-semibold tracking-tight">{{ model.name }}</p>
                            <p v-if="model.release_year" class="mt-1 text-xs text-ink-400">{{ model.release_year }}</p>
                        </button>
                    </div>
                </div>

                <div v-else-if="step === 3" key="issue" class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold tracking-tight">What's wrong?</h2>
                        <button @click="back" class="text-sm text-ink-500 hover:text-ink-900 transition">← Change model</button>
                    </div>
                    <p class="text-sm text-ink-500 mb-6">{{ selectedBrand?.name }} {{ selectedModel?.name }}</p>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <button
                            v-for="issue in issues"
                            :key="issue.id"
                            type="button"
                            class="text-left rounded-2xl border border-ink-100 hover:border-ink-900 p-5 transition"
                            @click="pickIssue(issue)"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold tracking-tight">{{ issue.name }}</p>
                                    <p v-if="issue.description" class="mt-1 text-xs text-ink-500 line-clamp-2">{{ issue.description }}</p>
                                </div>
                                <div v-if="issue.estimated_minutes" class="text-right">
                                    <p class="text-xs text-ink-400">~{{ issue.estimated_minutes }} min</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <form v-else key="contact" @submit.prevent="submit" class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold tracking-tight">How do we reach you?</h2>
                        <button type="button" @click="back" class="text-sm text-ink-500 hover:text-ink-900 transition">← Change issue</button>
                    </div>

                    <div class="rounded-2xl bg-ink-50 p-4 mb-6 flex items-start gap-3">
                        <div class="text-xs uppercase tracking-[0.2em] text-ink-400 flex-shrink-0">Booking</div>
                        <div class="text-sm">
                            <p class="font-semibold">{{ selectedBrand?.name }} {{ selectedModel?.name }}</p>
                            <p class="text-ink-500">{{ selectedIssue?.name }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
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
                            <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Preferred contact</label>
                            <div class="mt-2 grid grid-cols-3 gap-2">
                                <label v-for="opt in ['whatsapp','phone','email']" :key="opt">
                                    <input type="radio" v-model="form.preferred_contact" :value="opt" class="peer sr-only" />
                                    <span class="block text-center py-2 px-3 rounded-xl border border-ink-200 text-xs uppercase tracking-[0.15em] cursor-pointer peer-checked:bg-brand-blue-600 peer-checked:text-white peer-checked:border-brand-blue-600 transition">
                                        {{ opt }}
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-[0.2em] text-ink-500">Anything to add? <span class="normal-case tracking-normal text-ink-400">(optional)</span></label>
                            <textarea v-model="form.message" rows="3" class="mt-1 w-full rounded-xl border-ink-200 focus:border-ink-900 focus:ring-ink-900" placeholder="Tell us when it happened, water exposure, etc."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full mt-6" :disabled="form.processing">
                        {{ form.processing ? 'Booking…' : 'Confirm booking' }}
                    </button>
                </form>
            </Transition>
            </div>
        </div>
    </div>
</template>
