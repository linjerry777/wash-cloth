<template>
    <Head title="訂單管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                訂單管理
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between mb-4">
                            <div class="flex space-x-4">
                                <button
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                >
                                    全部
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    待處理
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    處理中
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    已完成
                                </button>
                            </div>
                            <div class="flex items-center">
                                <input
                                    type="text"
                                    placeholder="搜尋訂單..."
                                    class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        訂單編號
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        客戶
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        金額
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        狀態
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        建立時間
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        操作
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="order in orders" :key="order.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        #{{ order.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ order.user?.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${{ order.total_price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2 py-1 rounded-full text-xs',
                                                getStatusClass(order.status),
                                            ]"
                                        >
                                            {{ order.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ order.created_at }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            class="text-blue-600 hover:text-blue-900 mr-4"
                                        >
                                            處理
                                        </button>
                                        <button
                                            class="text-green-600 hover:text-green-900"
                                        >
                                            完成
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const getStatusClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        processing: "bg-blue-100 text-blue-800",
        completed: "bg-green-100 text-green-800",
        cancelled: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const updateStatus = (orderId, status) => {
    // 實現狀態更新邏輯
};
</script>
