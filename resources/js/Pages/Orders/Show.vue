<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const statusForm = useForm({
    status: props.order.status,
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

const updateStatus = () => {
    statusForm.patch(route("employee.orders.update-status", props.order.id));
};
</script>

<template>
    <Head title="訂單詳情" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    訂單 #{{ order.id }} 詳情
                </h2>
                <span
                    :class="[
                        'px-3 py-1 rounded-full text-sm',
                        statusClasses[order.status],
                    ]"
                >
                    {{ getStatusLabel(order.status) }}
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- 客戶信息 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                客戶信息
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p>
                                    <span class="font-medium">姓名：</span
                                    >{{ order.user.name }}
                                </p>
                                <p>
                                    <span class="font-medium">電郵：</span
                                    >{{ order.user.email }}
                                </p>
                            </div>
                        </div>

                        <!-- 訂單項目 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                訂單項目
                            </h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            項目
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            數量
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            單價
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            小計
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="item in order.items"
                                        :key="item.id"
                                    >
                                        <td class="px-6 py-4">
                                            {{ item.laundry_type.name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ item.quantity }}
                                        </td>
                                        <td class="px-6 py-4">
                                            ${{ item.price }}
                                        </td>
                                        <td class="px-6 py-4">
                                            ${{ item.subtotal }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td
                                            colspan="3"
                                            class="px-6 py-4 text-right font-medium"
                                        >
                                            總計：
                                        </td>
                                        <td class="px-6 py-4 font-medium">
                                            ${{ order.total_price }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- 備註 -->
                        <div class="mb-8" v-if="order.notes">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                備註
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                {{ order.notes }}
                            </div>
                        </div>

                        <!-- 狀態更新（僅限員工） -->
                        <div
                            v-if="$page.props.auth.user.role_id === 2"
                            class="mt-8"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                更新狀態
                            </h3>
                            <div class="flex items-center space-x-4">
                                <select
                                    v-model="statusForm.status"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="pending">待處理</option>
                                    <option value="processing">處理中</option>
                                    <option value="completed">已完成</option>
                                    <option value="cancelled">已取消</option>
                                </select>
                                <PrimaryButton
                                    @click="updateStatus"
                                    :disabled="statusForm.processing"
                                >
                                    更新狀態
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
