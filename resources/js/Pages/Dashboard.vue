<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { reactive, ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import axios from 'axios';

defineProps({
    errors: Object,
})

const employees = ref([])
const companies = ref([])

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    company_id: null,
})

const getEmployees = async () => {
    const { data } = await axios.get(route('employees.index'))

    employees.value =  data.data || []
}

const storeEmployee = async () => {
    form.post(route('employees.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            getEmployees()
        }
    })
}

const destroyEmployee = async (id) => {
    const { data } = await axios.delete(route('employees.destroy', [id]))

    getEmployees()
}


const getCompanies = async () => {
    const { data } = await axios.get(route('companies.index'))

    companies.value =  data.data || []
}

onMounted(() => {
    getEmployees()
    getCompanies()
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div>
            <div class="sm:flex sm:items-center">
                <form class="sm:flex-auto">
                    <div class="inline-block">
                        <input type="text" v-model="form.first_name" name="first_name" placeholder="First name" class="mx-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1">
                        <span class="text-sm text-red-600 dark:text-red-400" v-if="errors.first_name"><br> {{ errors.first_name }}</span>
                    </div>
                    <div class="inline-block">
                        <input type="text" v-model="form.last_name" name="last_name" placeholder="Last name" class="mx-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1">
                        <span class="text-sm text-red-600 dark:text-red-400" v-if="errors.last_name"><br> {{ errors.last_name }}</span>
                    </div>
                    <div class="inline-block">
                        <input type="text" v-model="form.email" name="email" placeholder="Email" class="mx-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1">
                        <span class="text-sm text-red-600 dark:text-red-400" v-if="errors.email"><br> {{ errors.email }}</span>
                    </div>
                    <div class="inline-block">
                        <select v-model="form.company_id" name="company_id" placeholder="" class="mx-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1">
                            <option :value="null">Select Company</option>
                            <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                        </select>
                        <span class="text-sm text-red-600 dark:text-red-400" v-if="errors.company_id"><br> {{ errors.company_id }}</span>
                    </div>
                </form>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button type="button" @click="storeEmployee" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Add user</button>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">First name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Company</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Delete</span>
                            </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="employee in employees" :key="employee.email">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ employee.first_name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ employee.last_name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ employee.email }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ employee.company.name }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="#" @click="destroyEmployee(employee.id)" class="text-indigo-600 hover:text-indigo-900">
                                    Delete
                                    <span class="sr-only">
                                        , {{ employee.name }}
                                    </span>
                                </a>
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
