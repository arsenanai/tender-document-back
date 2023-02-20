<template>
  <h1 class="fs-2">{{ title }}</h1>
  <div class="d-flex flex-row align-items-center justify-content-between mb-2">
    <button class="btn btn-sm btn-light">
      Add New
    </button>
    <div class="">
      <button class="btn btn-sm btn-light fw-bold mx-2">
        &lt;
      </button>
      <button class="btn btn-sm btn-light fw-bold ">
        >
      </button>
    </div>
  </div>
  <div class="table-responsive mb-2">
    <div class="container px-0 px-md-2 border-md rounded-md">
        <div class="border-bottom py-2">
            <div class="row fw-bold">
                <div class="col-2">
                    ID
                </div>
                <div class="col-4 col-sm-6 col-lg-8">
                    Name
                </div>
                <div class="col">
                    Actions
                </div>
            </div>
        </div>
        <div class="py-1" v-for="i in 20" :key="i" :class="{'border-bottom': i<20}">
            <div class="row d-flex flex-row align-content-center justify-content-between">
                <div class="col-2 py-1 font-monospace">
                    {{getId(i)}}
                </div>
                <div class="col-4 col-sm-6 col-lg-8 py-1 text-truncate">
                    Verylongname
                </div>
                <div class="col">
                    <a class="btn btn-light btn-sm">Edit</a>
                    <a class="btn btn-light btn-sm mx-2 text-danger">Delete</a>
                </div>    
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default{
  name: 'Table',
  props: {
    title: String,
  },
  methods: {
    getId(id) {
      return (id+'').padStart(2, '0');
    },
  }
}
</script>