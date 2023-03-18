<template>
  <form @submit.prevent="$emit('onSearch', input)">
    <div class="input-group">
      <input type="text" class="form-control" :placeholder="$t('Search')" aria-describedby="button-addon2"
        v-model="input">
      <button class="btn btn-outline-secondary text-dark" type="submit" id="button-addon2">
        {{$t('Search')}}
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'SearchForm',
  data() {
    return {
      input: null,
    };
  },
  mounted(){
    this.input = this.$route.query.search;
  },
}
</script>