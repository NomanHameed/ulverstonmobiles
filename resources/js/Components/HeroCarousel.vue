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
                        current.theme === 'dark' ? 'bg-ink-950 text-white' : 'bg-ink-50 text-ink-900',
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
                                ? 'bg-gradient-to-t from-black/70 via-black/30 to-transparent'
                                : 'bg-gradient-to-t from-white/70 via-white/20 to-transparent',
                        ]"
                    />

                    <div class="container-fluid relative z-10 text-center max-w-3xl mx-auto">
                        <p v-if="current.eyebrow" class="text-xs uppercase tracking-[0.3em] opacity-80">
                            {{ current.eyebrow }}
                        </p>
                        <h1 class="mt-4 text-5xl sm:text-7xl md:text-8xl font-semibold tracking-tightest leading-[0.95]">
                            {{ current.headline }}
                        </h1>
                        <p v-if="current.subheadline" class="mt-6 text-base sm:text-lg max-w-xl mx-auto opacity-80">
                            {{ current.subheadline }}
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                v-if="current.primary_cta_url"
                                :href="current.primary_cta_url"
                                :class="[
                                    'btn',
                                    current.theme === 'dark' ? 'bg-white text-ink-900 hover:bg-ink-100' : 'bg-ink-900 text-white hover:bg-ink-700',
                                ]"
                            >
                                {{ current.primary_cta_label }}
                            </Link>
                            <Link
                                v-if="current.secondary_cta_url"
                                :href="current.secondary_cta_url"
                                :class="[
                                    'btn border',
                                    current.theme === 'dark' ? 'border-white/30 text-white hover:bg-white/10' : 'border-ink-200 text-ink-900 hover:border-ink-900',
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
                    i === index ? 'w-10 bg-current opacity-80' : 'w-4 bg-current opacity-30 hover:opacity-50',
                    current.theme === 'dark' ? 'text-white' : 'text-ink-900',
                ]"
                @click="go(i)"
            />
        </div>
    </section>
</template>
