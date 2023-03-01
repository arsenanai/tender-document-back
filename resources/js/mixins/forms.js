export default {
  data() {
    return {
      data: {},
    };
  },
  methods : {
    validated() {
      let r = true;
      this.data = {};
      for (let i = 0; i < this.entity.fillables.length; i++) {
        this.entity.fillables[i].error = null;
        this.data[this.entity.fillables[i].codename] = this.entity[this.entity.fillables[i].codename];
        if (
          this.entity.fillables[i].hasOwnProperty('required')
          && this.entity.fillables[i].required === true
          && this.entity[this.entity.fillables[i].codename] === null) {
          this.entity.fillables[i].error = "This field is required";
          r = false;
        }
        if (!this.entity.fillables[i].regex.test(this.entity[this.entity.fillables[i].codename])) {
          this.entity.fillables[i].error = this.entity.fillables[i].validationMessage;
          r = false;
        }
      }
      return r;
    },
  }
}