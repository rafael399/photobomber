<template>
    <Head title="Gallery" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gallery
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="
                            p-6
                            bg-white
                            border-b border-gray-200
                            create-wrapper
                        "
                    >
                        <span>
                            Upload and see your
                            <span class="text-accent">photos</span> here!
                        </span>

                        <label
                            for="upload-file"
                            class="
                                custom-file-upload
                                inline-flex
                                items-center
                                px-4
                                py-2
                                bg-gray-800
                                border border-transparent
                                rounded-md
                                font-semibold
                                text-xs text-white
                                uppercase
                                tracking-widest
                                hover:bg-gray-700
                                active:bg-gray-900
                                focus:outline-none
                                focus:border-gray-900
                                focus:shadow-outline-gray
                                transition
                                ease-in-out
                                duration-150
                            "
                            :class="{ 'opacity-25': loading }"
                            :disabled="loading"
                        >
                            Upload Photo
                        </label>
                        <input
                            id="upload-file"
                            name="upload-file"
                            type="file"
                            @change="handlePhotoUpload($event)"
                            :disabled="loading"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="p-6 bg-white border-b border-gray-200 image-grid"
                    >
                        <img
                            v-for="(photo, index) in userPhotos"
                            :key="index"
                            :src="'./' + photo.path"
                        />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style>
.create-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

input[type="file"] {
    display: none;
}

.custom-file-upload {
    cursor: pointer;
}

.custom-file-upload:disabled {
    filter: brightness(4);
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 20px;
}
</style>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },

    data() {
        return {
            userPhotos: [],
            photoUpload: "",
            loading: true,
        };
    },

    beforeMount() {
        this.getUserPhotos();
    },

    methods: {
        handlePhotoUpload(event) {
            this.loading = true;

            // Pick the first selected photo
            this.photoUpload = event.target.files[0];

            // Create a FormData and append the photo
            let formData = new FormData();
            formData.append("photo", this.photoUpload);

            axios
                .post("/galleryPhoto", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.userPhotos.push(response.data);

                    this.$toast.open({
                        message: "Your photo was uploaded",
                        type: "success",
                        position: "top-right",
                        duration: 2000,
                        dismissible: true,
                        pauseOnHover: true,
                    });
                })
                .catch(() => {
                    this.$toast.open({
                        message: "Something went wrong, please try again",
                        type: "error",
                        position: "top-right",
                        duration: 2000,
                        dismissible: true,
                        pauseOnHover: true,
                    });
                })
                .then(() => {
                    this.loading = false;
                });
        },
        getUserPhotos() {
            this.loading = true;

            // Load every photo the user have in gallery
            axios
                .get("/galleryPhotos")
                .then((response) => {
                    this.userPhotos = response.data;
                })
                .catch(() => {
                    this.$toast.open({
                        message:
                            "Error while trying to load your photos, please try again",
                        type: "error",
                        position: "top-right",
                        duration: 2000,
                        dismissible: true,
                        pauseOnHover: true,
                    });
                })
                .then(() => {
                    this.loading = false;
                });
        },
    },
};
</script>
