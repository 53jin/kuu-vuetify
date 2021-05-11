<template>
    <v-app>
        <v-navigation-drawer
            v-model="drawer"
            fixed app
            :mini-variant.sync="miniVariant"
        >
            <v-list-item>
                <v-list-item-content>
                    <v-img
                        v-if="$root.app.logo_image_url && !isDark"
                        :src="$root.app.logo_image_url"
                        max-height="58" :aspect-ratio="270/60"
                    >
                        <template #placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-row>
                        </template>
                    </v-img>
                    <v-toolbar-title v-else class="text-center font-weight-bold" style="line-height:40px;font-size:1.6rem;">
                        {{ $root.app.name }}
                    </v-toolbar-title>
                </v-list-item-content>
            </v-list-item>
            <v-divider/>
            <v-list>
                <template v-for="item in items" v-if="canList(item)">
                    <v-list-item
                        ripple
                        v-if="!item.children"
                        :key="item.url"
                        :to="{path: '/' + item.url}"
                        link
                        exact
                    >
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"/>
                        </v-list-item-icon>
                        <v-list-item-title v-text="item.text"/>
                    </v-list-item>

                    <v-list-group
                        v-else
                        no-action
                        :value="listGroupValue(item)"
                        :prepend-icon="item.icon"
                        exact
                    >
                        <template v-slot:activator>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </template>

                        <v-list-item
                            dense
                            ripple
                            v-for="(children, i) in item.children"
                            v-if="canList(children)"
                            :key="i"
                            :to="{path: '/' + children.url}"
                            link
                            exact
                        >
                            <v-list-item-title v-text="children.text"/>
                            <v-list-item-icon>
                                <v-icon v-text="children.icon"/>
                            </v-list-item-icon>
                        </v-list-item>
                    </v-list-group>
                </template>
            </v-list>

            <template #append>
                <v-divider/>
                <div class="px-4 py-2 d-flex">
                    <v-spacer/>
                    <v-btn icon @click="isDark = !isDark">
                        <v-icon>mdi-brightness-{{ isDark ? '4' : '7'}}</v-icon>
                    </v-btn>
                </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar app dark color="primary">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>{{ $root.app.name }}</v-toolbar-title>

            <v-spacer/>
            <v-menu bottom left offset-y transition="slide-y-transition">
                <template v-slot:activator="{ on }">
                    <v-btn text v-on="on">
                        <span class="text-none" v-text="$root.user.name"/>
                        <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item>
                        <div class="ma-3">
                            <v-list-item-title>
                                {{ $root.user.name }}
                            </v-list-item-title>
                            <v-list-item-subtitle>
                                {{ $root.user.email }}
                            </v-list-item-subtitle>
                        </div>
                    </v-list-item>
                    <v-divider/>
                    <v-list-item to="/account" exact>
                        <v-list-item-title>
                            <v-icon>mdi-account</v-icon> Account
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="logout" exact>
                        <v-list-item-title>
                            <v-icon>mdi-logout</v-icon> Logout
                            <form ref="logoutForm" :action="url('logout')" method="POST" class="d-none">
                                <input type="hidden" name="_token" :value="$root.csrfToken"/>
                            </form>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main>
            <v-slide-x-reverse-transition mode="out-in">
                <keep-alive>
                    <router-view v-if="$route.meta.keepAlive"/>
                </keep-alive>
            </v-slide-x-reverse-transition>
            <v-slide-x-reverse-transition mode="out-in">
                <router-view v-if="!$route.meta.keepAlive"/>
            </v-slide-x-reverse-transition>

            <v-footer app inset>
                <v-row dense>
                    <v-col cols="12" class="text-center">
                        &copy; {{ new Date().getFullYear() }} - <strong>{{ $root.user ? $root.app.name : 'Kuu-vuetify' }}</strong>. All rights reserved
                    </v-col>
                </v-row>
            </v-footer>
        </v-main>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                miniVariant: false,
                drawer: null,
            }
        },
        mounted() {
            const el = document.querySelector('div.preloader');
            if (el) {
                el.style.opacity = 0;
                setTimeout(() => {
                    el.remove()
                }, 300);
            }
        },
        computed: {
            items() {
                return [

                    // Organization
                    {
                        icon: 'fas fa-sitemap',
                        text: 'Organization',
                        children: [
                            {
                                text: 'Roles',
                                url: 'organization/roles',
                                mp: 'role',
                                perm: 'list-*',
                            },
                            {
                                text: 'Employees',
                                url: 'organization/employees',
                                mp: 'employee',
                                perm: 'list-*',
                            },
                        ],
                    },
                    // End Organization

                    // Settings
                    {
                        icon: 'fas fa-cogs',
                        text: 'Settings',
                        children: [
                            {
                                text: 'Account',
                                url: 'account',
                            },
                        ],
                    },
                    // End Settings
                ];
            },
            isDark: {
                get() {
                    let isDark = localStorage.getItem('config.dark') === '1';
                    this.$vuetify.theme.dark = isDark;
                    return isDark;
                },
                set(val) {
                    if (val) {
                        localStorage.setItem('config.dark', "1")
                    } else {
                        localStorage.removeItem('config.dark');
                    }

                    this.$vuetify.theme.dark = val;
                },
            },
        },
        methods: {
            toolbarSideClicked() {
                if (this.$vuetify.breakpoint.mdAndUp) {
                    this.miniVariant = !this.miniVariant;
                    this.drawer = true;
                } else {
                    this.drawer = !this.drawer;
                }
            },
            canList(item) {
                const vm = this;
                if (vm.$root.isPublisher) {
                    return true;
                }

                if (!item.children) {
                    return vm.can(item.mp, item.perm);
                }

                return item.children.some(v => {
                    return vm.can(v.mp, v.perm);
                });
            },
            listGroupValue(item) {
                const vm = this;
                return vm.$route.matched.some(route => {
                    return item.children.some(v => {
                        return route.path.indexOf(v.url) === 1;
                    });
                });
            },
            logout() {
                this.$refs.logoutForm.submit();
            },
        },
    }
</script>
