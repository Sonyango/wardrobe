<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { UserIcon, ChevronDownIcon, ArrowRightEndOnRectangleIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<template>
    <Menu as="div" class="relative ml-3">
                    <div>
                      <MenuButton class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-hidden focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-gray-800">
                       
                        <UserIcon class="size-6 text-gray-300 mr-1" aria-hidden="true" />
                        <span class="text-white font-medium">My Account</span>
                        <ChevronDownIcon class="size-5 text-gray-400 ml-1" aria-hidden="true" />
                      </MenuButton>
                    </div>

                    <!-- -->
                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                      <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden">

                        <MenuItem v-slot="{ active }">
                          <button 
                            class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            @click="router.push('/login')">
                            <span>Login</span>
                            <ArrowRightEndOnRectangleIcon class="size-5 text-gray-400 ml-auto" aria-hidden="true" />
                          </button>
                        </MenuItem>
                      </MenuItems>
                    </transition>

                  </Menu>
</template>