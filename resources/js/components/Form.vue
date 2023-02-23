<template>
  <div class="row justify-content-center">
    <form class="col-12 col-md-6 col-lg-4 align-self-center needs-validation" novalidate
    @submit.prevent="onSubmit"
    :class="{'was-validated': entity.fillables.some(fillable => { return fillable.hasOwnProperty('error') } ) }">
      <div v-show="alert.message!=null" 
      class="alert" role="alert"
      :class="alert.type">
        {{ alert.message }}
      </div>
      <div
        v-for="(fillable, i) in entity.fillables" :key="i"
        :class="{'mb-3' : i < entity.fillables.length}"
      >
        <label class="form-label w-100 text-capitalize"
          :for="fillable.codename">
          {{ fillable.title }}
        </label>
        <div class="input-group has-validation">
          <input 
            class="form-control"
            :type="fillable.type"
            :id="fillable.codename"
            v-model="entity[fillable.codename]"
            :pattern="fillable.regex"
            :required="{'true': fillable.hasOwnProperty('required')}"
            :class="{'invalid': fillable.hasOwnProperty('error')}">
          <div class="invalid-feedback" v-show="fillable.hasOwnProperty('error')">
            {{ fillable.error }}
          </div>
        </div>
      </div>
      <div class="">
        <button type="submit" class="btn btn-light">{{ submit.buttonName }}</button>
      </div>
    </form>
  </div>
</template>

<script>
import common from '../common';
export default {
    props:{
        entity: {
            type: Object,
            required: true,
        },
        submit: {
            type: Object,
            required: true,
        },
        alert: {
            type: Object,
            required: true,
        }
    },
    methods:{
        onSubmit() {
          this.$emit('onSubmit', this.entity);
        },
        // getRegexP(regex) {
        //   return new RegExp(regex);
        // }
    }
}
</script>