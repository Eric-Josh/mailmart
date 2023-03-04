<script setup>
import { ref, onMounted } from 'vue'
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
const formDelete = useForm({})

let showModal = ref(false)
let actionType = ref('create')
let data = ref(null)

function resetModal(type, item=null) {
    showModal.value = !showModal.value
    actionType = type
    data.value = item
}

function destroy(id) {
  if (confirm("Are you sure you want to delete?")) {
    formDelete.delete(route("list.destroy", id))
  }
}

onMounted(() => {
    showModal.value = false
})
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
                            <th>
                                <Sort label="Unsubscribed" attribute="unsubscribed" />
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in list.data" :key="item.id">
                            <td data-label="Name">
                                {{ item.name }}
                            </td>
                            <td data-label="Subscribers">
                                {{ item.subscribers }}
                            </td>
                            <td data-label="Unsubscribed">
                                {{ item.unsubscribed }}
                            </td>
                            <td data-label="Name">
                                <BaseButtons type="justify-start" no-wrap>
                                    <BaseButton
                                        @click="resetModal('update', item)"
                                        color="info"
                                        :icon="mdiSquareEditOutline"
                                        small
                                    />
                                    <BaseButton
                                        color="danger"
                                        :icon="mdiTrashCan"
                                        small
                                        @click="destroy(item.id)"
                                    />
                                </BaseButtons>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>

    <SideModal 
    :show="showModal"
    :actionType="actionType"
    :data="data"
    @closeModal="resetModal"/>
</template>

<style>

</style>