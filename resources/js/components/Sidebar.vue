<script setup>
    import {
        ref
    } from "vue";

    const isOpen = ref(true);
    const toggleSidebar = () => {
        isOpen.value = !isOpen.value;
    };

    const logo = "/storage/images/Flower.png";
</script>
<template>
    <div class="flex">
        <!-- Sidebar -->
        <div :class="isOpen ? 'w-60' : 'w-25'"
            class="bg-orange-400 h-screen p-5 pt-8 relative transition-all duration-300 shadow-lg overflow-visible">
            <i :class="isOpen ? 'fas fa-arrow-left' : 'fas fa-arrow-left fa-flip-horizontal'"
                class=" text-orange-400 bg-white p-3 rounded-full w-10 h-10 flex items-center justify-center absolute -right-5 top-9 border border-orange-400 cursor-pointer"
                @click="toggleSidebar"></i>

            <div class="inline-flex items-center justify-center">
                <!-- Logo dengan Background -->
                <i :class="isOpen ? 'ml-5' : 'rotate-[360deg]'"
                    class="bg-white rounded-lg p-2 flex items-center justify-center w-12 h-12 mr-2 cursor-pointer duration-400">
                    <img :src="logo" alt="Logo" class="w-8 h-8">
                </i>
                <!-- Teks -->
                <h1 :class="isOpen ? 'text-4xl' : 'scale-0'" class="text-white font-medium duration-300">TodoFy</h1>
            </div>

            <ul :class=" isOpen ? 'ml-5' : ''"
            class="mt-5 duration-400">
                <!-- Dashboard -->
                <li class="flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition">
                    <i class="fas fa-home text-white w-6 text-lg"></i>
                    <span :class="isOpen ? 'ml-3 text-lg' : 'scale-0'" class="text-white duration-300">Dashboard</span>
                </li>
                <!-- Create -->
                <li class="flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition">
                    <i class="fas fa-plus-circle text-white w-6 text-lg"></i>
                    <span :class="isOpen ? 'ml-3 text-lg' : 'scale-0'" class="text-white duration-300">Create</span>
                </li>
                <!-- Setting -->
                <li class="flex items-center p-3 cursor-pointer hover:bg-orange-500 rounded-lg transition">
                    <i class="fas fa-cog text-white w-6 text-lg"></i>
                    <span :class="isOpen ? 'ml-3 text-lg' : 'scale-0'" class="text-white duration-300">Setting</span>
                </li>
            </ul>

        </div>

        <!-- Content -->
        <div class="p-7">
            <h1 class="text-3xl font-semibold">biji ayam</h1>
        </div>
    </div>
</template>
