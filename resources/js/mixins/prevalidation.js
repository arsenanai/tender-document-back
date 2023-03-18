export default {
  methods: {
    onKeyup(entity, fillable) {
      if (
        fillable.hasOwnProperty('preValidation') &&
        fillable.preValidation.hasOwnProperty('minChars') &&
        entity[fillable.codename].length <= 
        fillable.preValidation.minChars){
        return;
      } else {
        this.preValidate(entity, fillable);
      }
    },
    preValidate(entity, fillable) {
      fillable.hasError = false;
      fillable.feedbackMessage = null;
      axios({
        method: fillable.preValidation.method,
        url: fillable.preValidation.link,
        params: {
          filterBy: fillable.codename,
          search: entity[fillable.codename],
        },
        withCredentials: true,
        headers: {
          'Authorization': `Bearer ${this.getUserToken()}`,
        }
      })
      .then(response => {
        console.log('response', response);
        fillable.preValidation.message(response.data.data, entity[fillable.codename], fillable);
      })
      .catch((error) => {
        console.log('error', error);
        this.alert.type = 'text-danger';
        this.alert.message = this.$t('Server side error, contact vendor');
      })
      .then(_ => {

      });
    }
  }
}