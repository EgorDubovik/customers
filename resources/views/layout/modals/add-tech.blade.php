<div class="modal fade" id="add_new_tech_model" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new Tech</h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @foreach ($techs as $tech)
                    <div class="row add-new-tech-line-model" onclick="add_new_tech(this)" data-techid="{{ $tech->id }}" data-techname="{{ $tech->name }}" data-techphone="{{ $tech->phone }}">
                        <div class="media m-0 mt-0">
                            <img class="avatar brround avatar-md me-3" alt="avatra-img" src="../../assets/images/users/18.jpg">
                            <div class="media-body">
                                <a href="#" class="text-default fw-semibold">{{ $tech->name }}</a>
                                <p class="text-muted ">
                                    {{ $tech->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function add_new_tech(d){
        let is_id = false;
        let tech_id = $(d).attr('data-techid');
        let tech_name = $(d).attr('data-techname');
        let tech_phone = $(d).attr('data-techphone');
        $('.tech-line').each(function(){
            if($(this).find('input').val()==tech_id){
                is_id = true;
                return;
            }
        })
        if(!is_id){
            $('#techs-cont').append('<div class="tech-line">'+
                                    '<input type="hidden" name="tech_ids[]" value="'+tech_id+'" class="tech-ids">'+
                                        '<div class="media m-0 mt-0">'+
                                            '<img class="avatar brround avatar-md me-3" alt="avatra-img" src="../../assets/images/users/18.jpg">'+
                                                '<div class="media-body">'+
                                                    '<a href="#" class="text-default fw-semibold">'+tech_name+'</a>'+
                                                    '<p class="text-muted ">'+
                                                        tech_phone+
                                                    '</p>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>');
        }
        $('#add_new_tech_model').modal('hide');
        
    }
</script>