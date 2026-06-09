<script setup>
import { ref, defineComponent, h } from 'vue';
import { useRoute, RouterLink } from 'vue-router';
import * as HeroIcons from '@heroicons/vue/24/outline';
import Logo from '../components/Logo.vue';

const slug = (s = '') => String(s).trim().replace(/\s+/g, '-').toLowerCase();

const props = defineProps({
    collapsed: { type: Boolean, default: false },
    navigation: { type: Array, default: () => [] },
});

const emit = defineEmits(['toggleSidebar']);

const openMenus = ref({});

function toggleMenu(name) {
    if (name === 'openSidebar') {
        emit('toggleSidebar');
        return;
    }
    openMenus.value[name] = !openMenus.value[name];
}

const SidebarItem = defineComponent({
    name: 'SidebarItem',
    props: {
        item: { type: Object, required: true },
        level: { type: Number, default: 0 },
        parentName: { type: String, default: null },
        collapsed: { type: Boolean, default: false },
        openMenus: { type: Object, required: true },

    },
    setup(props) {
        const route = useRoute();
        
        const hasChildren = () => props.item.children && props.item.children.length > 0;

        const computeTo = () => {
            if (props.item.to) return props.item.to;
            if (props.item.route) return props.item.route;

            if (props.parentName) {
                return {
                    name: 'CategoryItems',
                    params: {
                        categoryName: slug(props.parentName),
                        itemType: slug(props.item.name),
                    },
                };
            }

            return {
                name: 'CategoryItems',
                params: {
                    categoryName: slug(props.item.name),
                },
            };
        };

        const isActiveLink = () => {
            const to = computeTo();
            if (!to || !to.params) return false;
            const { categoryName, itemType } = to.params;
            if (categoryName && route.params.categoryName !== undefined) {
                if (slug(route.params.categoryName) !== slug(categoryName)) return false;
                if (itemType) {
                    return slug(route.params.itemType || '') === slug(itemType);
                }
                return route.name === 'CategoryItems' && (!route.params.itemType || route.params.itemType === undefined);
            }
            return false;
        };
        return { hasChildren, computeTo, isActiveLink, slug, HeroIcons };

    },

    render () {
        const p = this.$props;
        const paddingLeft = `${Math.min(12 + p.level * 12, 48)}px`;
        const showName = !(p.collapsed && p.level === 0);

        const IconComp = p.item.icon && (HeroIcons[p.item.icon] || HeroIcons['QuestionMarkCircleIcon']);

        if (this.hasChildren()) {
            const isOpen = !!(p.openMenus && p.openMenus[p.item.name]);
            return h('div', { class: 'flex flex-col' }, [
                h('div', {
                    class: [
                        'flex items-center justify-between px-2 py-2 rounded cursor-ponter',
                        this.isActiveLink() ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                    ],
                    style: { paddingLeft },
                    onClick: (e) => {e.stopPropagation(); p.openMenus[p.item.name] = !p.openMenus[p.item.name]; }
                }, [
                    h('div', { class: 'flex items-center gap-2' }, [
                        IconComp ? h(IconComp, { class: 'w-5 h-5' }) : null,
                        showName ? h('span', { class: 'text-sm' }, p.item.name) : null,
                    ]),
                    showName ? h(HeroIcons.ChevronDownIcon, {
                        class: ['w-4 h-4 transition-transform', isOpen ? 'transform rotate-180' : '']
                    }) : null
                ]),
                isOpen ?
                    h('ul', { class: 'ml-2 space-y-1' }, p.item.children.map((child, i) =>
                        h('li', { key: i }, [ 
                            h(SidebarItem, {
                                item: child,
                                level: p.level + 1,
                                parentName: p.item.name,
                                collapsed: p.collapsed,
                                openMenus: p.openMenus,
                            })
                        ])
                    )) : null
            ]);
        }

        const to = this.computeTo();
        const linkClass = [
            'block px-2 py-2 rounded text-sm',
            this.isActiveLink() ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ];

        return h('div', { class: 'flex items-center' }, [
            h(RouterLink, { to }, {
                default: () => [
                    h('div', { class: linkClass, style: { paddingLeft } }, [
                        IconComp ? h(IconComp, { class: 'w-5 h-5 inline-block mr-2' }) : null,
                        (!p.collapsed || p.level > 0) ? h('span', p.item.name) : null
                    ])
                ]
            })
        ]);
    }
});

</script>

<template>
    <aside
        :class="['bg-gray-800 text-white transition-all duration-300 flex flex-col sticky top-0', 'h-screen', collapsed ? 'w-16' : 'w-64', 'z-30']">
        <div class="flex items-center justify-between p-4">
            <Logo :logoPath="'/logo.png'" size="sm" altText="Wardrobe Logo" />
            <button @click="toggleMenu('openSidebar')" class="text-gray-400 hover:text-white focus:outline-none" aria-label="Toggle sidebar">
                <HeroIcons.Bars3Icon class="w-5 h-5"/>
            </button>
        </div>

        <nav class="mt-2 overflow-auto flex-1">
            <ul class="px-2 space-y-1">
                <li v-for="(item, idx) in props.navigation" :key="idx">
                    <SidebarItem :item="item" :level="0" :parent-name="null" :collapsed="props.collapsed" :open-menus="openMenus" :openMenus="openMenus" />
                </li>
            </ul>
        </nav>
    </aside>
</template>

<style scoped></style>