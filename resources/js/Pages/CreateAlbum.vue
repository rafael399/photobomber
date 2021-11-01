<template>
    <Head title="Create Album" />

    <BreezeValidationErrors class="mb-4" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Album
            </h2>
        </template>

        <div class="py-12 wrapper">
            <div
                class="
                    w-full
                    sm:max-w-md
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
                            />
                            <BreezeLabel
                                for="4photos"
                                value="4 photos per page"
                                class="inline"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BreezeButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Create
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

    data() {
        return {
            form: this.$inertia.form({
                title: "",
                description: "",
                layout: 1,
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("album"), {
                onFinish: () => {
                    this.$toast.open({
                        message: "Your album was created",
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
