export default {
  namespaced: true,
  state: {
    app: {
      contentLayoutNav: "Horizontal",
      routeTransition: "scroll-x-transition",
      skinVariant: "materio-skin-variant",
      contentWidth: "boxed",
    },
    menu: {
      isMenuHidden: false,
      isVerticalNavMini: false,
    },
    appBar: {
      type: "fixed",
      isBlurred: true,
    },
    footer: {
      type: "static",
    },
    themes: {
      light: {
        primary: '#9155FD',
        accent: '#0d6efd',
        secondary: '#8A8D93',
        success: '#56CA00',
        info: '#16B1FF',
        warning: '#FFB400',
        error: '#FF4C51',
      },
      dark: {
        primary: '#9155FD',
        accent: '#0d6efd',
        secondary: '#8A8D93',
        success: '#56CA00',
        info: '#16B1FF',
        warning: '#FFB400',
        error: '#FF4C51',
      },
    },
  },
  mutations: {
    UPDATE_APP_ROUTE_TRANSITION(state, value) {
      state.app.routeTransition = value
    },
    UPDATE_CONTENT_LAYOUT_NAV(state, value) {
      state.app.contentLayoutNav = value
    },
    UPDATE_APP_SKIN_VARIANT(state, value) {
      state.app.skinVariant = value
    },
    UPDATE_APP_CONTENT_WIDTH(state, value) {
      state.app.contentWidth = value
    },
    TOGGLE_MENU_MENU_HIDDEN(state, value) {
      state.menu.isMenuHidden = value
    },
    TOGGLE_MENU_VERTICAL_NAV_MINI(state, value) {
      state.menu.isVerticalNavMini = value
    },
    UPDATE_APP_BAR_TYPE(state, value) {
      state.appBar.type = value
    },
    UPDATE_APP_BAR_IS_BLURRED(state, value) {
      state.appBar.isBlurred = value
    },
    UPDATE_FOOTER_TYPE(state, value) {
      state.footer.type = value
    },
    UPDATE_THEMES(state, value) {
      state.themes = value
    },
  },
}
