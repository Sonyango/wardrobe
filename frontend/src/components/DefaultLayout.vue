<template>
  <div class="flex min-h-screen">
    
    <div class="hidden md:block">
        <Sidebar 
          :collapsed="sidebarCollapsed" 
          :navigation="navigation" 
          @toggleSidebar="sidebarCollapsed = !sidebarCollapsed"
          class="sticky top-0 z-30">
          <template #logo>
              <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
          </template>
        </Sidebar>
      </div>
    
    

      <div class="flex-1 flex flex-col min-h-screen">
        <Navbar 
          :user="user"
          :userNavigation="userNavigation"
          :mobileOpen="mobileSidebarOpen"
          @toggleSidebar="sidebarCollapsed = !sidebarCollapsed"
          @toggleMobileSidebar="mobileSidebarOpen = !mobileSidebarOpen" 
          class="sticky top-0 z-40" />

        <!-- <header class="bg-white shadow-sm">
          <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Categories</h1>
          </div>
        </header> -->

        <main class="flex-1 overflow-y-auto bg-gray-50">
          <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <RouterView />
          </div>
        </main>
      </div>

      <MobileSidebar
        v-if="mobileSidebarOpen"
        :navigation="navigation"
        @close="mobileSidebarOpen = false"
      />

  </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import { ref, computed } from 'vue';
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';
import MobileSidebar from './MobileSidebar.vue';
import { useAuthStore } from '../stores/auth';

const sidebarCollapsed = ref(false);
const mobileSidebarOpen = ref(false);
const authStore = useAuthStore();

const user = {
  name: 'Tom Cook',
  email: 'tom@example.com',
  imageUrl:
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}

