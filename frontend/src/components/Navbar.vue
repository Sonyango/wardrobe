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
                  <Menu as="div" class="relative ml-3">
                    <div>
                      <MenuButton class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-hidden focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-gray-800">
                       
                        <UserIcon class="size-6 text-gray-300" aria-hidden="true" />
                        <span class="text-white font-medium">My Account</span>
                        <ChevronDownIcon class="size-5 text-gray-400" aria-hidden="true" />
                      </MenuButton>
                    </div>

                    <!-- -->
                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                      <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden">

                        <MenuItem v-slot="{ active }">
                          <button 
                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            @click="$router.push('/login')">
                            Login
                          </button>
                        </MenuItem>
                      </MenuItems>
                    </transition>

                  </Menu>
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
            <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
              <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
            </div>
            <div class="border-t border-gray-700 pt-4 pb-3">
              <div class="flex items-center px-5">
                <UserIcon class="size-8 text-gray-300" aria-hidden="true" />
                <span class="ml-3 text-white font-medium">My Account</span>
                <ChevronDownIcon class="ml-2 size-5 text-gray-400" aria-hidden="true" />
              </div>
              <div class="mt-3 space-y-1 px-2">
                 <button 
                  class="w-full text-left block px-4 py-2 text-sm text-gray-300 hover:bg-gray-100 rounded-md"
                  @click="$router.push('/login')">
                  Login
                 </button>
              </div>
            </div>
          </DisclosurePanel>
        </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon, UserIcon, ChevronDownIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
    user: Object,
    userNavigation: Array,
})
</script>