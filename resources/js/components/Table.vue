<template>
  <div>
    <h1 class="fs-2 text-capitalize">{{ entity.label }}</h1>
    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
      <button class="btn btn-sm btn-light" @click="$emit('new-entity', entity.name)">
        Add New
      </button>
      <div class="d-flex flex-row gap-2 align-items-center" v-if="entity.page !== null && entity.page.to > 0">
        <span>
          <span v-if="entity.page.last_page > entity.page.current_page">
            {{ entity.page.from }} - {{ entity.page.to }} &#47;
          </span>
          <span>{{ entity.page.total }}</span>
        </span>
        <button class="btn btn-sm btn-light fw-bold" v-if="hasPrevPage()" @click="prevPage()">
          &lt;
        </button>
        <button class="btn btn-sm btn-light fw-bold " v-if="hasNextPage()" @click="nextPage()">
          >
        </button>
      </div>
    </div>
    <div class="table-responsive mb-2">
      <div class="container px-0 px-md-2 border-md rounded-md" v-if="entity.page !== null && entity.page.to > 0">
          <div class="border-bottom py-2 d-none d-md-block">
              <div class="row fw-bold">
                <div class="col-md-auto">
                    ID
                </div>
                <div v-for="(fillable,i) in entity.fillables" :key="i"
                  :class="fillable.classes">
                  {{ fillable.label }}
                </div>
                <div class="col-md-auto">
                    Actions
                </div>
              </div>
          </div>
          <div class="py-1" 
            v-for="(data,i) in entity.page.data" 
            :key="i" 
            :class="{'border-bottom': i < getPagination() - 1}">
              <div class="row">
                  <div class="col-12 col-md-auto py-1 font-monospace">
                    <span class="d-block d-md-none fw-bold">ID: </span> {{getId(data.id)}}
                  </div>
                  <div v-for="(fillable,j) in entity.fillables" :key="j"
                    :class="fillable.classes+' '+fillable.dataClasses">
                    <a v-if="data.hasOwnProperty('link')"
                    :href="data.link">
                      <span class="d-block d-md-none fw-bold">{{ fillable.label }}: </span> {{ data[fillable.name] }}
                    </a>
                    <span v-else>
                      <span class="d-block d-md-none fw-bold">{{ fillable.label }}: </span> {{ data[fillable.name] }}
                    </span>
                  </div>
                  <div class="col-12 col-md-auto">
                    <span class="d-block d-md-none fw-bold">Actions: </span> 
                      <a class="btn btn-light btn-sm" @click="onEdit(data)">Edit</a>
                      <a class="btn btn-light btn-sm mx-2 text-danger" @click="onDelete(data)">Delete</a>
                  </div>    
              </div>
          </div>
      </div>
      <p v-else-if="loading">Loading data...</p>
      <h6 v-else><i>No entries</i></h6>
    </div>
  </div>
</template>

<script>
export default{
  name: 'Table',
  props: {
    title: String,
    entity: {
      required: true,
      type: Object,
    },
    loading: Boolean,
  },
  methods: {
    getId(id) {
      return id.toString().padStart(this.entity.pad, '0');
    },
    nextPage() {
      this.$emit('on-next', this.entity.page.next_page_url);
    },
    prevPage() {
      this.$emit('on-prev', this.entity.page.prev_page_url);
    },
    hasPrevPage() {
      return this.entity.page.prev_page_url !== null;
    },
    hasNextPage() {
      return this.entity.page.next_page_url !== null;
    },
    onEdit(data) {
      this.$emit('on-edit', data);
    },
    onDelete(data) {
      if( confirm(`You are deleting an item from ${this.entity.name}. Are you sure?`) ) {
        this.$emit('on-delete', data);
      }
    },
    getPagination() {
      return parseInt(import.meta.env.VITE_PAGINATION_SIZE);
    }
  }
}
</script>