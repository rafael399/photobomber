<template>
    <Head title="Edit Album" />

    <BreezeValidationErrors class="mb-4" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Album
            </h2>
        </template>

        <div class="py-12 wrapper">
            <div
                class="
                    w-full
                    max-w-7xl
                    mt-6
                    px-6
                    py-4
                    bg-white
                    shadow-md
                    overflow-hidden
                    sm:rounded-lg
                "
            >
                <form @submit.prevent="submit">
                    <div>
                        <BreezeLabel for="title" value="Title" />
                        <BreezeInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="description" value="Description" />
                        <BreezeInput
                            id="description"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.description"
                        />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="" value="Choose a Layout" />
                        <div>
                            <BreezeInput
                                id="1photo"
                                type="radio"
                                name="layout"
                                modelValue="1"
                                class="mr-1"
                                v-model="form.layout"
                                required
                                :checked="form.layout == 1"
                            />
                            <BreezeLabel
                                for="1photo"
                                value="1 photo per page"
                                class="inline"
                            />
                        </div>

                        <div>
                            <BreezeInput
                                id="2photos"
                                type="radio"
                                name="layout"
                                modelValue="2"
                                class="mr-1"
                                v-model="form.layout"
                                required
                                :checked="form.layout == 2"
                            />
                            <BreezeLabel
                                for="2photos"
                                value="2 photos per page"
                                class="inline"
                            />
                        </div>

                        <div>
                            <BreezeInput
                                id="4photos"
                                type="radio"
                                name="layout"
                                modelValue="4"
                                class="mr-1"
                                v-model="form.layout"
                                required
                                :checked="form.layout == 4"
                            />
                            <BreezeLabel
                                for="4photos"
                                value="4 photos per page"
                                class="inline"
                            />
                        </div>
                    </div>

                    <BreezeLabel
                        for=""
                        value="Choose which photos to add to your album"
                        class="mt-4"
                    />
                    <div
                        class="p-6 bg-white border-b border-gray-200 image-grid"
                    >
                        <img
                            v-for="(photo, index) in userPhotos"
                            :key="index"
                            :src="'./' + photo.path"
                            v-on:click="handleChoosePhoto(photo)"
                            class="image"
                            v-bind:class="{ selected: photo.selected }"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton
                            class="ml-4"
                            :class="{
                                'opacity-25': form.processing || loading,
                            }"
                            :disabled="form.processing || loading"
                        >
                            Update
                        </BreezeButton>
                    </div>
                </form>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style>
.wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

.create-album-btn:disabled {
    filter: brightness(4);
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 20px;
}

.image {
    cursor: pointer;
}

.selected {
    border: 5px solid #0099ff;
}
</style>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    props: {
        albumId: Number,
    },

    beforeMount() {
        let albumId = location.href.split("albumId=")[1];

        this.getAlbumInfo(albumId);
    },

    data() {
        return {
            form: this.$inertia.form({
                albumId: "",
                title: "",
                description: "",
                layout: "",
                selectedPhotosId: [],
            }),
            userPhotos: [],
            loading: true,
        };
    },

    methods: {
        async getAlbumInfo(albumId) {
            try {
                this.loading = true;

                let response = await axios.get(`/album/${albumId}`);
                this.form.albumId = response.data.id;
                this.form.title = response.data.title;
                this.form.description = response.data.description;
                this.form.layout = response.data.layout_type;

                await this.getAlbumPhotos(response.data.id);
                await this.getUserPhotos();
            } catch (error) {
                this.$toast.open({
                    message: "Something went wrong, please try again",
                    type: "error",
                    position: "top-right",
                    duration: 2000,
                    dismissible: true,
                    pauseOnHover: true,
                });

                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async getUserPhotos() {
            try {
                // Load every photo the user have in gallery
                let response = await axios.get("/galleryPhotos");
                let photoArray = response.data.map((photo) => {
                    let indexInSelectedPhotosId =
                        this.form.selectedPhotosId.findIndex(
                            (id) => id == photo.id
                        );
                    let selected = false;

                    if (indexInSelectedPhotosId >= 0) selected = true;

                    return {
                        ...photo,
                        selected,
                    };
                });

                this.userPhotos = photoArray;
            } catch (error) {
                this.$toast.open({
                    message:
                        "Error while trying to load your photos, please try again",
                    type: "error",
                    position: "top-right",
                    duration: 2000,
                    dismissible: true,
                    pauseOnHover: true,
                });

                console.error(error);
            }
        },
        async getAlbumPhotos(albumId) {
            try {
                let response = await axios.get(`/album-photos/${albumId}`);

                // Retrieve only the photo IDs and set the selectedPhotosId as this array
                let photoIds = response.data.map((photo) => photo.id);
                this.form.selectedPhotosId = photoIds;
            } catch (error) {
                this.$toast.open({
                    message: "Something went wrong, please try again",
                    type: "error",
                    position: "top-right",
                    duration: 2000,
                    dismissible: true,
                    pauseOnHover: true,
                });
            }
        },
        handleChoosePhoto(photo) {
            if (photo.selected) {
                let indexInSelectedPhotosId =
                    this.form.selectedPhotosId.findIndex(
                        (id) => id == photo.id
                    );
                this.form.selectedPhotosId.splice(indexInSelectedPhotosId, 1);
                photo.selected = false;
            } else {
                this.form.selectedPhotosId.push(photo.id);
                photo.selected = true;
            }
        },
        submit() {
            this.form.put(this.route("album"), {
                onFinish: () => {
                    this.$toast.open({
                        message: "Your album was updated",
                        type: "success",
                        position: "top-right",
                        duration: 2000,
                        dismissible: true,
                        pauseOnHover: true,
                    });
                },
            });
        },
    },
};
</script>
