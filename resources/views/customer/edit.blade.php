@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Edit customer</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                <div class="card col-md-6 m-auto">
                    <form method="post" action="{{route('customer.update', ['customer' => $customer])}}">
                        @csrf
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="category-create-address">Customer </div>
                            <div style="margin-left: 20px;">
                                <div class="row mb-4">
                                    <label class="col-md-2 control-label">Full Name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control customer_name" placeholder="Customer Full Name" name="customer_name" value="{{$customer->name}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-2 control-label">Phone</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control customer_phone" placeholder="Phone number" name="customer_phone" value="{{$customer->phone}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control customer_phone" placeholder="Email address" name="email" value="{{$customer->email}}">
                                    </div>
                                </div>
                            </div>

                            <div class="category-create-address">Address  <a href="#" class="parse_btn" onclick="$('.parse_address').show(); return false;">parse</a></div>

                            <textarea name="parse_address" style="display: none" class="parse_address form-control mb-2" onblur="parse_my_address(this)"></textarea>
                            <div style="margin-left: 20px;">
                                <div class="row mb-4">
                                    <label class="col-sm-2 control-label" for="textinput">Line 1</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="House number, street" class="form-control line1" name="line1" value="{{$customer->address->line1}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-sm-2 control-label" for="textinput">Line 2</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="apt. number" class="form-control line2" name="line2" value="{{$customer->address->line2}}">
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="row mb-4">
                                    <label class="col-sm-2 control-label" for="textinput">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="City" class="form-control city" name="city" value="{{$customer->address->city}}">
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="row mb-4">
                                    <label class="col-sm-2 control-label" for="textinput">State</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="State" class="form-control state" name="state" value="{{$customer->address->state}}">
                                    </div>

                                    <label class="col-sm-2 control-label" for="textinput">Zip</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Post Code" class="form-control zip" name="zip" value="{{$customer->address->zip}}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" type="submit">Update</button>
                            <a href="{{route('customer.show',['customer' => $customer])}}" class="btn btn-warning">Cancel</a>
                        </div>
                    </form>
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

        }
    </script>
@endsection
