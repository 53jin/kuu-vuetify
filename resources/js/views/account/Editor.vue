<template>
    <kuu-container :breadcrumbs="breadcrumbs">
        <v-tabs v-model="tabActiveName">
            <v-tab href="#profile">Profile</v-tab>
            <v-tab v-if="$root.isPublisher" href="#company">Company</v-tab>

            <v-tab-item value="profile">
                <v-card-text><tab-profile /></v-card-text>
            </v-tab-item>
            <v-tab-item v-if="$root.isPublisher" value="company">
                <v-card-text><tab-company /></v-card-text>
            </v-tab-item>
        </v-tabs>
    </kuu-container>
</template>

<script>
    import TabProfile from './ProfileEditor'
    import TabCompany from './TabCompany'

    export default {
        data() {
            return {
                breadcrumbs: [
                    {text: 'Account'},
                ],

                isLoading: false,
                tabActiveName: 'profile',
            }
        },
        components: {
            TabProfile,
            TabCompany,
        },
    }
</script>
