<template>
  
  <div class="flex min-h-full">
    
    <Sidebar :collapsed="sidebarCollapsed" :navigation="navigation" @toggleSidebar="sidebarCollapsed = !sidebarCollapsed">
        <template #logo>
            <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" />
        </template>
    </Sidebar>

      <div class="flex-1 flex flex-col min-h-screen bg-gray-50">
        <Navbar 
          :user="user"
          :userNavigation="userNavigation"
          @toggleSidebar="sidebarCollapsed = !sidebarCollapsed" />

        <header class="bg-white shadow-sm">
          <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Categories</h1>
          </div>
        </header>

        <main>
          <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <RouterView />
          </div>
        </main>
      </div>
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import { ref } from 'vue';
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';

const sidebarCollapsed = ref(false);

const user = {
  name: 'Tom Cook',
  email: 'tom@example.com',
  imageUrl:
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
  { 
    name: 'Category', 
    href: '#',
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
  },
]
const userNavigation = [
  { name: 'Your Profile', href: '#' },
  { name: 'Settings', href: '#' },
  { name: 'Sign out', href: '#' },
]
</script>


<style scoped></style>