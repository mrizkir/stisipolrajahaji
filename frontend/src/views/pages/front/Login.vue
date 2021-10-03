<template>
	<div class="auth-wrapper auth-v2">
		<div class="auth-inner">
			<!-- brand logo -->
			<router-link
				to="/"
				class="brand-logo d-flex align-center"
			>
				<v-img
					:src="require('@/assets/images/svg/logo.svg')"
					max-height="30px"
					max-width="30px"
					alt="logo"
					contain
					class="me-3 "
				></v-img>

				<h2 class="text--primary">
					STISIPOL Raja Haji
				</h2>
			</router-link>
			<!--/ brand logo -->
			<v-row class="auth-row ma-0">
				<v-col
					lg="8"
					class="d-none d-lg-block position-relative overflow-hidden pa-0"
				>
					<div class="auth-illustrator-wrapper">
						<!-- triangle bg -->
						<img
							height="362"
							class="auth-mask-bg"
							:src="require(`@/assets/images/misc/mask-v2-${$vuetify.theme.dark ? 'dark':'light'}.png`)"
						/>

						<!-- tree -->
						<v-img
							height="226"
							width="300"
							class="auth-tree"
							src="@/assets/images/misc/tree-4.png"
						>
						</v-img>
						<!-- 3d character -->
						<div class="d-flex align-center h-full pa-16 pe-0">
							<v-img
								contain
								max-width="100%"
								height="692"
								class="auth-3d-group"
								:src="require(`@/assets/images/3d-characters/group-${$vuetify.theme.dark ? 'dark' : 'light'}.png`)"
							></v-img>
						</div>
					</div>
				</v-col>
				<v-col
					lg="4"
					class="d-flex align-center auth-bg pa-10 pb-0"
				>
					<v-row>
						<v-col
							cols="12"
							sm="8"
							md="6"
							lg="12"
							class="mx-auto"
						>
							<v-card flat>
								<v-card-text>
									<p
										class="text-2xl font-weight-semibold text--primary mb-2"
									>
										Selamat datang ke PortalEkampus! 👋🏻
									</p>
									<p class="mb-2">
										Silahkan login dengan menggunakan username dan password
									</p>
								</v-card-text>
								<v-card-text>
									<v-form ref="frmlogin" @keyup.native.enter="doLogin" lazy-validation>
										<v-text-field
											v-model="formlogin.username"
											label="Username"
											placeholder="Username"
											:rules="rule_username"
											outlined
											hide-details="auto"
											class="mb-6"
										/>
										<v-text-field
											v-model="formlogin.password"
											label="Password"
											placeholder="Password"
											type="password"
											:rules="rule_password"
											outlined									
											hide-details="auto"
											class="mb-5"
										/>
										<v-select
											:items="$store.getters['auth/DaftarRoles']"
											v-model="formlogin.page"
											outlined
											label="Login Sebagai"
											hide-details="auto"
											class="mb-2"
										>											
										</v-select>
										<v-btn
											block
											color="primary"											
											class="mt-6"
											@click="doLogin"
											:disabled="btnLoading"
										>
											Login
										</v-btn>
									</v-form>
								</v-card-text>
								<v-card-text>
									<v-alert
										text
										type="error"
										:value="form_error"
									>
										<small class="d-block mb-1">
											Username atau Password tidak dikenal.
										</small>										
									</v-alert>
								</v-card-text>
							</v-card>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</div>
	</div>
</template>
<script>
	export default {
		name: "Login",
		created() {
			if (this.$store.getters["auth/Authenticated"]) {				
				this.$router.push(
					"/dashboard/" + this.$store.getters["auth/AccessToken"]
				);
			}
		},
		data:() => ({
			btnLoading: false,
			//form
			form_error: false,
			formlogin: {
				username: "",
				password: "",
				page: null,
			},
			rule_username: [
				value => !!value || "Kolom Username mohon untuk diisi !!!",
			],
			rule_password: [
				value => !!value || "Kolom Password mohon untuk diisi !!!",
			],
		}),
		methods: {
			async doLogin() {
				if (this.$refs.frmlogin.validate()) {
					this.btnLoading = true;
					await this.$ajax
						.post("/auth/login", {
							username: this.formlogin.username,
							password: this.formlogin.password,
							page: this.formlogin.page,
						})
						.then(({ data }) => {
							this.$ajax
								.get("/auth/me", {
									headers: {
										Authorization: data.token_type + " " + data.access_token,
									},
								})
								.then(response => {
									var data_user = {
										token: data,
										user: response.data,
									};
									this.$store.dispatch("auth/afterLoginSuccess", data_user);
								});
							this.btnLoading = false;
							this.form_error = false;
							this.$router.push("/dashboard/" + data.access_token);
						})
						.catch(() => {
							this.form_error = true;
							this.btnLoading = false;
						});
				}
			}
		},
	};
</script>
<style lang="scss" scoped>
@import '@core/preset/preset/pages/auth.scss';
</style>