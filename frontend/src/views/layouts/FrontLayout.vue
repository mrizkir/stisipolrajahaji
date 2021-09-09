<template>
  <div>
    <v-app-bar
      app
      flat
      :absolute="appBarType === 'static'"
      :elevate-on-scroll="appBarType !== 'static'"
      :elevation="appBarType !== 'static' ? $vuetify.theme.isDark ? 4: 3 : 0"
      class="mx-auto app-bar-static"
    >
      <v-toolbar-title>
				<span class="hidden-sm-and-down">STISIPOL Raja Haji</span>
        <v-spacer />        
			</v-toolbar-title>
      <v-spacer />
      <v-btn to="/" class="mr-2" color="primary" text large>
        Home
      </v-btn>
      <v-btn to="/login" class="mr-2" color="primary" text large>
        Login
      </v-btn>
    </v-app-bar>
    <v-main>
      <div class="app-content-container mx-auto h-full">
        <slot></slot>
      </div>
    </v-main>
  </div>
</template>
<script>  
  export default {	
    name: "frontlayout",
    data: () => ({
      appBarType: "fixed",
    }),
  }
</script>
<style lang="scss" scoped>
@import '~@core/layouts/styles/_variables';

.app-content-container {
	padding: $content-padding-vertical-navigation-menu;
}

@include theme(app-navigation-menu) using ($material) {
	background-color: map-deep-get($material, 'background');
}

// Vuetify Fix
// https://github.com/vuetifyjs/vuetify/issues/13327
$nav-drawer-mini-width: 68px;

.v-application {
	.v-main,
	.v-footer {
		transition-duration: 0.3s;
	}

	.v-footer {
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
	}
	@include theme(v-footer) using ($material) {
		// Elevation 3 with -y
		&.v-footer--fixed {
			box-shadow: 0 -4px 8px -4px rgba(map-deep-get($material, 'shadow-color'), 0.42) !important;
		}
	}

	&.nav-drawer-mini {
		.v-main {
			// We haven't used `ltr` & `rtl` mixin because those doesn't work with top-level selectors: https://github.com/vuetifyjs/vuetify/issues/13987
			@at-root {
				.nav-drawer-mini {
					&.v-application--is-ltr {
						.v-main {
							padding-left: $nav-drawer-mini-width !important;
						}
					}
					&.v-application--is-rtl {
						.v-main {
							padding-right: $nav-drawer-mini-width !important;
						}
					}
				}
			}
		}
		@media #{map-get($display-breakpoints, 'lg-and-up')} {
			.app-navigation-menu ~ .v-footer,
			.app-navigation-menu + .v-app-bar {
				left: $nav-drawer-mini-width !important;
			}
		}
	}

	.v-app-bar,
	.v-footer {
		max-width: calc(1440px - (1.5rem * 2));
		@media screen and (max-width: 1456px) {
			margin-left: 1.5rem !important;
			margin-right: 1.5rem !important;
		}

		@at-root .v-application {
			&.content-full {
				.v-footer,
				.v-app-bar {
					max-width: unset;
					margin-left: $content-padding-vertical-navigation-menu !important;
					margin-right: $content-padding-vertical-navigation-menu !important;
				}
			}

			&:not(.nav-drawer-mini) {
				@media screen and (max-width: 1711px) {
					.app-navigation-menu ~ .v-footer,
					.app-navigation-menu + .v-app-bar {
						margin-left: 1.5rem !important;
						margin-right: 1.5rem !important;
					}
				}
			}
			&.nav-drawer-mini {
				@media screen and (max-width: 1523px) {
					.v-footer,
					.v-app-bar {
						margin-left: 1.5rem !important;
						margin-right: 1.5rem !important;
					}
				}
			}
		}
	}

	.v-app-bar {
		border-radius: 0 0 10px 10px !important;
		z-index: 5;

		&.v-toolbar:not(.app-bar-shinked) {
			background-color: transparent;
		}

		&.app-bar-static {
			will-change: padding, background-color;
			transition: padding 0.2s ease, background-color 0.18s ease, left 0.3s ease;

			&.v-toolbar.v-sheet:not(.v-app-bar--is-scrolled) {
				box-shadow: none !important;
			}

			::v-deep > .v-toolbar__content {
				padding: 0;
			}
		}
	}
}

@include theme(v-app-bar) using ($material) {
	&.v-toolbar.app-bar-shinked {
		background-color: map-deep-get($material, 'cards');
	}
}

.v-application.content-layout {
	@media #{map-get($display-breakpoints, 'md-and-down')} {
		.v-main,
		.v-footer,
		.v-app-bar {
			max-width: unset;
			left: 0 !important;
			@include ltr() {
				padding-left: 0 !important;
			}
			@include rtl() {
				padding-right: 0 !important;
			}
		}
	}
}
</style>
