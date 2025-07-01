<script setup lang="ts">
import { ref } from "vue";
import { Icon } from "@iconify/vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
// import Logo from "./Logo.vue";
// const page = usePage();
// const organizationId = computed(() => page.props.auth.user);
const props = defineProps(['user'])

const showDropdownSelectAction = ref(false);

const visible = ref(false);
</script>


<template>
    <!-- <pre> {{props.user.name }}</pre> -->
    <!-- Toggle Button -->
    <div class="flex justify-between mt-3 lg:mx-10 mx-5">
        <div>
            <button v-if="!visible" @click="visible = true"
                class="top-4 left-4 z-50 p-2 rounded-md text-white bg-primary focus:outline-none"
                :aria-controls="visible ? 'sbar' : null" :aria-expanded="visible">
                <Icon icon="ci:menu-alt-03" width="24" height="24" />
            </button>
        </div>

    </div>
    <!-- Sidebar -->
    <transition name="slide">
        <aside v-if="visible" id="sbar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-40 flex flex-col"
            role="region">
            <!-- Header -->
            <div class="flex items-center justify-between px-7 py-5 border-b">
                <div class="flex items-center gap-2">
                    <Logo />
                   
                    <!-- <span class="font-inter font-medium text-[30px] leading-[16px] tracking-[0]">Logo</span> -->
                </div>
                <button @click="visible = false" class="p-1 rounded-md hover:bg-gray-200">
                    <Icon icon="material-symbols:close" width="24" height="24" />
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto">
                <ul class="space-y-1 p-4">
                    <li>
                        <a href="/dashboard" @click="visible = false"
                            class="flex items-center p-2 rounded hover:bg-gray-200 text-gray-700 hover:text-black cursor-pointer">
                            <Icon icon="ic:round-dashboard" width="20" height="20" />
                            <span
                                class="ml-3 font-inter font-medium text-gray-700 hover:text-black leading-[16px] tracking-[0]">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard" @click="visible = false"
                            class="flex items-center p-2 rounded hover:bg-gray-200 text-gray-700 hover:text-black cursor-pointer">
                            <Icon icon="ic:round-dashboard" width="20" height="20" />
                            <span
                                class="ml-3 font-inter font-medium text-gray-700 hover:text-black leading-[16px] tracking-[0]">Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard" @click="visible = false"
                            class="flex items-center p-2 rounded hover:bg-gray-200 text-gray-700 hover:text-black cursor-pointer">
                            <Icon icon="ic:round-dashboard" width="20" height="20" />
                            <span
                                class="ml-3 font-inter font-medium text-gray-700 hover:text-black leading-[16px] tracking-[0]">Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard" @click="visible = false"
                            class="flex items-center p-2 rounded hover:bg-gray-200 text-gray-700 hover:text-black cursor-pointer">
                            <Icon icon="ic:round-dashboard" width="20" height="20" />
                            <span
                                class="ml-3 font-inter font-medium text-gray-700 hover:text-black leading-[16px] tracking-[0]">Transaction</span>
                        </a>
                    </li>

                </ul>
                <!-- Bottom "Preference" Link -->
                <div class="p-4 absolute inset-x-0 bottom-0">
                    <!-- </li> -->

                    <a :href="route('logout')" class="flex items-center p-2 rounded hover:bg-gray-100 text-[#FF0000]">
                        <Icon icon="lucide:log-out" width="18" height="18" />
                        <span class="ml-2 font-inter font-medium text-[16px] leading-[16px] tracking-[0]">profile</span>
                    </a>
                    <a :href="route('logout')" class="flex items-center p-2 rounded hover:bg-gray-100 text-[#FF0000]">
                        <Icon icon="lucide:log-out" width="18" height="18" />
                        <span class="ml-2 font-inter font-medium text-[16px] leading-[16px] tracking-[0]">Log Out</span>
                    </a>


                </div>
            </nav>
        </aside>
    </transition>
</template>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from {
    transform: translateX(-100%);
}

.slide-enter-to {
    transform: translateX(0%);
}

.slide-leave-from {
    transform: translateX(0%);
}

.slide-leave-to {
    transform: translateX(-100%);
}
</style>

<style>
/* Optional slide animation */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from {
    transform: translateX(-100%);
}

.slide-enter-to {
    transform: translateX(0%);
}

.slide-leave-from {
    transform: translateX(0%);
}

.slide-leave-to {
    transform: translateX(-100%);
}
</style>
