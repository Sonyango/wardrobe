<script setup>
const categories = [
    {
        name: 'Clothes',
        items: [
            {
                name: 'Men Clothes',
                description: 'Shorts, Trousers, Jackets',
                imageSrc: 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Men's clothes",
                href: '#',
            },
            {
                name: 'Women Clothes',
                description: 'Dresses, Tops, Skirts',
                imageSrc: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Women's clothes",
                href: '#',
            }
        ]
    },
    {
        name: 'Shoes',
        items: [
            {
                name: 'Men Shoes',
                description: 'Sneakers, Boots, Loafers',
                imageSrc: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
                imageAlt: "Men's shoes",
                href: '#',
            },
            {
                name: 'Women Shoes',
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
    <div class="h-full">


        <div class="grid grid-cols-1 gap-8">
            <div v-for="category in categories" :key="category.name">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">{{ category.name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="item in category.items" :key="item.name" class="group relative bg-white rounded-lg shadow p-6 flex flex-col items-center">
                        <img :src="item.imageSrc" :alt="item.imageAlt" class="w-full h-48 object-cover rounded-lg group-hover:opacity-80" />
                        <h4 class="mt-6 text-lg font-medium text-gray-700">
                            <a :href="item.href">
                                <span class="absolute inset-0" />
                                {{ item.name }}
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>


        
    </div>
</template>

<style scoped>
</style>