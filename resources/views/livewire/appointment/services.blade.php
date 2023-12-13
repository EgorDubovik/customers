<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fe fe-list"></i> Infromation </h3>
    </div>
    <div class="card-body">
        <div class="appointment-time-info d-flex">
            <div>
                <b>Appointment time:</b> <span
                class="text-muted fs-14 mx-2 fw-normal">
                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('M d Y') }}</span>
                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('H:i') }} -
                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('H:i') }}
            </div>
            <div class="ms-auto d-flex">
                <a href="{{ route('appointment.edit', ['appointment' => $appointment]) }}" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit appointment time" data-bs-original-title="Edit appointment time"><span class="fe fe-edit"></span></a>
            </div>
        </div>
        
        <div class="appointment-services">
            <div class="appointment-services-title">
                Services:
            </div>
            <div class="line-services-added row" style="padding-left: 20px">
                <ul class="list-group list-group-flush services-list" id="services-list">
                @foreach ($appointment->services as $service)
                    <li class="list-group-item d-flex" 
                        data-price="{{ $service->price }}" 
                        data-title="{{ $service->title }}"
                        data-description="{{ $service->description }}"
                        data-id="{{ $service->id }}"
                    >
                        <div class="service-item-loading remove">
                            <div class="spinner-border text-secondary me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div>   
                            <i class="task-icon bg-secondary"></i>
                            <h6 class="fw-semibold">{{ $service->title }}<span class="text-muted fs-11 mx-2 fw-normal"> ${{ $service->price }}</span>
                            </h6>
                            <p class="text-muted fs-12">{!! nl2br($service->description) !!}</p>
                        </div>
                        <div class="ms-auto d-flex">
                            <a href="#" wire:click.prevent='edit({{ $service->id }})' class="text-muted me-2" data-bs-toggle="modal" data-bs-target="#add_new_service_model"><span class="fe fe-edit"></span></a>
                            <a href="#" wire:click.prevent="delete({{ $service->id }})" class="text-muted"><span class="fe fe-trash-2"></span></a>
                        </div>
                    </li>
                    
                @endforeach
                </ul>
                <div class="text-center">
                    <a href="#" wire:click='create()' data-bs-toggle="modal" data-bs-target="#add_new_service_model" class="text-secondary">+ add new service</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div>Payment history:
            @if ($remainingBalance <= 0)
                <span class="tag tag-outline-success" id="total_on_span" style="margin-left: 30px;">Paid full </span>    
            @else
                <span class="tag tag-outline-danger" id="total_on_span" style="margin-left: 30px;">Total due: ${{ $remainingBalance }}</span>
            @endif
        </div>
        <table style="width: 50%;" align="right" class="table-payment-history">
            @foreach ($appointment->payments as $payment)
            <tr>
                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('M d Y') }}</td>
                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('g:i A') }}</td>
                <td>{{ App\Models\Payment::getPaymentTypeText($payment->payment_type) }}</td>
                <td>${{ number_format($payment->amount,2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @include('livewire.layout.service-add-modal')
</div>