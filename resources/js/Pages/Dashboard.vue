<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
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
                            Create and list your
                            <span class="text-accent">photobomber</span> albums
                            here!
                        </span>
                        <Link
                            :href="loading ? '#' : route('create-album')"
                            class="
                                create-album-btn
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
                            :disabled="loading"
                            :class="{ 'opacity-25': loading }"
                        >
                            Create Album
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="p-6 bg-white border-b border-gray-200 album-grid"
                    >
                        <Link
                            class="album"
                            v-for="(album, index) in userAlbums"
                            :key="index"
                            :data="{ albumId: album.id }"
                            href="/album-dashboard"
                        >
                            <img
                                class="album-cover"
                                :src="
                                    album.coverPhoto != null
                                        ? './' + album.coverPhoto.path
                                        : 'https://i2.wp.com/tidyprint.in/wp-content/uploads/woocommerce-placeholder.png?fit=600%2C600&ssl=1'
                                "
                            />

                            <div class="album-details">
                                <span class="album-title">{{
                                    album.title
                                }}</span>
                                <span class="album-status">Status</span>
                                <span class="album-photo-count"
                                    >{{
                                        `${album.photoCount} ${
                                            album.photoCount == 1
                                                ? "photo"
                                                : "photos"
                                        }`
                                    }}
                                </span>
                            </div>
                        </Link>
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

.create-album-btn:disabled {
    filter: brightness(4);
}

.album-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-gap: 20px;
}

.album {
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
}

.album:hover {
    cursor: pointer;
}

.album-cover {
    height: 150px;
    width: 150px;
    background-color: red;
}

.album-details {
    width: 150px;

    display: flex;
    flex-direction: column;

    font-size: 0.75rem;
    line-height: 1rem;
}

.album-title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: bold;
    font-size: 0.785rem;
    align-self: center;
    margin-bottom: 5px;
    padding-top: 5px;
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
            userAlbums: [],
        };
    },

    beforeMount() {
        this.getUserAlbums();
    },

    methods: {
        async getUserAlbums() {
            try {
                this.loading = true;

                // Load every photo the user have in gallery
                let response = await axios.get("/albums");
                this.userAlbums = response.data;
            } catch (error) {
                this.$toast.open({
                    message:
                        "Error while trying to load your albums, please try again",
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
    },
};
</script>