const navigation = computed(() => {
  if (!authStore.isAuthenticated) {
    return [
    { 
    name: 'Clothes', 
    href: '#',
    icon: 'SparklesIcon', 
    current: false,
    children: [
      {
        name: 'Men Clothes',
        href: '#',
        icon: 'UserIcon',
        children: [
          { name: 'Shirts', href: '#', icon: 'SparklesIcon' },
          { name: 'Trousers', href: '#', icon: 'SparklesIcon' },
          { name: 'T-Shirts & Pollos', href: '#', icon: 'SparklesIcon' },
          { name: 'Hoodies & Sweatshirts', href: '#', icon: 'SparklesIcon' },
          { name: 'Jackets & Coats', href: '#', icon: 'SparklesIcon' },
          { name: 'Suites & Blazers', href: '#', icon: 'SparklesIcon' },
          { name: 'Underwears', href: '#', icon: 'SparklesIcon' },
          { name: 'Socks', href: '#', icon: 'SparklesIcon' },
        ],
      },
      {
        name: 'Women Clothes',
        href: '#',
        icon: 'UserIcon',
        children: [
          { name: 'Dresses', href:'#', icon: 'SparklesIcon' },
          { name: 'Skirts', href:'#', icon: 'SparklesIcon' },
          { name: 'Trousers & Leggings', href:'#', icon: 'SparklesIcon' },
          { name: 'Tops & Blouses', href:'#', icon: 'SparklesIcon' },
          { name: 'Suites & Blazers', href:'#', icon: 'SparklesIcon' },
          { name: 'Sweaters & Cardigans', href:'#', icon: 'SparklesIcon' },
          { name: 'Shorts & Jumpsuites', href:'#', icon: 'SparklesIcon' },
          { name: 'Underwears & Lingerie', href:'#', icon: 'SparklesIcon' },
        ],
      },
    ], 
  },
  { 
    name: 'Shoes', 
    href: '#',
    icon: 'ShoppingBagIcon', 
    children: [
      { name: 'Men Shoes', 
        href: '#', 
        icon: 'UserIcon',
        children: [
          { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
          { name: 'Boots', href: '#', icon: 'SparklesIcon' },
          { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
          { name: 'Sandals & Flip-flops', href: '#', icon: 'SparklesIcon' },
          { name: 'Formal Shoes', href: '#', icon: 'SparklesIcon' },
          { name: 'Athletic Shoes', href: '#', icon: 'SparklesIcon' },
        ] 
      },
      { name: 'Women Shoes', href: '#', icon: 'UserIcon',
        children: [
          { name: 'Heels', href: '#', icon: 'SparklesIcon' },
          { name: 'Flats', href: '#', icon: 'SparklesIcon' },
          { name: 'Boots', href: '#', icon: 'SparklesIcon' },
          { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
          { name: 'Sandals', href: '#', icon: 'SparklesIcon' },
          { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
        ]
      },
    ] 
  },
    ];
  }

  return [
    { 
    name: 'Dashboard', 
    to: { name: 'dashboard' },
    icon: 'Squares2X2Icon', 
    current: true 
  },
  { 
    name: 'Clothes', 
    href: '#',
    icon: 'SparklesIcon', 
    current: false,
    children: [
      {
        name: 'Men Clothes',
        href: '#',
        icon: 'UserIcon',
        children: [
          { name: 'Shirts', href: '#', icon: 'SparklesIcon' },
          { name: 'Trousers', href: '#', icon: 'SparklesIcon' },
          { name: 'T-Shirts & Pollos', href: '#', icon: 'SparklesIcon' },
          { name: 'Hoodies & Sweatshirts', href: '#', icon: 'SparklesIcon' },
          { name: 'Jackets & Coats', href: '#', icon: 'SparklesIcon' },
          { name: 'Suites & Blazers', href: '#', icon: 'SparklesIcon' },
          { name: 'Underwears', href: '#', icon: 'SparklesIcon' },
          { name: 'Socks', href: '#', icon: 'SparklesIcon' },
        ],
      },
      {
        name: 'Women Clothes',
        href: '#',
        icon: 'UserIcon',
        children: [
          { name: 'Dresses', href:'#', icon: 'SparklesIcon' },
          { name: 'Skirts', href:'#', icon: 'SparklesIcon' },
          { name: 'Trousers & Leggings', href:'#', icon: 'SparklesIcon' },
          { name: 'Tops & Blouses', href:'#', icon: 'SparklesIcon' },
          { name: 'Suites & Blazers', href:'#', icon: 'SparklesIcon' },
          { name: 'Sweaters & Cardigans', href:'#', icon: 'SparklesIcon' },
          { name: 'Shorts & Jumpsuites', href:'#', icon: 'SparklesIcon' },
          { name: 'Underwears & Lingerie', href:'#', icon: 'SparklesIcon' },
        ],
      },
    ], 
  },
  { 
    name: 'Shoes', 
    href: '#',
    icon: 'ShoppingBagIcon', 
    children: [
      { name: 'Men Shoes', 
        href: '#', 
        icon: 'UserIcon',
        children: [
          { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
          { name: 'Boots', href: '#', icon: 'SparklesIcon' },
          { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
          { name: 'Sandals & Flip-flops', href: '#', icon: 'SparklesIcon' },
          { name: 'Formal Shoes', href: '#', icon: 'SparklesIcon' },
          { name: 'Athletic Shoes', href: '#', icon: 'SparklesIcon' },
        ] 
      },
      { name: 'Women Shoes', href: '#', icon: 'UserIcon',
        children: [
          { name: 'Heels', href: '#', icon: 'SparklesIcon' },
          { name: 'Flats', href: '#', icon: 'SparklesIcon' },
          { name: 'Boots', href: '#', icon: 'SparklesIcon' },
          { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
          { name: 'Sandals', href: '#', icon: 'SparklesIcon' },
          { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
        ]
      },
    ] 
  }
  ];
});

// const navigation = [
//   { 
//     name: 'Dashboard', 
//     to: { name: 'dashboard' },
//     icon: 'Squares2X2Icon', 
//     current: true 
//   },
//   { 
//     name: 'Clothes', 
//     href: '#',
//     icon: 'SparklesIcon', 
//     current: false,
//     children: [
//       {
//         name: 'Men Clothes',
//         href: '#',
//         icon: 'UserIcon',
//         children: [
//           { name: 'Shirts', href: '#', icon: 'SparklesIcon' },
//           { name: 'Trousers', href: '#', icon: 'SparklesIcon' },
//           { name: 'T-Shirts & Pollos', href: '#', icon: 'SparklesIcon' },
//           { name: 'Hoodies & Sweatshirts', href: '#', icon: 'SparklesIcon' },
//           { name: 'Jackets & Coats', href: '#', icon: 'SparklesIcon' },
//           { name: 'Suites & Blazers', href: '#', icon: 'SparklesIcon' },
//           { name: 'Underwears', href: '#', icon: 'SparklesIcon' },
//           { name: 'Socks', href: '#', icon: 'SparklesIcon' },
//         ],
//       },
//       {
//         name: 'Women Clothes',
//         href: '#',
//         icon: 'UserIcon',
//         children: [
//           { name: 'Dresses', href:'#', icon: 'SparklesIcon' },
//           { name: 'Skirts', href:'#', icon: 'SparklesIcon' },
//           { name: 'Trousers & Leggings', href:'#', icon: 'SparklesIcon' },
//           { name: 'Tops & Blouses', href:'#', icon: 'SparklesIcon' },
//           { name: 'Suites & Blazers', href:'#', icon: 'SparklesIcon' },
//           { name: 'Sweaters & Cardigans', href:'#', icon: 'SparklesIcon' },
//           { name: 'Shorts & Jumpsuites', href:'#', icon: 'SparklesIcon' },
//           { name: 'Underwears & Lingerie', href:'#', icon: 'SparklesIcon' },
//         ],
//       },
//     ], 
//   },
//   { 
//     name: 'Shoes', 
//     href: '#',
//     icon: 'ShoppingBagIcon', 
//     children: [
//       { name: 'Men Shoes', 
//         href: '#', 
//         icon: 'UserIcon',
//         children: [
//           { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
//           { name: 'Boots', href: '#', icon: 'SparklesIcon' },
//           { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
//           { name: 'Sandals & Flip-flops', href: '#', icon: 'SparklesIcon' },
//           { name: 'Formal Shoes', href: '#', icon: 'SparklesIcon' },
//           { name: 'Athletic Shoes', href: '#', icon: 'SparklesIcon' },
//         ] 
//       },
//       { name: 'Women Shoes', href: '#', icon: 'UserIcon',
//         children: [
//           { name: 'Heels', href: '#', icon: 'SparklesIcon' },
//           { name: 'Flats', href: '#', icon: 'SparklesIcon' },
//           { name: 'Boots', href: '#', icon: 'SparklesIcon' },
//           { name: 'Sneakers', href: '#', icon: 'SparklesIcon' },
//           { name: 'Sandals', href: '#', icon: 'SparklesIcon' },
//           { name: 'Loafers & Slip-ons', href: '#', icon: 'SparklesIcon' },
//         ]
//       },
//     ] 
//   },
// ]
const userNavigation = [
  { name: 'Your Profile', href: '#' },
  { name: 'Settings', href: '#' },
  { name: 'Sign out', href: '#' },
]
</script>


<style scoped></style>