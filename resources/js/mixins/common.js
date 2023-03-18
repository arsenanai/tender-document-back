import { store } from '../store';

export default {
  data() {
    return {
      store,
      locales: {
        kk: 'Қазақша',
        ru: 'Русский'
      },
    };
  },
  methods: {
    basicErrorHandling(e) {
      console.log('error', e.stack);
      if (e.response) {
        if (e.response.status) {
          if (e.response.status === 401) {
            this.logout();
          } else if (e.response.status === 403) {
            return this.toast('warning', "You don't have permission");
          } else if (e.response.status === 500) {
            return this.toast('danger', 'Some error occured. Contact support');
          }
        }
      }
      return this.toast('danger', 'Try again later');
    },
    toast(type, text) {
      const message = {};
      message.type = `alert alert-+${type}`;
      message.text = text;
      return message;
    },
    stringIsSet(string) {
      return (string != null && string !== '');
    },
    arrayIsSet(array) {
      return (array != null && array.length > 0);
    },
    authenticate(userData) {
      localStorage.setItem('entries_user', JSON.stringify(userData));
      this.store.user = userData;
    },
    authenticated() {
      return this.store.user !== null;
    },
    getAuthenticatedUser() {
      this.store.user = JSON.parse(localStorage.getItem('entries_user'));
      return this.store.user;
      // return JSON.parse(localStorage.getItem('entries_user'));
    },
    eraseUserData() {
      localStorage.removeItem('entries_user');
      this.store.user = null;
    },
    goTo(route) {
      this.$router.push(route);
    },
    fetchUser() {
      const userData = this.getAuthenticatedUser();
      if (userData !== null) {
        this.store.user = userData;
      }
    },
    getUserToken() {
      return this.store.user.token;
    },
    changeLocale(locale) {
      // console.log('locale', locale);
      // this.$i18n.locale = locale;
      localStorage.setItem('entries_lang', locale);
      this.$router.go();
    },
    toTitleCase(str) {
      return str.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());
    }
  },
};
