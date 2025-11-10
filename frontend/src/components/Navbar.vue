<template>
     <Disclosure as="nav" class="sticky top-0 z-40 bg-gray-800" v-slot="{ open }">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
              <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">

                      <button 
                          v-if="!mobileOpen"
                          @click="$emit('toggleMobileSidebar')" 
                          class="mr-3 text-gray-300 hover:text-white md:hidden focus:outline-none"
                          aria-label="Open sidebar"
                          >
                        <Bars3Icon class="h-6 w-6"></Bars3Icon>
                      </button>

                      <router-link :to="{ name: 'home' }" class="text-white font-semibold">
                        Wardrobe
                      </router-link>
                    </div>

                    <div class="flex items-center">
                      <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                          <!-- Profile dropdown -->
                        <LoginDropdown v-if="!authStore.isAuthenticated" />

                        <UserMenu v-else :user="authStore.user" @logout="handleLogout" />
                        </div>
                      </div>
                      
                      <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-none">
                          <span class="sr-only">Open main menu</span>
                          <Bars3Icon v-if="!open" class="h-6 w-6" aria-hidden="true" />
                          <XMarkIcon v-else class="h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                      </div>
                  </div>
              </div>
          </div>

          <DisclosurePanel class="md:hidden">
            <MobileLoginDropdown v-if="!authStore.isAuthenticated" />
            <MobileUserMenu v-else :user="authStore.user" @logout="handleLogout" />
          </DisclosurePanel>
        </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import MobileLoginDropdown from './MobileLoginDropdown.vue';
import LoginDropdown from './LoginDropdown.vue';
import UserMenu from './UserMenu.vue';
import MobileUserMenu from './MobileUserMenu.vue';
import { useAuthStore } from '../stores/auth';
import { logout } from '../services/auth';
import { useRouter } from 'vue-router';


const router = useRouter();
const authStore = useAuthStore();

const emit = defineEmits(['toggleSidebar', 'toggleMobileSidebar']);

const props = defineProps({
    
    mobileOpen: { type: Boolean, default: false },
});

async function handleLogout() {
  try {
    await logout();
    authStore.clearUser();
    router.push({ name: 'login' });
  } catch (error) {
    console.error('Logout failed:', error);
  }
}
</script>