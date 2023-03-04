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
                <FormField
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
                </FormField>
                <div class="grid grid-cols-12 gap-3">
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
                    <div class="">
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
                    </div>
                </div>
                
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
                    <ckeditor
                    :editor="ClassicEditor"
                    :config="editorConfig"
                    v-model="form.message"
                    />
                </div>

                <div class="mt-6">
                    <label for="list" class="block mb-2 text-md font-bold text-gray-900 dark:text-white">
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
import { watch, ref } from 'vue'
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

import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
//import InlineEditor from '@ckeditor/ckeditor5-build-inline'
//import BalloonEditor from '@ckeditor/ckeditor5-build-balloon'
//import BalloonBlockEditor from '@ckeditor/ckeditor5-build-balloon-block'

// CKEditor 5 variables
let ckeditor = CKEditor.component;
const editorConfig = ref({
    // toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'underline' ],
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