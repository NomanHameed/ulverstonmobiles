<script setup>
import { computed } from 'vue';

const props = defineProps({
    name:   { type: String, default: '' },
    size:   { type: [Number, String], default: 22 },
    stroke: { type: [Number, String], default: 1.6 },
});

const ICONS = {
    shield:    ['M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z'],
    cpu:       ['M5 7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7z',
                'M9 9h6v6H9z',
                'M3 10v4M3 17v0M21 10v4M10 3h4M10 21h4'],
    aperture:  ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z',
                'M12 3v9l8 4M12 3v9L4 16M12 21v-9L4 8M12 21v-9l8-4'],
    camera:    ['M3 8a2 2 0 0 1 2-2h2l2-2h6l2 2h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z',
                'M12 17a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
    sparkles:  ['M12 3v6M12 15v6M3 12h6M15 12h6',
                'M5.5 5.5l3 3M15.5 15.5l3 3M18.5 5.5l-3 3M8.5 15.5l-3 3'],
    pen:       ['M3 21l4-1 12-12-3-3L4 17l-1 4z',
                'M14 6l3 3'],
    plug:      ['M9 3v6M15 3v6',
                'M7 9h10v3a5 5 0 0 1-10 0V9z',
                'M12 17v4'],
    waves:     ['M3 8c2 0 2-2 5-2s3 2 5 2 3-2 5-2 2 2 3 2',
                'M3 14c2 0 2-2 5-2s3 2 5 2 3-2 5-2 2 2 3 2',
                'M3 20c2 0 2-2 5-2s3 2 5 2 3-2 5-2 2 2 3 2'],
    'circle-dot': ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z',
                   'M12 13.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z'],
    wand:      ['M3 21l11-11', 'M14 4l3 3', 'M17 7l3 3', 'M5 5l1 1', 'M19 13l1 1', 'M9 19l1 1'],
    battery:   ['M3 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z',
                'M21 11v2',
                'M6 10v4M9 10v4'],
    screen:    ['M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z',
                'M11.5 18h1'],
    glass:     ['M5 4h14l-1 16H6L5 4z', 'M5 9h14'],
    speaker:   ['M11 5l-4 4H4v6h3l4 4V5z',
                'M15 9a4 4 0 0 1 0 6',
                'M18 6a8 8 0 0 1 0 12'],
    droplet:   ['M12 3l5 7a6 6 0 1 1-10 0l5-7z'],
    code:      ['M9 8l-4 4 4 4', 'M15 8l4 4-4 4', 'M14 4l-4 16'],
    heart:     ['M12 21s-7-4.5-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.5-9.5 9-9.5 9z'],
    book:      ['M4 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v18l-8-3-8 3V4z'],
    star:      ['M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 4 2-7L2 9h7z'],
    bolt:      ['M13 2L4 14h7l-1 8 9-12h-7l1-8z'],
    headphones:['M3 12a9 9 0 1 1 18 0v6a3 3 0 0 1-3 3h-1v-9h4',
                'M3 12v6a3 3 0 0 0 3 3h1v-9H3'],
    smartphone:['M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z',
                'M11 18h2'],
    watch:     ['M9 7h6l1 4v2l-1 4H9l-1-4v-2l1-4z',
                'M10 7l1-4h2l1 4', 'M10 17l1 4h2l1-4'],
    cable:     ['M4 8h6v8H4zm10 0h6v8h-6z', 'M10 12h4'],
    plus:      ['M12 5v14M5 12h14'],
};

const paths = computed(() => ICONS[props.name] || ICONS.sparkles);
</script>

<template>
    <svg
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        :stroke-width="stroke"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path v-for="(d, i) in paths" :key="i" :d="d" />
    </svg>
</template>
