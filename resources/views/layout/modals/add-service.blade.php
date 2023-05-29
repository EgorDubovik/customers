<div class="modal fade" id="add_new_service_model" aria-hidden="true">
    @if (isset($place))
        @if($place == 'show')
            <form action="{{ route('appointment.add.serivce', ['appointment'=>$appointment]) }}" method="post">
                @csrf
        @endif
    @endif
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new service</h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row row-space">
                    <div class="col-md-7">
                        <div class="input-group custom">
                            <div class="typeahead__container">
                                <div class="typeahead__field">
                                    <div class="typeahead__query">
                                        <input class="custom-input js-typeahead" type="text" placeholder="TITLE" id="title" name="title">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group custom">
                            <input class="custom-input" type="number" placeholder="PRICE" id="price" name="price">
                        </div>
                    </div>
                </div>
                <div class="input-group custom">
                    <textarea class="custom-input" placeholder="DESCRIPTION" id="description" name="description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="add_new_service();$('#add_new_service_model').modal('hide');">Add new service</button>
            </div>
        </div>
    </div>
    @if (isset($place))
        @if($place == 'show')
            </form>
        @endif
    @endif

</div>