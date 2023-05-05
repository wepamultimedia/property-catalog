<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "properties",
        bc: [
            {
                label: "properties",
                route: "admin.property_catalog.properties.index"
            }, {label: "edit"}
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, ref } from "vue";
import Table from "@core/Components/Table.vue";
import Ckeditor from "@core/Components/Form/Ckeditor.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import Select from "@core/Components/Select.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import Textarea from "@/Vendor/Core/Components/Form/Textarea.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import { __ } from "@/Vendor/Core/Mixins/translations";
import { Link, router, useForm } from "@inertiajs/vue3";
import Icon from "@core/Components/Heroicon.vue";
import Flap from "@core/Components/Flap.vue";
import { useStore } from "vuex";

const props = defineProps(["property", "categories", "images", "errors"]);

const values = reactive({
    name: null,
    summary: null,
    cover: null,
    cover_alt: null
});
const selectedLocale = ref();

const store = useStore();

const form = useForm({
    ...props.property.data
});

const imageGallery = reactive({
    flap: false,
    add: image => {
        imageGallery.form.image_url = image.url;
        imageGallery.form.image_alt = image.alt_name;
        imageGallery.form.property_id = form.id;
        imageGallery.form.post(route("admin.property_catalog.images.store"), {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                imageGallery.close();
            }
        });
    },
    edit: (image) => {
        imageGallery.form.id = image.id;
        imageGallery.form.property_id = image.property_id;
        imageGallery.form.image_url = image.image_url;
        imageGallery.form.translations = image.translations;
        imageGallery.flap = true;
    },
    update: () => {
        imageGallery.form.put(route("admin.property_catalog.images.update", {image: imageGallery.form.id}), {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                imageGallery.close();
            }
        });
    },
    close: () => {
        imageGallery.flap = false;
        imageGallery.form.reset();
    },
    position: (image, position) => {
        router.put(route("admin.property_catalog.images.position", {
            image: image.id,
            position: position
        }), {}, {
            preserveState: true,
            preserveScroll: true
        });
    },
    form: useForm({
        id: null,
        property_id: null,
        image_url: null,
        image_alt: null,
        translations: {}
    })
});

