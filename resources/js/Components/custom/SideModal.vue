<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                    <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                        <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 sm:-ml-10 sm:pr-4">
                            <button type="button" class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white" @click="open = false">
                            <span class="sr-only">Close panel</span>
                            <!-- <XMarkIcon class="h-6 w-6" aria-hidden="true" /> -->
                            </button>
                        </div>
                        </TransitionChild>
                        <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                            <div class="px-4 sm:px-6">
                                <DialogTitle class="text-base font-semibold leading-6 text-gray-900">List</DialogTitle>
                            </div>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">New List</h3>
                                <form form @submit.prevent="actionType=='create' ? form.post(route('list.store')) : form.post(route('list.update', data.id))">
                                    <div class="col-span-6 sm:col-span-3 mt-6">
                                        <label for="list-name" class="block text-sm font-medium text-gray-700" required>Name</label>
                                        <input type="text" 
                                        name="list-name" 
                                        id="list-name" 
                                        v-model="form.name"
                                        autocomplete="given-name" 
                                        class="mt-1 block w-full rounded-md 
                                        border-gray-300 shadow-sm 
                                        focus:border-indigo-500 
                                        focus:ring-indigo-500 
                                        sm:text-sm" />
                                    </div>
                                    
                                    <div class="mt-6" v-if="actionType !== 'create'">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                                        <input class="block w-full text-sm 
                                        text-gray-900 border border-gray-300 
                                        rounded-lg cursor-pointer bg-gray-50 
                                        dark:text-gray-400 focus:outline-none 
                                        dark:bg-gray-700 dark:border-gray-600 
                                        dark:placeholder-gray-400" 
                                        id="file-upload" type="file"
                                        name="file-upload"
                                        @input="form.file = $event.target.files[0]"
                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                    </progress>
                                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 mt-6">
                                        <button type="submit" class="inline-flex justify-center 
                                        rounded-md border border-transparent bg-indigo-600 py-2 
                                        px-4 text-sm font-medium text-white shadow-sm 
                                        hover:bg-indigo-700 focus:outline-none focus:ring-2 
                                        focus:ring-indigo-500 focus:ring-offset-2">Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </DialogPanel>
                    </TransitionChild>
                </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Head, Link, useForm } from "@inertiajs/vue3"
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
    show: Boolean,
    actionType: String,
    data: Object
})
const emit = defineEmits(['closeModal'])
const open = ref(props.show)
const form = useForm({
    _method: 'post',
    id: null,
    name: '',
    file: null
})

watch(() => props.show, (newVal, oldVal) => {
    open.value = newVal
    if(props.actionType == 'create') {
        form.name = ''
        form.file = null
        form._method = 'post'
    }
})

watch(() => props.data, (newVal, oldVal) => {
    if(newVal) {
        form.id = newVal.id
        form.name = newVal.name
        form.file = null
        form._method = 'put'
    }
})

watch(open, (newVal, oldVal) => {
    if(!newVal)
        emit('closeModal')
})
</script>

<style>

</style>