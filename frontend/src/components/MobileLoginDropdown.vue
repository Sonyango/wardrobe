<script setup>
import { UserIcon, ChevronDownIcon, ArrowRightEndOnRectangleIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<template>
    <div class="border-t border-gray-700 pt-4 pb-3">
              <div class="flex items-center px-5">
                <UserIcon class="size-8 text-gray-300" aria-hidden="true" />
                <span class="ml-3 text-white font-medium">My Account</span>
                <ChevronDownIcon class="ml-2 size-5 text-gray-400" aria-hidden="true" />
              </div>
              <div class="mt-3 space-y-1 px-2">
                 <button 
                  class="w-full flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-100 rounded-md"
                  @click="router.push('/login')">
                  <span>Login</span>
                  <ArrowRightEndOnRectangleIcon class="size-5 text-gray-400 ml-3" aria-hidden="true" />
                 </button>
              </div>
            </div>
</template>