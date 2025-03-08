<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    laundryTypes: {
        type: Array,
        required: true,
    },
});

const searchQuery = ref("");
const searchResults = ref([]);
const selectedCustomer = ref(null);
const showSearchResults = ref(false);

const form = useForm({
    user_id: "",
    items: [],
    notes: "",
});

// 搜索客戶
const searchCustomers = async () => {
    if (searchQuery.value.length < 2) return;

    try {
        const response = await fetch(
            `/employee/customers/search?query=${searchQuery.value}`
        );
        const data = await response.json();
        searchResults.value = data;
        showSearchResults.value = true;
    } catch (error) {
        console.error("搜索客戶失敗:", error);
    }
};

// 選擇客戶
const selectCustomer = (customer) => {
    selectedCustomer.value = customer;
    form.user_id = customer.id;
    searchQuery.value = customer.name;
    showSearchResults.value = false;
};

// 添加訂單項目
const addItem = () => {
    form.items.push({
        laundry_type_id: "",
        quantity: 1,
    });
};

// 移除訂單項目
const removeItem = (index) => {
    form.items.splice(index, 1);
};

// 計算總價
const totalPrice = computed(() => {
    return form.items.reduce((total, item) => {
        const laundryType = props.laundryTypes.find(
            (type) => type.id === item.laundry_type_id
        );
        return total + (laundryType ? laundryType.price * item.quantity : 0);
    }, 0);
});

// 提交訂單
const submit = () => {
    form.post(route("employee.orders.store"), {
        onSuccess: () => {
            form.reset();
            selectedCustomer.value = null;
            searchQuery.value = "";
        },
    });
};
</script>

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
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <form @submit.prevent="submit">
                        <!-- 客戶搜索 -->
                        <div class="mb-6">
                            <InputLabel value="搜索客戶" />
                            <div class="relative">
                                <TextInput
                                    v-model="searchQuery"
                                    type="text"
                                    class="w-full"
                                    @input="searchCustomers"
                                    placeholder="輸入客戶名稱或電郵搜索..."
                                />
                                <!-- 搜索結果下拉框 -->
                                <div
                                    v-if="
                                        showSearchResults &&
                                        searchResults.length > 0
                                    "
                                    class="absolute z-10 w-full mt-1 bg-white border rounded-md shadow-lg"
                                >
                                    <div
                                        v-for="customer in searchResults"
                                        :key="customer.id"
                                        class="p-2 hover:bg-gray-100 cursor-pointer"
                                        @click="selectCustomer(customer)"
                                    >
                                        {{ customer.name }} ({{
                                            customer.email
                                        }})
                                    </div>
                                </div>
                            </div>
                            <InputError
                                :message="form.errors.user_id"
                                class="mt-2"
                            />
                        </div>

                        <!-- 已選擇的客戶信息 -->
                        <div
                            v-if="selectedCustomer"
                            class="mb-6 p-4 bg-gray-50 rounded-md"
                        >
                            <h3 class="font-medium">已選擇的客戶：</h3>
                            <p>姓名：{{ selectedCustomer.name }}</p>
                            <p>電郵：{{ selectedCustomer.email }}</p>
                        </div>

                        <!-- 訂單項目 -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">訂單項目</h3>
                                <PrimaryButton type="button" @click="addItem"
                                    >添加項目</PrimaryButton
                                >
                            </div>

                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="flex gap-4 mb-4"
                            >
                                <div class="flex-1">
                                    <select
                                        v-model="item.laundry_type_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">選擇洗衣類型</option>
                                        <option
                                            v-for="type in laundryTypes"
                                            :key="type.id"
                                            :value="type.id"
                                        >
                                            {{ type.name }} - ${{ type.price }}
                                        </option>
                                    </select>
                                </div>
                                <div class="w-32">
                                    <TextInput
                                        v-model="item.quantity"
                                        type="number"
                                        min="1"
                                        class="w-full"
                                    />
                                </div>
                                <SecondaryButton
                                    type="button"
                                    @click="removeItem(index)"
                                    >移除</SecondaryButton
                                >
                            </div>
                            <InputError
                                :message="form.errors.items"
                                class="mt-2"
                            />
                        </div>

                        <!-- 備註 -->
                        <div class="mb-6">
                            <InputLabel value="備註" />
                            <textarea
                                v-model="form.notes"
                                rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                            <InputError
                                :message="form.errors.notes"
                                class="mt-2"
                            />
                        </div>

                        <!-- 總價和提交按鈕 -->
                        <div class="flex justify-between items-center">
                            <div class="text-xl font-bold">
                                總計: ${{ totalPrice }}
                            </div>
                            <div class="flex gap-4">
                                <SecondaryButton
                                    type="button"
                                    :href="route('employee.orders.index')"
                                >
                                    取消
                                </SecondaryButton>
                                <PrimaryButton
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    創建訂單
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
