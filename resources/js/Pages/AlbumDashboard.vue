<template>
    <Head title="Album Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Album Dashboard
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
                            justify-between-wrapper
                        "
                    >
                        <span class="album-details">
                            <span class="album-title-dashboard">
                                {{ album.title }}
                            </span>

                            <span
                                v-if="
                                    album.description != null &&
                                    album.description != ''
                                "
                                class="album-description"
                            >
                                {{ album.description }}
                            </span>
                        </span>

                        <span>
                            <Link
                                :data="{ albumId: album.id }"
                                :href="loading ? '#' : '/edit-album'"
                                class="
                                    edit-album-btn
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
                                Edit Album
                            </Link>

                            <button
                                class="
                                    compile-btn
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
                                    opacity-25
                                "
                                disabled
                            >
                                Compile
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div
                            class="album-viewer"
                            :class="[
                                pagePhotos.length > 2
                                    ? 'album-layout-2columns'
                                    : 'album-layout-1column',
                            ]"
                        >
                            <img
                                v-for="(photo, index) in pagePhotos"
                                :key="index"
                                :src="'./' + photo.path"
                                class="album-photo"
                                :class="
                                    pagePhotos.length == 3 &&
                                    index == 2 &&
                                    'third-photo'
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="
                max-w-7xl
                mx-auto
                sm:px-6
                lg:px-8
                justify-between-wrapper
                mt-3
            "
        >
            <BreezeButton
                class="
                    pages-btn
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
                :class="{ 'opacity-25': currentPage <= 1 }"
                :disabled="currentPage <= 1"
                v-on:click="handlePreviousPage"
            >
                Previous Page
            </BreezeButton>

            <BreezeButton
                class="
                    pages-btn
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
                :class="{ 'opacity-25': currentPage >= pagesCount }"
                :disabled="currentPage >= pagesCount"
                v-on:click="handleNextPage"
            >
                Next Page
            </BreezeButton>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style>
.justify-between-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.album-details {
    display: flex;
    flex-direction: column;
}

.album-title-dashboard {
    font-size: 1.2rem;
    font-weight: bold;
    white-space: nowrap;
}

.compile-btn,
.edit-album-btn,
.pages-btn {
    cursor: pointer;
}

.compile-btn {
    margin-left: 10px;
}

.album-viewer {
    height: 500px;
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
    padding: 15px;
    display: grid;
    overflow: auto;
    grid-gap: 20px;
}

.album-layout-1column {
    grid-template-columns: 1fr;
}

.album-layout-2columns {
    grid-template-columns: repeat(2, 1fr);
}

.album-photo {
    justify-self: center;
    align-self: center;
    width: 100%;
}

.third-photo {
    grid-column-start: span 2;
}
</style>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },

    data() {
        return {
            album: Object,
            albumPhotos: [],
            loading: true,
            photosCount: 0,
            pagesCount: 0,
            currentPage: 1,
            pagePhotos: [],
        };
    },

    beforeMount() {
        let albumId = location.href.split("albumId=")[1];

        this.getAlbumInfo(albumId);
    },

    methods: {
        async getAlbumInfo(albumId) {
            try {
                this.loading = true;

                let response = await axios.get(`/album/${albumId}`);
                this.album = response.data;

                await this.getAlbumPhotos(response.data.id);
            } catch (error) {
                this.$toast.open({
                    message: "Something went wrong, please try again",
                    type: "error",
                    position: "top-right",
                    duration: 2000,
                    dismissible: true,
                    pauseOnHover: true,
                });
            } finally {
                this.loading = false;
            }
        },
        async getAlbumPhotos(albumId) {
            try {
                let response = await axios.get(`/album-photos/${albumId}`);
                this.albumPhotos = response.data;
                this.photosCount = this.albumPhotos.length;
                this.pagesCount = Math.ceil(
                    this.photosCount / this.album.layout_type
                );

                this.updatePagePhotos();
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
        handleNextPage() {
            if (this.currentPage >= this.pagesCount) return;

            this.currentPage += 1;
            this.updatePagePhotos();
        },
        handlePreviousPage() {
            if (this.currentPage <= 1) return;

            this.currentPage -= 1;
            this.updatePagePhotos();
        },
        updatePagePhotos() {
            let take = this.album.layout_type;
            let skip = (this.currentPage - 1) * take;

            this.pagePhotos = this.albumPhotos.slice(skip, skip + take);
        },
    },
};
</script>
