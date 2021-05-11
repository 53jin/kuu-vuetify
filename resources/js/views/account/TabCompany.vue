<template>
    <v-form ref="form" @submit.prevent="onSubmit">
        <v-text-field v-model="company_form.company_name" label="Company name"/>
        <v-textarea v-model="company_form.company_address" label="Company address" rows="3" auto-grow/>


        <kuu-countries-select
            v-model="company_form.company_country"
            item-value="code"
        />

        <v-text-field v-model="company_form.company_city" label="Company city"/>
        <v-text-field v-model="company_form.company_url" label="Company url" type="url"/>

        <v-btn color="primary" type="submit" :loading="isHandling">Save</v-btn>
    </v-form>
</template>

<script>

    export default {
        data() {
            return {
                isHandling: false,
                labelPosition: 'top',
                company_form: {
                    name: null,
                    user_id: null,
                    company_name: null,
                    company_url: null,
                    company_country: null,
                    company_city: null,
                    company_address: null,

                },

                tabActiveName: 'company',
                country_list: '',
                response_data: null,

            }
        },
        mounted() {
            // this.companyData();
            // this.countryList();
        },
        methods: {
            onItemSaved() {
                this.appMessageSuccess('Successfully saved.');
            },
            updatePublisherData(data) {
                this.company_form = Object.assign({}, this.company_form, data);
            },
            countryList() {
                this.$api.get(`common/countries`, {})
                    .then((response) => {
                        console.log('success');

                        this.isLoading = false;

                        let data = response.data.data;
                        this.country_list = data;

                    }, (error) => {
                        this.appResponseError(error)
                    })
                    .catch((error) => {
                        this.appResponseError(error)
                    })
                    .finally(() => {
                    });
            },
            companyData() {
                console.log('fetchData');
                this.isLoading = true;
                this.$api
                    .get(`account/show/company`, {
                        params: {
                            // include: 'user'
                        }
                    })
                    .then((response) => {
                        console.log('success');

                        this.isLoading = false;

                        let data = response.data;
                        this.updatePublisherData(data);

                    }, (error) => {
                        this.appResponseError(error)
                    })
                    .catch((error) => {
                        this.appResponseError(error)
                    })
                    .finally(() => {
                    });
            },

            onSubmit() {
                this.isHandling = true;

                this.$api.post(`account/update/company`, this.company_form)
                    .then((response) => {
                        console.log('success');

                        this.onItemSaved();

                    }, (error) => {
                        this.appResponseError(error)
                    })
                    .catch((error) => {
                        this.appResponseError(error)
                    })
                    .finally(() => {
                        this.isHandling = false;
                    });


            },


        },
    }
</script>
