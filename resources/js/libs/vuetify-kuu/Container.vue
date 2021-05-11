<template>
    <div class="mc-container">
        <v-toolbar height="60" v-if="breadcrumbs || urls || $slots['urls.append']">
            <v-breadcrumbs class="pa-0" v-if="breadcrumbs" :items="breadcrumbs">
                <template #item="{ item }">
                    <router-link
                        v-if="item.href"
                        :to="item.href"
                        :class="[item.disabled && 'grey--text']"
                    >
                        {{ item.text }}
                    </router-link>
                    <v-toolbar-title v-else v-text="item.text"/>
                </template>
            </v-breadcrumbs>
            <v-spacer/>
            <v-toolbar-items v-if="urls || $slots['urls.append']">
                <template v-if="urls.edit">
                    <v-btn v-if="isFunction(urls.edit)" @click="urls.edit" ripple icon>
                        <v-icon>edit</v-icon>
                    </v-btn>
                    <v-btn v-else :to="urls.edit" ripple icon>
                        <v-icon>edit</v-icon>
                    </v-btn>
                </template>

                <template v-if="urls.add">
                    <v-btn v-if="isFunction(urls.add)" @click="urls.add" ripple icon color="primary">
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn v-else :to="urls.add" ripple icon color="primary">
                        <v-icon>add</v-icon>
                    </v-btn>
                </template>
                <slot name="urls.append"/>
            </v-toolbar-items>
        </v-toolbar>

        <v-container fluid><v-card><slot/></v-card></v-container>
    </div>
</template>

<script>
    export default {
        props: {
            breadcrumbs: {type: Boolean | Array, default: false},
            urls: {type: Boolean | Array, default: false},
        },
        methods: {
            isFunction(value) {
                return _.isFunction(value);
            }
        }
    };
</script>
