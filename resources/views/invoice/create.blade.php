@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.css')}}" rel="stylesheet" />
@endsection

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new invoice</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <form method="post" action="{{ route('invoice.store') }}">
            @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="category-create-address" style="border: 0px">Customer </div>
                            @if (isset($customer))
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <div class="customer-view-info" style="display: block">
                                    <div class="customer-icon"><i class="fe fe-bookmark"></i></div>
                                    <p class="fs-18 fw-semibold mb-0"><span id="customer-card-name">{{ $customer->name }}</span></p>
                                    <span class="text-muted"  id="customer-card-email" style="font-weight: normal">{{ $customer->email }}</span>
                                    <div class="hr"></div>
                                    <input type="hidden" name="customer_address" id="input-customer-address"  value="{{ $customer->address->full }}"> 
                                    <address style="margin-top: 10px;" id="customer-card-address">
                                        {{ $customer->address->full }}
                                    </address>
                                    <div class="action"><a href=# onclick="editCustomerCard(); return false;" class="text-warning"> <i class="side-menu__icon fe fe-edit"></i></a></div>
                                </div>
                            @else
                                <div class="customer-view-info" style="display: none">
                                    <div class="customer-icon"><i class="fe fe-bookmark"></i></div>
                                    <p class="fs-18 fw-semibold mb-0"><span id="customer-card-name">Customer Name</span></p>
                                    <span class="text-muted"  id="customer-card-email" style="font-weight: normal">yourdomain@example.com</span>
                                    <div class="hr"></div>
                                    <input type="hidden" name="customer_address" id="input-customer-address"> 
                                    <address style="margin-top: 10px;" id="customer-card-address">
                                        Street Address<br>
                                        City, State Postal Code
                                    </address>
                                    <div class="action"><a href=# onclick="editCustomerCard(); return false;" class="text-warning"> <i class="side-menu__icon fe fe-edit"></i></a></div>
                                </div>
                            @endif
                            <div class="customer-input-group" {!! (isset($customer)) ? "style='display:none'" : "" !!}>
                                <div style="margin-left: 20px;">
                                    <div class="row mb-4">
                                        <label class="col-md-2 control-label">Full Name</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control customer_name" onblur = "update_invoice_view()" id="customer_name" placeholder="Customer Full Name" name="customer_name" value="{!! (isset($customer)) ? $customer->name : "" !!}">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-md-2 control-label">Email</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control customer_phone" placeholder="Email address" id="email" onblur = "update_invoice_view()" name="email" value="{!! (isset($customer)) ? $customer->email : "" !!}">
                                        </div>
                                    </div>
                                </div>

                                <div class="category-create-address">Address  <a href="#" class="parse_btn" onclick="$('.parse_address').toggle(); return false;">parse</a></div>

                                <textarea name="parse_address" style="display: none" class="parse_address form-control mb-2" onblur="parse_my_address(this)"></textarea>
                                <div style="margin-left: 20px;">
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">Line 1</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="House number, street" class="form-control line1" name="line1" id="address-line1" onblur = "update_invoice_view()" value="{!! (isset($customer)) ? $customer->address->line1 : "" !!}">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">Line 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="apt. number" class="form-control line2" name="line2" id="address-line2" onblur = "update_invoice_view()" value="{!! (isset($customer)) ? $customer->address->line2 : "" !!}">
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">City</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="City" class="form-control city" name="city" id="address-city" onblur = "update_invoice_view()" value="{!! (isset($customer)) ? $customer->address->city : "" !!}">
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">State</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="State" class="form-control state" name="state" id="address-state" onblur = "update_invoice_view()" value="{!! (isset($customer)) ? $customer->address->state : "" !!}">
                                        </div>

                                        <label class="col-sm-2 control-label" for="textinput">Zip</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Post Code" class="form-control zip" name="zip" id="address-zip" onblur = "update_invoice_view()" value="{!! (isset($customer)) ? $customer->address->zip : "" !!}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end customer-card-footer" {!! (isset($customer)) ? "style='display:none'" : "" !!}>
                        <button class="btn btn-primary btn-sm" onclick="save_customer_information();return false;" >Save</button>
                    </div>
                    
                </div>

                <div class="card">
                    <div class="card-body">
                        
                        <h5 class="card-title">Services</h5>
                        <div id="line-services-added" class="row">
                            @if(isset($appointment))
                                @foreach ($appointment->services as $service)
                                    <div class="col-sm-12 col-md-6 mb-2">
                                        <input type="hidden" name="service-prices[]" class = "service-prices" value="{{ $service->price }}">
                                        <input type="hidden" name="service-title[]" class = "service-title" value="{{ $service->title }}">
                                        <input type="hidden" name="service-description[]" class = "service-description"  value="{{ $service->description }}">
                                        <div class="cont-service-block">
                                            <div class="row mb-2">
                                                <div class="col-9">
                                                    <b>Dryer</b>
                                                </div>
                                                <div class="col-3"><b>$60</b></div>
                                            </div>
                                            <div class="hr"></div>
                                            <div class="row mt-2">
                                                <div class="col-9 iems-descrition">Diagnose your Washer</div>
                                                <div class="col-3">
                                                    <p class="text-end">
                                                        <a href="#" onclick="removeServiceItem(this); return false;" class=" text-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @include('service.add-form')
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm" onclick="addService();return false;">Add</button>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card col-12">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="../assets/images/brand/LogoForT-Shutsh.png" style="width: 170px;" class="header-brand-img logo-3" alt="Sash logo">
                                <div>
                                    <address class="pt-3">
                                        2901 Ridgeview dr, APT 426, Plano TX 75025<br>
                                        edservicetx@gmail.com
                                    </address>
                                </div>
                            </div>
                            <div class="col-lg-6 text-end border-bottom border-lg-0">
                                <h3>#INV-000</h3>
                                <h5>Date Issued: {{ date('M-d-Y') }}</h5>
                            </div>
                        </div>
                        <div class="row pt-5">
                            <div class="col-lg-6">
                                <p class="h3">Invoice To:</p>
                                @if (isset($customer))
                                    <p class="fs-18 fw-semibold mb-0">{{ $customer->name }}</p>
                                    <address>
                                        <span id="invoice-address">{{ $customer->address->line1 }}, {{ $customer->address->line2 }}<br>
                                        {{ $customer->address->city }} {{ $customer->address->state }} {{ $customer->address->zip }}</span><br>
                                        <span id="invoice-email">{{ $customer->email }}</span>
                                    </address>
                                @else
                                    <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name">Customer Name</span></p>
                                    <address>
                                        <span id="invoice-address">Street Address<br>
                                        City, State Postal Code</span><br>
                                        <span id="invoice-email">yourdomain@example.com</span>
                                    </address>
                                @endif
                                
                            </div>
                            <div class="col-lg-6 text-end">
                                <p class="h4 fw-semibold">Payment Details:</p>
                                <p class="mb-1">Total Due: $<span id='total-small-invoice'>00,00</span></p>
                                <p class="mb-1">Type of payment: Null</p>
                            </div>
                        </div>
                        <div class="table-responsive push">
                            <table class="table table-bordered table-hover mb-0 text-nowrap">
                                <tbody>
                                    <tr id="tr-header-invoice-table">
                                        <th class="text-center"></th>
                                        <th>Item</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                    @if(isset($appointment))
                                        @foreach ($appointment->services as $service)
                                            <tr class="table-invoice-line">
                                                <td class="text-center">1</td>
                                                <td>
                                                    <p class="font-w600 mb-1">{{ $service->title }}</p>
                                                    <div class="text-muted">
                                                        <div class="text-muted">{{ $service->description }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-end">${{  $service->price }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="2" class="fw-bold text-uppercase text-end">Total</td>
                                        <td class="fw-bold text-end h4"><span id="total-invoice">$ {{ $appointment->services->sum('price') }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <button type="submit" class="btn btn-secondary mb-1" ><i class="si si-paper-plane"></i> Send Invoice</button>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        </form>
    </div>
    
@stop
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>
    <script src="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.js')}}"></script>
    <script>
        $('#service-price').mask("##0.00", {reverse: true});
    </script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/parse-address.min.js')}}"></script>
    <script>
        function parse_my_address(d){
            var address  = $(d).val();
            var parsed = parseAddress.parseLocation(address);
            console.log(parsed.hasOwnProperty('prefix'));
            $('.line1').val(
                ((parsed.hasOwnProperty('number')) ? parsed.number+ " " : "")  +
                ((parsed.hasOwnProperty('prefix')) ? parsed.prefix+ " " : "")  +
                ((parsed.hasOwnProperty('street')) ? parsed.street+ " " : "")  +
                ((parsed.hasOwnProperty('type')) ? parsed.type+ " " : "")
            );
            $('.line2').val(
                ((parsed.hasOwnProperty('sec_unit_type')) ? parsed.sec_unit_type+ " " : "")  +
                ((parsed.hasOwnProperty('sec_unit_num')) ? parsed.sec_unit_num : "")
            );
            $('.city').val(((parsed.hasOwnProperty('city')) ? parsed.city+ " " : ""));
            $('.state').val(((parsed.hasOwnProperty('state')) ? parsed.state+ " " : ""));
            $('.zip').val(((parsed.hasOwnProperty('zip')) ? parsed.zip+ " " : ""));
            $('.parse_address').toggle();

        }

        function update_invoice_view(){
            var name = $("#customer_name").val();
            var email = $("#email").val();
            var address = getAddress();
            $('#invoice-address').html(address);
            $('#input-customer-address').val(address);
            if(name != ""){
                $('#invoice-customer-name').html(name);
            }
            if(email != ""){
                $("#invoice-email").html(email);
            }
        }

        function save_customer_information(){
            var name = $("#customer_name").val();
            var email = $("#email").val();
            var address = getAddress();
            $('#customer-card-name').html(name);
            $('#customer-card-email').html(email);
            $('#customer-card-address').html(address);
            update_invoice_view();
            $('.customer-view-info').show();
            $('.customer-input-group').hide();
            $('.customer-card-footer').hide();
        }

        function editCustomerCard(){
            $('.customer-view-info').hide();
            $('.customer-input-group').show();
            $('.customer-card-footer').show();
        }

        function getAddress(){
            var address_line1 = $("#address-line1").val();
            var address_line2 = $("#address-line2").val();
            var address_city = $("#address-city").val();
            var address_state = $("#address-state").val();
            var address_zip = $("#address-zip").val();
            if(name != ""){
                $('#invoice-customer-name').html(name);
            }
            var address =   ((address_line1 != "") ? address_line1 : "Street Address") +
                            ((address_line2 != "") ? ", "+address_line2 : "") +
                            ((address_city != "") ?  "<br> "+address_city : "City" + ",")+
                            ((address_state != "") ? " "+address_state : "State") +
                            ((address_zip != "") ? " "+address_zip : "Postal code");

            return address;

        }

        function addService(){
            var title = $('#title').val();
            var price = $('#price').val();
            
            var description = $('#description').val().replace(/\n/g, "<br />");
            
            $('#line-services-added').append(
                            '<div class="col-sm-12 col-md-6 mb-2 added-service-line">'+
                                '<input type="hidden" name="service-prices[]" class = "service-prices" value="'+price+'">'+
                                '<input type="hidden" name="service-title[]" class = "service-title" value="'+title+'">'+
                                '<input type="hidden" name="service-description[]" class = "service-description"  value="'+description+'">'+
                                '<div class="cont-service-block">'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-9"><b>'+title+'</b></div>'+
                                        '<div class="col-3"><b>$'+price+'</b></div>'+
                                    '</div>'+
                                    '<div class="hr"></div>'+
                                    '<div class="row mt-2">'+
                                        '<div class="col-9 iems-descrition">'+description+'</div>'+
                                        '<div class="col-3">'+
                                            '<p class="text-end">'+
                                                '<a href="#"onclick="removeServiceItem(this); return false;" class=" text-danger"><i class="fa fa-trash"></i></a>'+
                                            '</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>');
            
            $('#title').val('');
            $('#price').val(''); 
            $('#description').val('');

            fillInvoiceTable();

            countTotal();
        }

        function fillInvoiceTable(){
            $('.table-invoice-line').remove();
            $('.added-service-line').each(function(){
                var parent = $(this);
                var price = parent.find('.service-prices').val();
                var title = parent.find('.service-title').val();
                var description = parent.find('.service-description').val();
                $('#tr-header-invoice-table').after(
                                    '<tr class="table-invoice-line">'+
                                        '<td class="text-center">1</td>'+
                                        '<td>'+
                                            '<p class="font-w600 mb-1">'+title+'</p>'+
                                            '<div class="text-muted">'+
                                                '<div class="text-muted">'+description+'</div>'+
                                            '</div>'+
                                        '</td>'+
                                        '<td class="text-end">$'+price+'</td>'+
                                    '</tr>'
                )
            })
        }

        function countTotal(){
            var total = 0;
            $('.service-prices').each(function(){
                console.log(total);
                total += parseFloat($(this).val());
            });
            $('#total-invoice').html('$'+total);
            $('#total-small-invoice').html(total);
        }

        function removeServiceItem(d){
            $(d).parent().parent().parent().parent().parent().remove();
            fillInvoiceTable();
        }

        
        
    </script>
    @include('service.typehead-script')
@endsection