<template>
    <span>{{m[nameKey]}}<span class="text-muted" v-if="m[idKey]"> #{{m[idKey]}}</span></span>
</template>

<script>
    export default {
        props: {
            m:{ default: {}},
            idKey: {default: 'id'},
            nameKey: {default: 'name'},
        }
    };
</script>