<template>
    <LayoutAuthenticated>
        <Head title="Contact List" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountKey"
                title="Campaigns"
                main
            >
                <BaseButton
                :route-name="route('campaign.create')"
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
                                <Sort label="Campaign" attribute="campaign" />
                            </th>
                            <th>
                                <Sort label="Recipients" attribute="recipient" />
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in campaign.data" :key="item.id">
                            <td data-label="Subject">
                                {{ item.subject }}
                            </td>
                            <td data-label="Recipients">
                                {{ item.total_sent }}
                            </td>
                            <td data-label="Action">
                                <BaseButtons type="justify-start" no-wrap>
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
</template>

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
    campaign: {
        type: Array,
        default: () => ([]),
    },
})
const formDelete = useForm({})

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("campaign.destroy", id))
    }
}
</script>

<style>

</style>