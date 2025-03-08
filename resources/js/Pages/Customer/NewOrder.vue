<template>
    <Head title="新增訂單" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                新增訂單
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="space-y-4">
                                <div
                                    v-for="(item, index) in selectedItems"
                                    :key="index"
                                    class="flex items-center space-x-4"
                                >
                                    <div class="flex-1">
                                        <select
                                            v-model="item.laundry_type_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">
                                                選擇洗衣類型
                                            </option>
                                            <option
                                                v-for="type in laundryTypes"
                                                :key="type.id"
                                                :value="type.id"
                                            >
                                                {{ type.name }} - ${{
                                                    type.price
                                                }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="w-32">
                                        <input
                                            type="number"
                                            v-model="item.quantity"
                                            min="1"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeItem(index)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        移除
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    @click="addItem"
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    新增項目
                                </button>

                                <div class="mt-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >備註</label
                                    >
                                    <textarea
                                        v-model="form.notes"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </div>

                                <div
                                    class="mt-4 flex justify-between items-center"
                                >
                                    <div class="text-xl font-bold">
                                        總計: ${{ totalPrice }}
                                    </div>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                        :disabled="
                                            form.processing ||
                                            selectedItems.length === 0
                                        "
                                    >
                                        提交訂單
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    laundryTypes: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    items: [],
    notes: "",
});

const selectedItems = ref([]);

const addItem = () => {
    selectedItems.value.push({
        laundry_type_id: "",
        quantity: 1,
    });
};

const removeItem = (index) => {
    selectedItems.value.splice(index, 1);
};

const totalPrice = computed(() => {
    return selectedItems.value.reduce((total, item) => {
        const laundryType = props.laundryTypes.find(
            (type) => type.id === item.laundry_type_id
        );
        return total + (laundryType ? laundryType.price * item.quantity : 0);
    }, 0);
});

const submit = () => {
    form.items = selectedItems.value;
    form.post(route("customer.orders.store"), {
        onSuccess: () => {
            selectedItems.value = [];
            form.reset();
        },
    });
};
</script>
