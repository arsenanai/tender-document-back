export default {
  data() {
    return {
      data: {},
    };
  },
  methods : {
    validated(entity) {
      let r = true;
      this.data = {};
      for (let i = 0; i < entity.fillables.length; i++) {
        entity.fillables[i].hasError = false;
        entity.fillables[i].feedbackMessage = null;
        this.data[entity.fillables[i].codename] = entity[entity.fillables[i].codename];
        if (
          entity.fillables[i].hasOwnProperty('required')
          && entity.fillables[i].required === true
          && (entity[entity.fillables[i].codename] === null
            || (entity[entity.fillables[i].codename] !== null 
              && entity[entity.fillables[i].codename].length === 0)
            )) {
          entity.fillables[i].hasError = true;
          entity.fillables[i].feedbackMessage = this.$t("This field is required");
          r = false;
        }
        if (entity.fillables[i].hasOwnProperty('regex')) {
          const reg = new RegExp(entity.fillables[i].regex);
          if (!reg.test(entity[entity.fillables[i].codename])) {
            entity.fillables[i].hasError = true;
            entity.fillables[i].feedbackMessage = entity.fillables[i].validationMessage;
            r = false;
          }
        }
      }
      return r;
    },
    populateData(route, entity, data) {
      console.log('entity', entity);
      if (localStorage.getItem(`${entity.route}-to-edit`) !== null) {
        data = JSON.parse(
          localStorage.getItem(`${entity.route}-to-edit`)
        );
        console.log('data', data);
        localStorage.removeItem(`${entity.route}-to-edit`);
        this.syncEntity(entity, data);
      } else {
        //fetching data from back end in case of direct url access
        this.loading = true
        axios({
          method: 'GET',
          url: `/api/${entity.route}/${route.params.id}`,
          withCredentials: true,
          headers: {
            'Authorization': `Bearer ${this.getUserToken()}`,
          }
        })
        .then((response) => {
          console.log('response', response.data);
          data = response.data.data;
          data.id = route.params.id;
          this.syncEntity(entity, data);
        }).catch(error => {
          console.log(error);
        }).then(_ => {
          this.loading = false;
        })
      }
    },
    syncEntity(entity, data) {
      for (const prop in data) {
        if (Object.prototype.hasOwnProperty.call(data, prop)) {
          entity[prop] = data[prop];
        }
      }
    }
  }
}