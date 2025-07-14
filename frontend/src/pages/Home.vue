<script setup>
const categories = [
    {
        name: 'Men Clothes',
        items: [
            {
                name: 'Shirts',
                description: 'Shorts, Trousers, Jackets',
                imageSrc: 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Men's clothes",
                href: '#',
            },
            {
                name: 'Trousers',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'T-shirts & Pollos',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'Hoodies & Sweatshirts',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'Jackets & Coats',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'Suits & Blazers',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'Underwear',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            {
                name: 'Socks',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            },
            
        ]
    },
    {
        name: 'Women Clothes',
        items: [
            {
                name: 'Dresses',
                description: 'Sneakers, Boots, Loafers',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Men's shoes",
                href: '#',
            },
            {
                name: 'Skirts',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Trousers & Leggings',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Shirts & Blouses',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Suits & Blazers',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Sweaters & Cardigans',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Shorts & Jumpsuits',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            },
            {
                name: 'Underwear & Lingerie',
                description: 'Heels, Flats, Sandals',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's shoes",
                href: '#',
            }
        ]
    }
];
</script>

<template>
    <div class="relative h-[calc(100vh-15rem)] overflow-y-auto py-8">

        <div class="flex flex-col gap-12">
            <div v-for="category in categories" :key="category.name">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">{{ category.name }}</h3>
                <div class="flex flex-row gap-6">
                    <div class="group bg-white rounded-lg shadow flex flex-col items-center justify-center"
                        v-for="item in category.items"
                        :key="item.name"
                        style="width: 200px; height: 200px;">
                        <img :src="item.imageSrc" 
                             :alt="item.imageAlt" class="w-20 h-20 object-cover rounded-lg group-hover:opacity-80"
                        />
                        <h4 class="mt-2 text-sm font-medium text-gray-700 text-center">
                            <a :href="item.href">
                                {{ item.name }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500 mt-1 text-center">{{ item.description }}</p>
                        <button class="mt-2 px-2 py-1 bg-gray-800 text-white rounded hover:bg-gray-700 text-xs">
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