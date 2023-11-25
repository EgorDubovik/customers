<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fe fe-user"></i> Technical</h3>
            <div class="card-options">
                <a href="#" onclick="$('#add_new_tech_model').modal('show');return false;">
                    <i class="fe fe-plus text-success"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            @foreach ($appointment->techs as $tech)
                <div class="media m-0 mt-0">
                    <div class="avatar_cirle" style="background: {{ $tech->color }}"></div>
                    <div class="media-body">
                        <div class="row">
                            <div class="col-10">
                                <a href="#"
                                    class="text-default fw-semibold">{{ $tech->name }}</a>
                                <p class="text-muted ">
                                    {{ $tech->phone }}
                                </p>
                            </div>
                            <div class="col-2">
                                {{-- <a href="{{ route('appointment.remove.tech', ['appointment' => $appointment, 'user' => $tech]) }}"
                                    class="text-muted"><i class="fe fe-trash-2"></i></a> --}}
                                <a href="#" wire:click.prevent="delete({{ $appointment->id }},{{ $tech->id }})"
                                    class="text-muted"><i class="fe fe-trash-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal --}}

    <div wire:ignore.self class="modal fade" id="add_new_tech_model" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new Tech</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @foreach ($techs as $tech)
                        {{-- <div class="row add-new-tech-line-model" onclick="add_new_tech(this)" data-techid="{{ $tech->id }}" data-techname="{{ $tech->name }}" data-techphone="{{ $tech->phone }}"> --}}
                        <div class="row add-new-tech-line-model" wire:click="add({{ $tech->id }})" data-toggle="modal" data-target="#add_new_tech_model" >
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
    
</div>


