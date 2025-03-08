<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="首頁 - 智能洗衣服務" />

    <div class="min-h-screen bg-gray-50">
        <!-- 導航欄 -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-blue-600"
                                >WashCloth</span
                            >
                        </div>
                    </div>

                    <!-- 導航鏈接 -->
                    <div class="flex items-center space-x-4">
                        <template v-if="canLogin">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
                            >
                                控制台
                            </Link>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
                                >
                                    登入
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium"
                                >
                                    註冊
                                </Link>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 主要內容 -->
        <main>
            <!-- Hero 部分 -->
            <div class="relative bg-white overflow-hidden">
                <div class="max-w-7xl mx-auto">
                    <div
                        class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32"
                    >
                        <div
                            class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28"
                        >
                            <div class="sm:text-center lg:text-left">
                                <h1
                                    class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl"
                                >
                                    <span class="block">智能洗衣服務</span>
                                    <span class="block text-blue-600"
                                        >為您帶來潔淨體驗</span
                                    >
                                </h1>
                                <p
                                    class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0"
                                >
                                    專業的洗衣服務，讓您的衣物煥然一新。我們提供便捷的預約系統，讓您輕鬆安排取送時間。
                                </p>
                                <div
                                    class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
                                >
                                    <div class="rounded-md shadow">
                                        <Link
                                            :href="route('register')"
                                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10"
                                        >
                                            立即預約
                                        </Link>
                                    </div>
                                    <div class="mt-3 sm:mt-0 sm:ml-3">
                                        <a
                                            href="#services"
                                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10"
                                        >
                                            了解更多
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 服務特點 -->
            <div id="services" class="py-12 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2
                            class="text-base text-blue-600 font-semibold tracking-wide uppercase"
                        >
                            我們的服務
                        </h2>
                        <p
                            class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl"
                        >
                            為什麼選擇我們？
                        </p>
                    </div>

                    <div class="mt-10">
                        <div
                            class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <!-- 服務項目 1 -->
                            <div
                                class="bg-white overflow-hidden shadow rounded-lg"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-6 w-6 text-blue-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5">
                                            <h3
                                                class="text-lg leading-6 font-medium text-gray-900"
                                            >
                                                快速服務
                                            </h3>
                                            <p
                                                class="mt-2 text-base text-gray-500"
                                            >
                                                24小時內完成洗滌，讓您的衣物快速恢復整潔。
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 服務項目 2 -->
                            <div
                                class="bg-white overflow-hidden shadow rounded-lg"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-6 w-6 text-blue-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5">
                                            <h3
                                                class="text-lg leading-6 font-medium text-gray-900"
                                            >
                                                專業品質
                                            </h3>
                                            <p
                                                class="mt-2 text-base text-gray-500"
                                            >
                                                專業的洗滌設備和技術，確保衣物品質。
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 服務項目 3 -->
                            <div
                                class="bg-white overflow-hidden shadow rounded-lg"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-6 w-6 text-blue-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5">
                                            <h3
                                                class="text-lg leading-6 font-medium text-gray-900"
                                            >
                                                便捷支付
                                            </h3>
                                            <p
                                                class="mt-2 text-base text-gray-500"
                                            >
                                                多種支付方式，讓您輕鬆完成付款。
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- 頁腳 -->
        <footer class="bg-white">
            <div
                class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8"
            >
                <div class="mt-8 md:mt-0">
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ new Date().getFullYear() }} WashCloth. All
                        rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
