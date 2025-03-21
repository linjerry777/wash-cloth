<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    orders: {
        type: Array,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
});

const statusClasses = {
    pending: "bg-yellow-100 text-yellow-800",
    processing: "bg-blue-100 text-blue-800",
    completed: "bg-green-100 text-green-800",
    cancelled: "bg-red-100 text-red-800",
};

const getStatusLabel = (status) => {
    const labels = {
        pending: "待處理",
        processing: "處理中",
        completed: "已完成",
        cancelled: "已取消",
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="客戶儀表板" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                歡迎回來
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- 統計卡片 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="text-sm text-gray-600">總訂單數</div>
                        <div class="text-2xl font-semibold">
                            {{ stats.total_orders }}
                        </div>
                    </div>
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="text-sm text-gray-600">待處理</div>
                        <div class="text-2xl font-semibold text-yellow-600">
                            {{ stats.pending_orders }}
                        </div>
                    </div>
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="text-sm text-gray-600">處理中</div>
                        <div class="text-2xl font-semibold text-blue-600">
                            {{ stats.processing_orders }}
                        </div>
                    </div>
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                    >
                        <div class="text-sm text-gray-600">已完成</div>
                        <div class="text-2xl font-semibold text-green-600">
                            {{ stats.completed_orders }}
                        </div>
                    </div>
                </div>

                <!-- 最近訂單 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            最近訂單
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            訂單編號
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            項目
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            總價
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            狀態
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            建立時間
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr v-for="order in orders" :key="order.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="
                                                    route(
                                                        'customer.orders.show',
                                                        order.id
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                #{{ order.id }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                v-for="item in order.items"
                                                :key="item.id"
                                                class="text-sm"
                                            >
                                                {{ item.laundry_type.name }} x
                                                {{ item.quantity }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            ${{ order.total_price }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="[
                                                    'px-2 py-1 rounded-full text-xs',
                                                    statusClasses[order.status],
                                                ]"
                                            >
                                                {{
                                                    getStatusLabel(order.status)
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.created_at }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
