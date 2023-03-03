<template>
  <div>
    <h1 class="fs-2 text-capitalize">{{ entity.label }}</h1>
    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
      <button class="btn btn-sm btn-light" @click="$emit('new-entity', entity.route)">
        Add New
      </button>
      <div class="d-flex flex-row align-items-center gap-2" aria-label="Page navigation"
        v-if="entity.page !== null && entity.page.to > 0">
        <span>{{ entity.page.from }} - {{ entity.page.to }} &#47; {{ entity.page.total }}</span>
        <ul class="pagination my-0">
          <li class="page-item" :class="{'disabled': !hasPrevPage()}">
            <a class="page-link fw-bold" :class="{'text-dark': hasPrevPage()}" @click="$emit('on-prev')">&lt;</a>
          </li>
          <li class="page-item" :class="{'disabled': !hasNextPage()}">
            <a class="page-link fw-bold" :class="{'text-dark': hasNextPage()}" @click="$emit('on-next')">></a>
          </li>
        </ul>
      </div>
    </div>
    <div class="table-responsive mb-2">
      <table class="table table-hover text-nowrap table-sm align-middle" v-if="entity.page !== null && entity.page.to > 0">
        <thead>
          <tr>
            <th scope="col" v-if="entity.withIndex">
              &#x2116;
            </th>
            <th scope="col" v-else>
                ID
            </th>
            <th scope="col" v-for="(fillable,i) in entity.fillables" :key="i">
              {{ fillable.label }}
            </th>
            <th scope="col">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(data,i) in entity.page.data" 
          :key="i">
            <td class="fw-bold font-monospace">
              {{ getId(data, i) }}
            </td>
            <td v-for="(fillable,j) in entity.fillables" :key="j">
              <span v-if="fillable.hasOwnProperty('raw') && fillable.data === 'raw'" :class="callFunction('class', fillable, data)"
                v-html="fillable.raw(data, i)">  
              </span>
              <template v-else>
                {{ data[fillable.name] }}
              </template>
            </td>
            <td>
              <a class="btn btn-light btn-sm" @click="$emit('on-edit', data)">Edit</a>
              <a class="btn btn-light btn-sm mx-2 text-danger" @click="onDelete(data)">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
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
    getId(data, i) {
      const id = this.entity.withIndex ? (i + this.entity.page.from) : data.id;
      return id.toString().padStart(this.entity.pad, '0');
    },
    hasPrevPage() {
      return this.entity.page.prev_page_url !== null;
    },
    hasNextPage() {
      return this.entity.page.next_page_url !== null;
    },
    onDelete(data) {
      if( confirm(`You are deleting an item from ${this.entity.route}. Are you sure?`) ) {
        this.$emit('on-delete', data);
      }
    },
    getPagination() {
      return parseInt(import.meta.env.VITE_PAGINATION_SIZE);
    },
    callFunction(fn, object, input) {
      if (typeof object[fn] === 'function') {
        return object[fn](input);
      }
      return null;
    }
  }
}
</script>