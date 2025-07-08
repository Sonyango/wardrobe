<script setup>
//import { defineProps } from 'vue';
//import * as HeroIcons from '@heroicons/vue/24/outline';
import { getCurrentInstance, ref } from 'vue';


const props = defineProps({
    collapsed: Boolean,
    navigation: Array,
});

const openMenus = ref({});

function toggleMenu(name) {
    openMenus.value[name] = !openMenus.value[name];
}




</script>

<template>
    <aside
        :class="['bg-gray-800 text-white transition-all duration-300 flex flex-col', 'h-screen', collapsed ? 'w-16' : 'w-64']">
        <div class="flex items-center justify-between p-4">
            <slot name="logo"></slot>
        </div>

        <nav v-if="!collapsed" class="mt-4">
            <SidebarNavList :items="navigation" :openMenus="openMenus" :toggleMenu="toggleMenu" :level="0" />
        </nav>
    </aside>
</template>

<script>
import { defineComponent, h, getCurrentInstance } from 'vue';
import * as HeroIcons from '@heroicons/vue/24/outline';

export default {
    components: {
        SidebarNavList: defineComponent({
            name: 'SidebarNavList',
            props: ['items', 'openMenus', 'toggleMenu', 'level'],
            setup(props) {
                function getIcon(iconName) {
                    return HeroIcons[iconName] || HeroIcons['QuestionMarkCircleIcon'];
                }
                const SidebarNavList = getCurrentInstance().type;
                return () => props.items.map(item => {
                    const Icon = getIcon(item.icon);
                    const hasChildren = item.children && item.children.length > 0;
                    const isOpen = props.openMenus[item.name];

                    const basePadding = 24;
                    const indentPerLevel = 16;
                    const maxPadding = 48;
                    const paddingLeft = `${Math.min(basePadding + props.level * indentPerLevel, maxPadding)}px`;

                    return h('div', { class: 'flex flex-col' }, [
                        h('div', {
                            class: ['flex items-center justify-between px-4 py-2 rounded hover:bg-gray-700 cursor-pointer',
                                item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:text-white'
                            ],
                            style: { paddingLeft },
                            onClick: hasChildren ? () => props.toggleMenu(item.name) : undefined,
                        }, [
                            h('div', { class: 'flex items-center gap-2' }, [
                                Icon ? h(Icon, { class: 'w-6 h-6 mr-2' }) : null,
                                h('span', item.name)
                            ]),
                            hasChildren ? h(HeroIcons.ChevronDownIcon, {
                                class: [
                                    'w-4 h-4 transition-transform',
                                    isOpen ? 'transform rotate-180' : ''
                                ]
                            }) : null
                        ]),
                        hasChildren && isOpen ? h(
                            SidebarNavList, {
                            items: item.children,
                            openMenus: props.openMenus,
                            toggleMenu: props.toggleMenu,
                            level: props.level + 1
                        }
                        ) : null
                    ]);
                });
            },
        })
    }
}
</script>

<style scoped></style>