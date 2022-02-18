
require('./overrides.scss')

// Skins
require('./skins/bordered.scss')
require('./skins/semi-dark.scss')

export default {
  theme: {
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
    dark: false,      
  },
  rtl: false,
}
