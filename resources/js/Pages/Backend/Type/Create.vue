<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "types",
        bc: [
            {
                label: "types",
                route: "admin.property_catalog.types.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import {__} from "@core/Mixins/translations";
import {useStore} from "vuex";
import {useForm} from "@inertiajs/vue3";

const props = defineProps(["type", "errors"]);

const store = useStore();

const form = useForm({
    translations: {},
    ...props.type.data,
});

const submit = () => {
    form.post(route("admin.property_catalog.types.store"), {
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base ">
                <div class="col-span-1">
                    <p class="text-sm"
                       v-html="__('create_summary')"></p>
                </div>
                <div class="col-span-2 border dark:border-gray-600 bg-white dark:bg-gray-600 rounded-lg shadow">
                    <div class="grid grid-cols-6 p-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <Input v-model="form"
                                   :errors="form.errors"
                                   :label="__('name')"
                                   autofocus
                                   name="name"
                                   translation/>

                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
