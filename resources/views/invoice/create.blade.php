@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new invoice</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-6">
                <div class="card col-12">
                    <form method="post">
                        @csrf
                        <div class="card-body">
                            <div class="category-create-address">Customer </div>
                            <div class="customer-view-info" style="display: none">
                                <p class="fs-18 fw-semibold mb-0"><span id="customer-card-name">Customer Name</span> <span class="text-muted"  id="customer-card-email" style="font-weight: normal">yourdomain@example.com</span> <span style="margin-left:20px"> <a href=# onclick="editCustomerCard(); return false;" style="color:brown;font-weight:normal;font-size:14px;"> <i class="side-menu__icon fe fe-edit"></i> Edit</a></span></p>
                                <address style="margin-left: 20px;" id="customer-card-address">
                                    Street Address<br>
                                    City, State Postal Code
                                </address>
                            </div>
                            <div class="customer-input-group">
                                <div style="margin-left: 20px;">
                                    <div class="row mb-4">
                                        <label class="col-md-2 control-label">Full Name</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control customer_name" onblur = "update_invoice_view()" id="customer_name" placeholder="Customer Full Name" name="customer_name" value="">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-md-2 control-label">Email</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control customer_phone" placeholder="Email address" id="email" onblur = "update_invoice_view()" name="email" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="category-create-address">Address  <a href="#" class="parse_btn" onclick="$('.parse_address').toggle(); return false;">parse</a></div>

                                <textarea name="parse_address" style="display: none" class="parse_address form-control mb-2" onblur="parse_my_address(this)"></textarea>
                                <div style="margin-left: 20px;">
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">Line 1</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="House number, street" class="form-control line1" name="line1" id="address-line1" onblur = "update_invoice_view()">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">Line 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="apt. number" class="form-control line2" name="line2" id="address-line2" onblur = "update_invoice_view()">
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">City</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="City" class="form-control city" name="city" id="address-city" onblur = "update_invoice_view()">
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="row mb-4">
                                        <label class="col-sm-2 control-label" for="textinput">State</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="State" class="form-control state" name="state" id="address-state" onblur = "update_invoice_view()">
                                        </div>

                                        <label class="col-sm-2 control-label" for="textinput">Zip</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Post Code" class="form-control zip" name="zip" id="address-zip" onblur = "update_invoice_view()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end customer-card-footer">
                            <button class="btn btn-primary btn-sm" onclick="save_customer_information();return false;" >Save</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Services</h5>
                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-md-2">Title</div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="service-title" placeholder="Title">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-md-2">Price</div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="service-price" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="colFormLabel" class="col-md-2 col-form-label">Description</label>
                            <div class="col-md-10">
                                <textarea class="form-control" placeholder="Description" id="service-description" style="height: 100px"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary btn-sm" onclick="addService();return false;">Add</button>
                        </div>
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
                                        2901 Ridgeview dr, Plano TX 75025<br>
                                        edservicetx@gmail.com
                                    </address>
                                </div>
                            </div>
                            <div class="col-lg-6 text-end border-bottom border-lg-0">
                                <h3>#INV-000</h3>
                                <h5>Date Issued: 00-00-0000</h5>
                            </div>
                        </div>
                        <div class="row pt-5">
                            <div class="col-lg-6">
                                <p class="h3">Invoice To:</p>
                                <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name">Customer Name</span></p>
                                <address>
                                        <span id="invoice-address">Street Address<br>
                                        City, State Postal Code</span><br>
                                        <span id="invoice-email">yourdomain@example.com</span>
                                    </address>
                            </div>
                            <div class="col-lg-6 text-end">
                                <p class="h4 fw-semibold">Payment Details:</p>
                                <p class="mb-1">Total Due: $00,00</p>
                                <p class="mb-1">Type of payment: Null</p>
                            </div>
                        </div>
                        <div class="table-responsive push">
                            <table class="table table-bordered table-hover mb-0 text-nowrap">
                                <tbody>
                                    <tr class=" ">
                                        <th class="text-center"></th>
                                        <th>Item</th>
                                        <th class="text-end">Total</th>
                                    </tr>
{{--                                     
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            <p class="font-w600 mb-1">Logo Design</p>
                                            <div class="text-muted">
                                                <div class="text-muted">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium</div>
                                            </div>
                                        </td>
                                        <td class="text-end">$1,308</td>
                                    </tr> --}}
                                    
                                    <tr>
                                        <td colspan="2" class="fw-bold text-uppercase text-end">Total</td>
                                        <td class="fw-bold text-end h4">$00,00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-secondary mb-1" onclick="javascript:window.print();"><i class="si si-paper-plane"></i> Send Invoice</button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
@stop
@section('scripts')
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
            
        }
    </script>
@endsection