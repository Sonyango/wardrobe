<template>
     <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">

              <button 
                @click="$emit('toggleSidebar')"
                class="text-gray-400 hover:text-white focus:outline-none mr=4"
                aria-label="Toggle sidebar">
                 <Bars3Icon class="size-6" aria-hidden="true" />
              </button>

              <div class="flex items-center">
              </div>

              <div class="hidden md:block mt-3">
                <div class="ml-4 flex items-center md:ml-6">

                  <!-- Profile dropdown -->
                  <LoginDropdown />
                </div>
              </div>
              <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <DisclosureButton class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                  <span class="absolute -inset-0.5" />
                  <span class="sr-only">Open main menu</span>
                  <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                  <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                </DisclosureButton>
              </div>
            </div>
          </div>

          <DisclosurePanel class="md:hidden">
            <MobileLoginDropdown />
          </DisclosurePanel>
        </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import MobileLoginDropdown from './MobileLoginDropdown.vue';
import LoginDropdown from './LoginDropdown.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
    user: Object,
    //userNavigation: Array,
})
</script>