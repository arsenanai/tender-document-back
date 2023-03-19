export default {
  data() {
    return {
      data: {},
      actions: {
        Creation: {
          status: 201,
          method: 'POST',
        },
        Updation: {
          status: 202,
          method: 'PUT',
        }
      },
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
    },
    onSubmit(action) {
      this.alert.type = null;
      this.alert.message = null;
      if (this.validated(this.entity)) {
        this.loading = true;
        axios({
          method: this.actions[action].method,
          url: action === 'Creation' ? `/api/${this.entity.route}`: `/api/${this.entity.route}/${this.data.id}`,
          data: this.data,
          withCredentials: true,
          headers: {
            'Authorization': `Bearer ${this.getUserToken()}`,
          }
        })
        .then(response => {
          //console.log('response', response);
          if(response.status === this.actions[action].status && response.data.success === true) {
            this.alert.type = 'text-success';
            this.alert.message = this.$t(`${action} successful`);
          } else {
            this.alert.type = 'text-danger';
            this.alert.message = this.$t(`${action} failed`);
          }
        })
        .catch((error) => {
          this.alert.type = 'text-danger';
          if (error.response.status === 422) {
            for (let ii = 0; ii < this.entity.fillables.length; ii+=1) {
              if (error.response.data.errors.hasOwnProperty(this.entity.fillables[ii].codename)) {
                this.entity.fillables[ii].hasError = true;
                this.entity.fillables[ii].feedbackMessage = this.$t(error.response.data.errors[
                  this.entity.fillables[ii].codename
                ][0]);
              }
            }
            this.alert.message = this.$t('Invalid data provided');
          } else {
            this.alert.message = this.$t('Server side error, contact vendor');
          }
        })
        .then(_ => {
          this.loading = false;
        });
      }
    },
  }
}