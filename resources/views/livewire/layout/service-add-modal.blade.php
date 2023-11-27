<div wire:ignore.self  class="modal fade" id="add_new_service_model" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">{{ $formTitle }}</h5>
               <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
           </div>
           <div class="modal-body">
               <div class="row row-space">
                   <div class="col-md-7">
                       <div class="input-group custom">
                           <div class="typeahead__container">
                               <div class="typeahead__field">
                                   <div class="typeahead__query">
                                       <input wire:model='title' class="custom-input js-typeahead" type="text" placeholder="TITLE" id="title" name="title">
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="col-md-5">
                       <div class="input-group custom">
                           <input wire:model='price' class="custom-input" type="number" placeholder="PRICE" id="price" name="price">
                       </div>
                   </div>
               </div>
               <div class="input-group custom">
                   <textarea wire:model='description' class="custom-input" placeholder="DESCRIPTION" id="description" name="description"></textarea>
               </div>
           </div>
           <div class="modal-footer">
               @if($mode == "create")
                   <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                   <button class="btn btn-primary" wire:click='store()'>Add new service</button>
               @elseif ($mode == 'edit')
                   <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                   <button class="btn btn-primary" wire:click='update()'>Save</button>
               @endif
           </div>
       </div>
   </div>
</div>
<script>
   window.addEventListener('close-modal', event => {
       $('#add_new_service_model').modal('hide');
   })
</script>