<template>
    <LayoutAuthenticated>
        <Head title="New Campaign" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountKey"
                title="New Campaign"
                main
            >
                <BaseButton
                :route-name="route('campaign.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
                />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('campaign.store'))">
                <div class="">
                    <label for="subject" class="block text-sm font-medium leading-6 text-gray-900">
                        Subject of this email
                    </label>
                    <input type="text" name="subject" id="subject"  
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm 
                    ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 
                    focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                    v-model="form.subject"
                    required/>
                </div>
                <div class="mt-6">
                    <label for="from" class="block text-sm font-medium leading-6 text-gray-900">
                        From name
                    </label>
                    <input type="text" name="from" id="from"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm 
                    ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 
                    focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                    v-model="form.from_name"
                    required/>
                </div>
                <!-- <FormField
                    label="Subject"
                    :class="{ 'text-red-400': form.errors.subject }">
                    <FormControl
                        v-model="form.subject"
                        type="text"
                        placeholder="Subject of this email"
                        :error="form.errors.subject">
                        <div class="text-red-400 text-sm" v-if="form.errors.subject">
                            {{ form.errors.subject }}
                        </div>
                    </FormControl>
                </FormField> -->
                <!-- <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <FormField
                            label="From name"
                            :class="{ 'text-red-400': form.errors.from_name }">
                            <FormControl
                                v-model="form.from_name"
                                type="text"
                                placeholder="From name"
                                :error="form.errors.from_name">
                                <div class="text-red-400 text-sm" v-if="form.errors.from_name">
                                {{ form.errors.from_name }}
                                </div>
                            </FormControl>
                        </FormField>
                    </div>
                    <div class=""> -->
                        <!-- <FormField
                            label="From email"
                            :class="{ 'text-red-400': form.errors.from_email }">
                                <FormControl
                                    v-model="form.from_email"
                                    type="email"
                                    placeholder="From email"
                                    :error="form.errors.from_email">
                                    <div class="text-red-400 text-sm" v-if="form.errors.from_email">
                                    {{ form.errors.from_email }}
                                    </div>
                                </FormControl>
                            </FormField> -->
                    <!-- </div>
                </div> -->
                
                <!-- <FormField
                label="Reply To"
                :class="{ 'text-red-400': form.errors.reply_to }">
                    <FormControl
                        v-model="form.reply_to"
                        type="email"
                        placeholder="Reply To"
                        :error="form.errors.reply_to">
                        <div class="text-red-400 text-sm" v-if="form.errors.reply_to">
                        {{ form.errors.reply_to }}
                        </div>
                    </FormControl>
                </FormField> -->

                <div class="mt-6">
                    <!-- <ckeditor
                    :editor="ClassicEditor"
                    :config="editorConfig"
                    v-model="form.message"
                    /> -->
                    <label for="from" class="block text-sm font-medium leading-6 text-gray-900">
                        Message
                    </label>
                    <editor
                        api-key="nz91pgequ1i4nogj6arnwzcz01gd4h5d43gbnj6pdvyfdzzx"
                        v-model="form.message"
                        :init="init"
                    />
                </div>

                <div class="mt-6">
                    <label for="list" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">
                        Choose your lists
                    </label>
                    <select
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block p-2.5"
                        id="example-select-multiple"
                        name="example-select-multiple"
                        size="5"
                        multiple
                        v-model="form.list"
                        required>
                        <option selected class="font-bold">Lists</option>
                        <option :value="l.id" v-for="l in list" :key="l.id">{{l.name}}</option>
                    </select>
                </div>

                <template #footer>
                    <BaseButtons>
                        <BaseButton
                        type="submit"
                        color="info"
                        label="Submit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>

<script setup>
import { watch, ref, reactive } from 'vue'
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiAccountKey,
    mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'

import Editor from '@tinymce/tinymce-vue'

// import CKEditor from '@ckeditor/ckeditor5-vue';
// import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
//import InlineEditor from '@ckeditor/ckeditor5-build-inline'
//import BalloonEditor from '@ckeditor/ckeditor5-build-balloon'
//import BalloonBlockEditor from '@ckeditor/ckeditor5-build-balloon-block'

// CKEditor 5 variables
// let ckeditor = CKEditor.component;
// const editorConfig = ref({});

const plugins = 'quickbars emoticons table image media autolink link code anchor wordcount insertdatetime';
const toolbar = ' bold italic underline strikethrough | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify|bullist numlist |outdent indent blockquote | undo redo | axupimgs | removeformat | table | emoticons | image | media'
const init = reactive({
    height: 500,
    menubar: true,
    plugins: plugins,
    toolbar: toolbar,
    branding: false,
    a11y_advanced_options: true,
    file_picker_types: 'file image media'
});

const props = defineProps({
  list: {
    type: Array,
    default: () => ([]),
  }
})
const form = useForm({
    subject: '',
    from_name: '',
    from_email: '',
    reply_to: '',
    message: '',
    attachment: null,
    list: [],
    test_mail: ''
})

</script>

<style>

</style>