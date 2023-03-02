<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  mdiAccountKey,
  mdiPlus,
  mdiSquareEditOutline,
  mdiTrashCan,
  mdiAlertBoxOutline,
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import BaseButton from "@/Components/BaseButton.vue"
import CardBox from "@/Components/CardBox.vue"
import CardBoxModal from "@/Components/CardBoxModal.vue"
import BaseButtons from "@/Components/BaseButtons.vue"
import NotificationBar from "@/Components/NotificationBar.vue"
import Pagination from "@/Components/Admin/Pagination.vue"
import Sort from "@/Components/Admin/Sort.vue"
import SideModal from "@/Components/custom/SideModal.vue"

const props = defineProps({
  list: {
    type: Object,
    default: () => ({}),
  },
})

let showModal = ref(false)
let actionType = ref('create')

function resetModal(type) {
    showModal.value = !showModal.value
    actionType = type
}
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Contact List" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountKey"
                title="Contact List"
                main
            >
                <BaseButton
                @click="resetModal('create')"
                :icon="mdiPlus"
                label="Add"
                color="info"
                rounded-full
                small
                />
            </SectionTitleLineWithButton>
            <NotificationBar
                v-if="$page.props.flash.message"
                color="success"
                :icon="mdiAlertBoxOutline"
            >
                {{ $page.props.flash.message }}
            </NotificationBar>
            <CardBox class="mb-6" has-table>
                <table>
                    <thead>
                        <tr>
                            <th>
                                <Sort label="Name" attribute="name" />
                            </th>
                            <th>
                                <Sort label="Recipient" attribute="recipient" />
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>

    <SideModal 
    :show="showModal"
    :actionType="actionType"
    @closeModal="resetModal"/>
</template>

<style>

</style>