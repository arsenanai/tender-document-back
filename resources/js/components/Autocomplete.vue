<template>
  <div class="position-relative">
    <input type="hidden" :id="fillable.codename" v-model="entity[fillable.codename]">
    <div class="input-group has-validation">
      <input class="form-control"
        v-if="isEntityPopulated"
        type="text"
        :id="`${fillable.codename}_display`"
        @keyup="onKeyup"
        v-model="entity[fillable.autocomplete.for][fillable.autocomplete.displayField]"
        :pattern="fillable.regex"
        :required="{'true': fillable.hasOwnProperty('required')}"
        :class="{'invalid': fillable.error !== null}"
        :disabled="loading">
      <div class="invalid-feedback" v-show="fillable.hasOwnProperty('error')">
        {{ fillable.error }}
      </div>
    </div>
    <div class="position-absolute w-100 shadow rounded-3 top-100 left-0 mt-1 autocomplete-wrapper"
      v-if="options">
      <div class="list-group autocomplete-options">
        <a class="list-group-item list-group-item-action"
        v-for="(option,i) in options" :key="i" @click.prevent="onSelect(option)">
          <span class="text-muted font-monospace fw-bold">
            {{ getId(option[fillable.autocomplete.selectionField]) }}
          </span>
          <span class="text-left mx-1" v-html="getMarkedDisplay(option)">
          </span>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import common from '@/mixins/common';
export default {
  name: 'Autocomplete',
  mixins: [common],
  props: {
    entity: {
      type: Object,
      required: true,
    },
    fillable: {
      type: Object,
      required: true,
    },
    loading: Boolean,
  },
  data() {
    return {
      options: null,
      load: null,
    };
  },
  created() {
    this.load = this.loading;
  },
  methods: {
    getId(id) {
      return id.toString().padStart(this.entity.pad, '0');
    },
    getSelectedItemsDisplay() {
      if (this.isEntityPopulated()) {
        return this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField];
      } else
        return null;
    },
    isEntityPopulated() {
      return this.entity.hasOwnProperty(this.fillable.autocomplete.for)
      && this.entity[this.fillable.autocomplete.for].hasOwnProperty(this.fillable.autocomplete.displayField);
    },
    onKeyup() {
      if (
        this.fillable.autocomplete.hasOwnProperty('minChars') &&
        this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField].length <= 
        this.fillable.autocomplete.minChars){
        return;
      } else {
        this.load = true;
        this.options = null;
        axios({
          method: this.fillable.autocomplete.method,
          url: this.fillable.autocomplete.link,
          params: {
            by: this.fillable.autocomplete.displayField,
            search: this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField],
          },
          withCredentials: true,
          headers: {
            'Authorization': `Bearer ${this.getUserToken()}`,
          }
        })
        .then(response => {
          console.log('response', response);
          this.options = response.data.data;
        })
        .catch((error) => {
          console.log('error', error);
          this.alert.type = 'alert-danger';
          this.alert.message = 'Server side error, contact vendor';
        })
        .then(_ => {
          this.load = false;
        });
      }
    },
    onSelect(option) {
      this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField] = option[this.fillable.autocomplete.displayField];
      this.entity[this.fillable.codename] = option[this.fillable.autocomplete.selectionField];
      this.options = null;
    },
    getMarkedDisplay(option) {
      return option[this.fillable.autocomplete.displayField].replaceAll(
        this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField],
        `<b>${this.entity[this.fillable.autocomplete.for][this.fillable.autocomplete.displayField]}</b>`
      );
    }
  },
}
</script>

<style scoped>
.autocomplete-wrapper {
  z-index: 1;
}
.autocomplete-options {
  max-height: 145px;
  overflow: auto;
}
</style>