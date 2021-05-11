<template>
    <v-app>
        <v-content>
            <v-container fluid fill-height>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <slot/>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
    }
</script>
