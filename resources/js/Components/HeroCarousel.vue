<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    slides: { type: Array, required: true },
    interval: { type: Number, default: 7000 },
});

const index = ref(0);
let timer = null;

const current = computed(() => props.slides[index.value]);

function next()  { index.value = (index.value + 1) % props.slides.length; restart(); }
function prev()  { index.value = (index.value - 1 + props.slides.length) % props.slides.length; restart(); }
function go(i)   { index.value = i; restart(); }
function restart() {
    clearInterval(timer);
    if (props.slides.length > 1) timer = setInterval(next, props.interval);
}

onMounted(restart);
onBeforeUnmount(() => clearInterval(timer));
</script>

<template>
    <section class="relative overflow-hidden">
        <div class="relative h-[80vh] min-h-[560px] max-h-[780px]">
            <Transition
                enter-active-class="transition ease-apple duration-700"
                enter-from-class="opacity-0 scale-105"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-apple duration-700 absolute inset-0"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
                mode="out-in"
            >
                <div
                    :key="index"
                    :class="[
                        'absolute inset-0 flex flex-col justify-center',
                        current.theme === 'dark' ? 'bg-gradient-to-br from-brand-blue-700 via-brand-blue-800 to-brand-blue-900 text-white' : 'bg-ink-50 text-ink-900',
                    ]"
                >
                    <div
                        v-if="current.image_path"
                        class="absolute inset-0 bg-cover bg-center opacity-90"
                        :style="{ backgroundImage: `url(/storage/${current.image_path})` }"
                    />
                    <div
                        :class="[
                            'absolute inset-0',
                            current.theme === 'dark'
                                ? 'bg-gradient-to-tr from-brand-blue-900/85 via-brand-blue-800/55 to-brand-orange-500/20'
                                : 'bg-gradient-to-t from-white/70 via-white/20 to-transparent',
                        ]"
                    />

                    <div class="container-fluid relative z-10 text-center max-w-3xl mx-auto">
                        <p
                            v-if="current.eyebrow"
                            :class="[
                                'text-xs uppercase tracking-[0.3em] font-semibold',
                                current.theme === 'dark' ? 'text-brand-orange-300' : 'text-brand-orange-600',
                            ]"
                        >
                            {{ current.eyebrow }}
                        </p>
                        <h1 class="mt-4 text-5xl sm:text-7xl md:text-8xl font-semibold tracking-tightest leading-[0.95]">
                            {{ current.headline }}
                        </h1>
                        <p v-if="current.subheadline" class="mt-6 text-base sm:text-lg max-w-xl mx-auto opacity-85">
                            {{ current.subheadline }}
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                v-if="current.primary_cta_url"
                                :href="current.primary_cta_url"
                                class="btn-accent"
                            >
                                {{ current.primary_cta_label }}
                            </Link>
                            <Link
                                v-if="current.secondary_cta_url"
                                :href="current.secondary_cta_url"
                                :class="[
                                    'btn border',
                                    current.theme === 'dark' ? 'border-white/40 text-white hover:bg-white/10' : 'border-brand-blue-200 text-brand-blue-700 hover:border-brand-blue-600',
                                ]"
                            >
                                {{ current.secondary_cta_label }}
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>

        <div v-if="slides.length > 1" class="absolute inset-x-0 bottom-6 z-20 flex items-center justify-center gap-2">
            <button
                v-for="(s, i) in slides"
                :key="i"
                type="button"
                :aria-label="`Slide ${i + 1}`"
                :class="[
                    'h-1 rounded-full transition-all duration-500',
                    i === index
                        ? 'w-10 bg-brand-orange-500 opacity-100'
                        : ['w-4 opacity-40 hover:opacity-70', current.theme === 'dark' ? 'bg-white' : 'bg-ink-700'],
                ]"
                @click="go(i)"
            />
        </div>
    </section>
</template>
