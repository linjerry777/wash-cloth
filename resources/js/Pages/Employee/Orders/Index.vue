<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    orders: {
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
    <Head title="訂單管理" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    訂單管理
                </h2>
                <Link
                    :href="route('employee.orders.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    新增訂單
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
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
                                            客戶
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
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            操作
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="order in orders.data"
                                        :key="order.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            #{{ order.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.user.name }}
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="
                                                    route(
                                                        'employee.orders.show',
                                                        order.id
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900 mr-4"
                                            >
                                                查看
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            <Pagination :links="orders.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
