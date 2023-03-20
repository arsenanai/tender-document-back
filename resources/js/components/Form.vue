<template>
  <div>
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-4 align-self-center bg-white pt-3 rounded-top">
        <h1 class="fs-3 text-capitalize text-center mb-3">{{ entity.label }}</h1>
      </div>
    </div>
    <div class="row justify-content-center">
      <form class="col-12 col-md-6 col-lg-4 align-self-center bg-white pb-3 rounded-bottom"
      autocomplete="off"
      @submit.prevent="onSubmit"
      novalidate>
        <div
        v-show="alert.message!=null"
        class="p-3 mb-3 border rounded bg-light" :class="alert.type" role="alert"
        v-html="alert.message">
        </div>
        <div
          v-for="(fillable, i) in entity.fillables" :key="i"
          :class="{'mb-3' : i < entity.fillables.length && fillable.type !== 'hidden'}"
          :style="{'display: none': fillable.type === 'hidden'}"
        >
          <label class="form-label w-100 text-capitalize"
            :for="fillable.codename" v-if="fillable.type !== 'hidden'">
            {{ fillable.title }}
          </label>
          <Autocomplete
            v-if="'autocomplete' === fillable.type"
            :entity="entity"
            :fillable="fillable"
            :loading="loading"
            :disabled="fillable.hasOwnProperty('dependsOn') && entity[fillable.dependsOn] === null"
          />
          <input type="hidden"
          :name="fillable.codename"
          v-model="entity[fillable.codename]"
          v-if="'hidden' === fillable.type"
          />
          <div class="input-group has-validation" v-else>
            <input
              v-if="['text', 'email', 'password', 'number', 'date', 'tel', 'search', 'url', 'time', 'range',
              'color'].includes(fillable.type)"
              class="form-control"
              :type="fillable.type"
              :name="fillable.codename"
              :id="fillable.codename"
              v-model="entity[fillable.codename]"
              @keyup="$emit('onKeyup', entity, fillable)"
              :pattern="fillable.regex"
              :placeholder="fillable.placeholder"
              :required="{'true': fillable.hasOwnProperty('required')}"
              :class="{'is-invalid': fillable.hasError, 'is-valid': fillable.hasError === false}"
              :disabled="loading"
              :aria-describedby="`${fillable.codename}Feedback`"
            />
            <textarea
              v-if="'textarea' === fillable.type"
              class="form-control"
              :id="fillable.codename"
              v-model="entity[fillable.codename]"
              :required="{'true': fillable.hasOwnProperty('required')}"
              :class="{'is-invalid': fillable.hasError}"
              :disabled="loading"
              :aria-describedby="`${fillable.codename}Feedback`"
            ></textarea>
            <div :class="{'invalid-feedback': fillable.hasError, 'valid-feedback': fillable.hasError === false}"
              :id="`${fillable.codename}Feedback`"
              v-if="fillable.feedbackMessage"
              v-html="fillable.feedbackMessage">
            </div>
          </div>
        </div>
        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-light" :disabled="loading">
            <span v-if="loading">
              {{ $t('Loading') }}...
            </span>
            <span v-else>
              {{ submit }}
            </span>
          </button>
          <button class="btn btn-light" :disabled="loading" @click.prevent="$router.go()">
            {{ $t('Refresh') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Autocomplete from '@/components/Autocomplete.vue';
export default {
  name: 'Form',
  components: {
    Autocomplete,
  },
  props:{
    entity: {
      type: Object,
      required: true,
    },
    submit: {
      type: String,
      required: true,
    },
    alert: {
      type: Object,
      required: true,
    },
    loading: Boolean,
  },
  methods:{
    onSubmit() {
      this.$emit('onSubmit');
    },
    hasNoErrors() {
      for(const fillable in this.entity.fillables) {
        if (fillable.hasError) {
          return false;
        }
      }
      return true;
    }
  }
}
</script>