const submit = () => {
    form.put(route("admin.property_catalog.properties.update", {property: form.id}), {
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form class="pb-8"
          @submit.prevent="submit">
        <section class="text-skin-base
                    border
                    dark:border-gray-600
                    bg-white dark:bg-gray-600
                    rounded-lg
                    shadow">
            <div class="grid grid-cols-12 divide-y xl:divide-x divide-gray-300 dark:divide-gray-700">
                <!-- title, summary and body-->
                <div class="p-6 col-span-full xl:col-span-8">
                    <div class="mb-6">
                        <Input v-model="form"
                               v-model:locale="selectedLocale"
                               v-model:value="values.name"
                               :errors="errors"
                               :label="__('name')"
                               autofocus
                               name="name"
                               required
                               translation/>
                    </div>
                    <div class="mb-6">
                        <Textarea v-model="form"
                                  v-model:locale="selectedLocale"
                                  v-model:value="values.summary"
                                  :errors="errors"
                                  :label="__('summary')"
                                  name="summary"
                                  required
                                  translation/>
                    </div>
                    <div class="mb-6">
                        <div class="mt-1"
                             style="--ck-border-radius: 0.50rem">
                            <Ckeditor v-model="form"
                                      v-model:locale="selectedLocale"
                                      :errors="errors"
                                      :label="__('data_sheet')"
                                      name="data_sheet"
                                      required
                                      translation></Ckeditor>
                        </div>
                    </div>
                </div>
                <!-- draf, date, category and cover -->
                <div class="col-span-full xl:col-span-4 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-1 divide-y lg:divide-x lg:divide-y-0 xl:divide-y xl:divide-x-0 divide-gray-300 dark:divide-gray-700 gap-4">
                    <!-- draft, date and category -->
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="text-sm">{{ __("published") }}</label>
                            <ToggleButton v-model="form.published"/>
                        </div>
                        <div class="mb-6">
                            <label class="text-sm">{{ __("highlighted") }}</label>
                            <ToggleButton v-model="form.highlighted"/>
                        </div>
                        <div>
                            <Select v-model="form.category_id"
                                    :errors="errors"
                                    :label="__('select_category')"
                                    :options="categories.data"
                                    name="category_id"
                                    option-label="name"
                                    reduce
                                    required></Select>
                        </div>
                    </div>
                    <!-- cover -->
                    <div class="p-6">
                        <div class="sm:w-1/2 lg:w-full mb-6">
                            <InputImage v-model="form.cover"
                                        v-model:alt="values.cover_alt"
                                        v-model:image="values.cover"
                                        :errors="errors"
                                        :label="__('cover_image')"
                                        name="cover"/>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="form"
                                      v-model:locale="selectedLocale"
                                      v-model:value="values.cover_alt"
                                      :errors="errors"
                                      :label="__('cover_alt')"
                                      name="cover_alt"
                                      required
                                      translation/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-b-lg overflow-hidden">
                <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                    <SaveFormButton :form="form"/>
                </div>
            </div>
        </section>
    </form>
    <!-- Gallery -->
    <section class="my-8">
        <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-6">
            <span class="text-skin-base font-medium text-lg">{{ __("image_gallery") }}</span>
            <InputImage :button-label="__('add_image')"
                        :show-image="false"
                        button-class="btn btn-secondary"
                        class="flex justify-end"
                        name="cover"
                        @change="imageGallery.add"/>
        </div>
        <div class="text-skin-base
                    border
                    overflow-hidden
                    dark:border-gray-600
                    bg-white dark:bg-gray-600
                    rounded-lg
                    shadow">
            <Table :columns="['image_url', 'image_alt', 'position']"
                   :data="images"
                   delete-route="admin.property_catalog.images.destroy"
                   divide-x
                   :delete-title="__('delete_image_title')"
                   :delete-message="__('delete_image_message')"
                   edit-route="admin.property_catalog.images.edit"
                   even>
                <template #col-content-image_url="{item}">
                    <figure>
                        <img :alt="item.image_alt"
                             :src="item.image_url"
                             class="w-20 h-20 object-cover aspect-square">
                    </figure>
                </template>
                <template #col-content-position="{item}">
                    <div class="flex items-center justify-start">
                        <div class="inline-flex"
                             role="group">
                            <button class="rounded-l-lg px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                    type="button"
                                    @click="imageGallery.position(item, item.position - 1)">
                                <Icon class="m-0 fill-white h-4 w-4"
                                      icon="chevron-up"></Icon>
                            </button>
                            <span class="px-2 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase"
                                  type="button">
                                {{ item.position }}
                            </span>
                            <button class="rounded-r-lg px-1 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-0 active:bg-gray-800 transition duration-150 ease-in-out"
                                    type="button"
                                    @click="imageGallery.position(item, item.position + 1)">
                                <Icon class="m-0 fill-white h-4 w-4"
                                      icon="chevron-down"></Icon>
                            </button>
                        </div>
                    </div>
                </template>
                <template #action-edit="{item}">
                    <button class="w-8 h-6"
                            @click="imageGallery.edit(item)">
                        <icon class="fill-skin-base w-5 h-5"
                              icon="pencil-alt"></icon>
                    </button>
                </template>
            </Table>
        </div>
    </section>
    <!-- seo -->
    <section class="my-8">
        <h2 class="font mb-4">{{ __("seo") }}</h2>
        <SeoForm v-model:seo="form.seo"
                 :description="values.summary"
                 :errors="errors?.seo"
                 :image="values.cover"
                 :locale="selectedLocale"
                 :title="values.name"
                 article-type="blog_entry"
                 page-type="article"/>
    </section>
    <Flap v-model="imageGallery.flap"
          :on-close="imageGallery.close"
          close-background
          md>
        <form class="pb-8"
              @submit.prevent="imageGallery.update">
            <figure>
                <img :src="imageGallery.form.image_url"
                     class="w-full"
                     alt="">
            </figure>
            <div class="my-6">
                <Input v-model="imageGallery.form"
                       v-model:locale="selectedLocale"
                       :errors="errors"
                       :label="__('image_alt')"
                       autofocus
                       name="image_alt"
                       required
                       translation/>
            </div>
            <div class="rounded-b-lg ">
                <SaveFormButton :form="imageGallery.form"/>
            </div>
        </form>
    </Flap>
</template>
<style>
.ck-editor__editable {
    @apply min-h-[260px] max-h-[800px]
}
</style>