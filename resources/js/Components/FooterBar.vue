<script setup>
import { computed } from 'vue'
import { containerMaxW } from '@/config.js';
import BaseLevel from '@/Components/BaseLevel.vue'
import JustboilLogo from '@/Components/JustboilLogo.vue'

const year = computed(() => new Date().getFullYear())
</script>

<template>
  <footer class="py-2 px-6">
    <BaseLevel :class="containerMaxW">
      <div class="text-center md:text-left">
        <b>&copy;{{ year }}, <a
          href="javascript:void(0)"
        >MailMart</a>.</b>
        <!-- Get more with <a
          href="https://tailwind-vue.justboil.me/"
          target="_blank"
          class="text-blue-600"
        >Premium version</a> -->
      </div>
      <div class="md:py-2">
        <a href="javascript:void(0)">
          <JustboilLogo class="w-auto h-8 md:h-6" />
        </a>
      </div>
    </BaseLevel>
  </footer>
</template>
