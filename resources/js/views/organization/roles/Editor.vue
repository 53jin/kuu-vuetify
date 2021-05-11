<template>
    <kuu-editor-dialog
        v-model="visible"
        :loading="isLoading"
        :handing="isHandling"
        :disabled="isLoading"
        :title="title"
        @save="onFormSubmit"
    >
        <kuu-form ref="form" @submit.prevent="onFormSubmit">
            <kuu-text-field
                v-model="input.name"
                counter="15" label="Name" name="name" rules="required|max:15"
            />
        </kuu-form>
    </kuu-editor-dialog>
</template>

<script>
    import Model from './model'

    export default {
        mixins: [Model],
        data() {
            return {
                input: {
                    name: null,
                },
            }
        },
        mounted() {
            this.localId && this.find();
        },
    }
</script>
