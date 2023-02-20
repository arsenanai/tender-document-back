<template>
<div class="modal fade" :id="id" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <slot></slot>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-sm btn-light">Save changes</button>
      </div>
    </div>
  </div>
</div>    
</template>

<script>
export default {
    name: 'Modal',
    props: ['id']
}
</script>