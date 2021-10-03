<template>
	<div>	
		<v-app-bar app>
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
			<slot />
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
	export default {
		name: "Dashboard",
		created() {
			this.token = this.$route.params.token;
			this.initialize();
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
							this.$router.push("/login");
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