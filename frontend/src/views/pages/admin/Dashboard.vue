<template>
	<div>	
		<v-app-bar app elevation="0">
			<v-toolbar-title
				class="headline clickable"
				@click.stop="
					$router
						.push('/dashboard/' + $store.getters['auth/AccessToken'])
						.catch(err => {})
				"
			>
				<span class="hidden-sm-and-down">{{ APP_NAME }}</span>
			</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-divider class="mx-4" inset vertical></v-divider>
			<v-menu
				:close-on-content-click="true"
				origin="center center"
				transition="scale-transition"
				:offset-y="true"
				bottom
				left
			>
				<template v-slot:activator="{ on }">
					<v-avatar size="30">
						<v-img :src="photoUser" v-on="on" />
					</v-avatar>
				</template>
				<v-list>
					<v-list-item>
						<v-list-item-avatar>
							<v-img :src="photoUser"></v-img>
						</v-list-item-avatar>
						<v-list-item-content>
							<v-list-item-title class="title">
								{{ ATTRIBUTE_USER("username") }}
							</v-list-item-title>
							<v-list-item-subtitle>
								[ {{ DEFAULT_ROLE }} ]
							</v-list-item-subtitle>
						</v-list-item-content>
					</v-list-item>
					<v-divider />					
					<v-divider />
					<v-list-item @click.prevent="logout">
						<v-list-item-icon class="mr-2">
							<v-icon>mdi-power</v-icon>
						</v-list-item-icon>
						<v-list-item-title>Logout</v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
		</v-app-bar>
		<v-main class="mx-4 mb-4">
			<v-container flud color="#f1f2f6">
				<v-row class="mt-n5">
					<v-col xs="12" sm="4" md="3" class="align-self-start" v-if="$store.getters['auth/can']('DMASTER-GROUP')">
						<v-card							
							class="clickable"							
							color="text-center"
							@click.native="$router.push('/dmaster')"
						>
							<v-card-text class="d-flex flex-column justify-center align-center">
								<v-avatar
									color="primary"
									class="v-avatar-light-bg primary--text mt-10"
									icon
									size="50"
								>
									<v-icon
										size="2rem"
										color="primary"
									>
										{{ icons.mdiSourceBranch }}
									</v-icon>
								</v-avatar>
								<h6 class="text-xl mt-4 font-weight-medium">
									DATA MASTER
								</h6>
							</v-card-text>
							<v-card-text>
								Pengaturan berbagai parameter sebagai referensi dari modul-modul lain dalam sistem.
							</v-card-text>							
						</v-card>
					</v-col>
					<v-responsive width="100%" v-if="$vuetify.breakpoint.xsOnly" />
					<v-col xs="12" sm="4" md="3" class="align-self-start" v-if="$store.getters['auth/can']('KEUANGAN-GROUP')">
						<v-card							
							class="clickable"							
							color="text-center"
							@click.native="$router.push('/keuangan')"
						>
							<v-card-text class="d-flex flex-column justify-center align-center">
								<v-avatar
									color="primary"
									class="v-avatar-light-bg primary--text mt-10"
									icon
									size="50"
								>
									<v-icon
										size="2rem"
										color="primary"
									>
										{{ icons.mdiCashMultiple }}
									</v-icon>
								</v-avatar>
								<h6 class="text-xl mt-4 font-weight-medium">
									KEUANGAN
								</h6>
							</v-card-text>
							<v-card-text>
								Modul ini digunakan untuk mengelola Keuangan Perguruan Tinggi.
							</v-card-text>							
						</v-card>
					</v-col>
					<v-responsive width="100%" v-if="$vuetify.breakpoint.xsOnly" />					
					<v-col xs="12" sm="4" md="3" class="align-self-start" v-if="$store.getters['auth/can']('AKADEMIK-GROUP')">
						<v-card							
							class="clickable"							
							color="text-center"
							@click.native="$router.push('/akademik')"
						>
							<v-card-text class="d-flex flex-column justify-center align-center">
								<v-avatar
									color="primary"
									class="v-avatar-light-bg primary--text mt-10"
									icon
									size="50"
								>
									<v-icon
										size="2rem"
										color="primary"
									>
										{{ icons.mdiCashMultiple }}
									</v-icon>
								</v-avatar>
								<h6 class="text-xl mt-4 font-weight-medium">
									AKADEMIK
								</h6>
							</v-card-text>
							<v-card-text>
								Modul ini digunakan untuk mengelola akademik Perguruan Tinggi.
							</v-card-text>							
						</v-card>
					</v-col>
					<v-responsive width="100%" v-if="$vuetify.breakpoint.xsOnly" />					
					<v-col xs="12" sm="4" md="3" class="align-self-start" v-if="$store.getters['auth/can']('KEPEGAWAIAN-GROUP')">
						<v-card							
							class="clickable"							
							color="text-center"
							@click.native="$router.push('/kepegawaian')"
						>
							<v-card-text class="d-flex flex-column justify-center align-center">
								<v-avatar
									color="primary"
									class="v-avatar-light-bg primary--text mt-10"
									icon
									size="50"
								>
									<v-icon
										size="2rem"
										color="primary"
									>
										{{ icons.mdiCashMultiple }}
									</v-icon>
								</v-avatar>
								<h6 class="text-xl mt-4 font-weight-medium">
									KEPEGAWAIAN
								</h6>
							</v-card-text>
							<v-card-text>
								Modul ini digunakan untuk mengelola kepegawaian terutama dosen.
							</v-card-text>							
						</v-card>
					</v-col>
					<v-responsive width="100%" v-if="$vuetify.breakpoint.xsOnly" />					
				</v-row>
				<v-row class="mt-n5">
					<v-col xs="12" sm="4" md="3" class="align-self-start" v-if="$store.getters['auth/can']('SYSTEM-USERS-GROUP')">
						<v-card							
							class="clickable"							
							color="text-center"
							@click.native="$router.push('/system-users')"
						>
							<v-card-text class="d-flex flex-column justify-center align-center">
								<v-avatar
									color="primary"
									class="v-avatar-light-bg primary--text mt-10"
									icon
									size="50"
								>
									<v-icon
										size="2rem"
										color="primary"
									>
										{{ icons.mdiCashMultiple }}
									</v-icon>
								</v-avatar>
								<h6 class="text-xl mt-4 font-weight-medium">
									USER SISTEM
								</h6>
							</v-card-text>
							<v-card-text>
								Modul ini digunakan untuk mengelola user sistem.
							</v-card-text>							
						</v-card>
					</v-col>
					<v-responsive width="100%" v-if="$vuetify.breakpoint.xsOnly" />
				</v-row>
			</v-container>
		</v-main>
		<v-footer app padless fixed>
			<v-card class="flex" color="yellow darken-2" flat tile>
				<v-divider></v-divider>
				<v-card-text class="py-2 black--text text-center">
					<strong>{{ APP_NAME }} (2021-2021)</strong> dikembangkan oleh TIM IT
					STISIPOL Raja Haji					
				</v-card-text>
			</v-card>
		</v-footer>
	</div>
