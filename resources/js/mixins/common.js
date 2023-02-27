import { store } from '../store';

export default {
  data() {
    return {
      store,
    };
  },
  methods: {
    /* display(what,target){
      var result = ''
      if(target!==null)
          if(what==='name')
              result = target['name_'+this.$i18n.locale]
          else if(what==='description')
              result = target['description_'+this.$i18n.locale]
        //if(this.stringIsSet(result) &&result.length>100)
        //result = result.substring(0,75)+' ... '+result.substring(result.length-25,result.length)
         return result
      },
      view(what,target){
      var result = ''
      if(target!==null)
          if(what==='name')
              result = target['name_'+this.$i18n.locale]
          else if(what==='description')
              result = target['description_'+this.$i18n.locale]
        if(this.stringIsSet(result) &&result.length>100)
          result = result.substring(0,75)+' ... '+result.substring(result.length-25,result.length)
         return result
      }, */
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
      console.log('previous user', this.store.user);
      const userData = this.getAuthenticatedUser();
      if (userData !== null) {
        this.store.user = userData;
      }
    },
    getUserToken() {
      return this.store.user.token;
    },
  },
};
