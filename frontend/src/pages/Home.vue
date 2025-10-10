<script setup>
import { categories } from '../data/categories';
// const categories = [
//     {
//         name: 'Men Clothes',
//         items: [
//             {
//                 name: 'Shirts',
//                 description: 'Shorts, Trousers, Jackets',
//                 imageSrc: 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Men's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Trousers',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'T-shirts & Pollos',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Hoodies & Sweatshirts',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Jackets & Coats',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Suits & Blazers',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Underwear',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
//             {
//                 name: 'Socks',
//                 description: 'Dresses, Tops, Skirts',
//                 imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's clothes",
//                 href: '#',
//             },
            
//         ]
//     },
//     {
//         name: 'Women Clothes',
//         items: [
//             {
//                 name: 'Dresses',
//                 description: 'Sneakers, Boots, Loafers',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Men's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Skirts',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Trousers & Leggings',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Shirts & Blouses',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Suits & Blazers',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Sweaters & Cardigans',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Shorts & Jumpsuits',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             },
//             {
//                 name: 'Underwear & Lingerie',
//                 description: 'Heels, Flats, Sandals',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: "Women's shoes",
//                 href: '#',
//             }
//         ]
//     },
//     {
//         name: 'Men Shoes',
//         items: [
//             {
//                 name: 'Sneakers',
//                 description: 'Casual footwear for everyday use',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Men\'s sneakers',
//                 href: '#',
//             },
//             {
//                 name: 'Boots',
//                 description: 'Durable and stylish boots for all occasions',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Men\'s boots',
//                 href: '#',
//             },
//             {
//                 name: 'Loafers',
//                 description: 'Comfortable loafers for a smart casual look',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Men\'s loafers',
//                 href: '#',
//             }
//         ]
//     },
//     {
//         name: 'Women Shoes',
//         items: [
//             {
//                 name: 'Heels',
//                 description: 'Elegant heels for special occasions',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Women\'s heels',
//                 href: '#',
//             },
//             {
//                 name: 'Flats',
//                 description: 'Comfortable flats for everyday wear',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Women\'s flats',
//                 href: '#',
//             },
//             {
//                 name: 'Sandals',
//                 description: 'Stylish sandals for warm weather',
//                 imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
//                 imageAlt: 'Women\'s sandals',
//                 href: '#',
//             }
//         ]
//     }
// ];
</script>

<template>
    <div class="relative h-[calc(100vh-15rem)] overflow-y-auto py-8">

        <div class="flex flex-col gap-12">
            <div v-for="category in categories" :key="category.name">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">{{ category.name }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    <div class="group bg-white rounded-lg shadow flex flex-col items-center justify-center aspect-square w-full"
                        v-for="item in category.items"
                        :key="item.name"
                        >
                        <img :src="item.imageSrc" 
                             :alt="item.imageAlt" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg group-hover:opacity-80"
                        />
                        <h4 class="mt-2 text-xs sm:text-sm font-medium text-gray-700 text-center">
                            <!-- <a :href="item.href">
                                {{ item.name }}
                            </a> -->
                            <router-link :to="{
                                name: 'CategoryItems',
                                params: { 
                                    categoryName: category.name.replace(/\s+/g, '-').toLowerCase(),
                                    itemType: item.name.replace(/\s+/g, '-').toLocaleLowerCase()
                                }
                            }" class="hover:underline">
                                {{ item.name }}
                            </router-link>
                        </h4>
                        <p class="text-[10px] sm:text-xs text-gray-500 mt-1 text-center">{{ item.description }}</p>
                        <button class="mt-2 px-2 py-1 bg-gray-800 text-white rounded hover:bg-gray-700 text-[10px] sm:text-xs">
                            View
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        

        
</template>

<style scoped>
</style>