</template>
<script>
	import { mapGetters } from "vuex";
	import { mdiSourceBranch, mdiCashMultiple } from "@mdi/js";
	export default {
		name: "Dashboard",
		created() {
			this.token = this.$route.params.token;
			this.initialize();			
		},
		setup() {
			return {
				icons: {
					mdiSourceBranch,
					mdiCashMultiple,
				}
			};
		},
		props: {
			showrightsidebar: {
				type: Boolean,
				default: true,
			},
			temporaryleftsidebar: {
				type: Boolean,
				default: false,
			},
		},
		data: () => ({
			token: null,
			loginTime: 0,
			drawer: null,
			drawerRight: null,
		}),
		methods: {
			initialize: async function() {
				await this.$ajax
					.get("/auth/me", {
						headers: {
							Authorization: "Bearer " + this.token,
						},
					})
					.then(({ data }) => {
						console.log(data);
						// this.dashboard = data.role[0];
						// this.$store.dispatch("uiadmin/changeDashboard", this.dashboard);
					})
					.catch(error => {
						if (error.response.status == 401) {
							this.$store.dispatch("auth/logout");
							this.$store.dispatch("uifront/reinit");
							this.$store.dispatch("uiadmin/reinit");							
						}
					});
				// this.$store.dispatch("uiadmin/init", this.$ajax);
			},
			logout() {
				this.loginTime = 0;
				this.$ajax
					.post(
						"/auth/logout",
						{},
						{
							headers: {
								Authorization: this.TOKEN,
							},
						}
					)
					.then(() => {
						this.$store.dispatch("auth/logout");
						this.$store.dispatch("uifront/reinit");
						this.$store.dispatch("uiadmin/reinit");
						this.$router.push("/");
					})
					.catch(() => {
						this.$store.dispatch("auth/logout");
						this.$store.dispatch("uifront/reinit");
						this.$store.dispatch("uiadmin/reinit");
						this.$router.push("/");
					});
			},
			isBentukPT(bentuk_pt) {
				return this.$store.getters["uifront/getBentukPT"] == bentuk_pt
					? true
					: false;
			},
		},
		computed: {
			...mapGetters("auth", {
				AUTHENTICATED: "Authenticated",
				ACCESS_TOKEN: "AccessToken",
				TOKEN: "Token",
				DEFAULT_ROLE: "DefaultRole",
				ATTRIBUTE_USER: "AttributeUser",
			}),
			APP_NAME() {
				return process.env.VUE_APP_NAME;
			},
			photoUser() {
				let img = this.ATTRIBUTE_USER("foto");
				var photo;
				if (img == "") {
					photo = this.$api.url + "/resources/userimages/no_photo.png";
				} else {
					photo = this.$api.url + "/" + img;
				}
				return photo;
			},
		},
		watch: {
			loginTime: {
				handler(value) {
					if (value >= 0) {
						setTimeout(() => {
							this.loginTime =
								this.AUTHENTICATED == true ? this.loginTime + 1 : -1;
						}, 1000);
					} else {
						this.$store.dispatch("auth/logout");
						this.$router.replace("/login");
					}
				},
				immediate: true,
			},
		},
	};
</script>