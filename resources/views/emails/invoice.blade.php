<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<style>
body{
    margin: 0;
    padding: 0;
    background: #e4e4e4;    
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    color: #545454;
}
.wraper{
    width: 100%;
    background: #e4e4e4;
    padding: 20px;
}
address{
    margin-bottom: 1rem;
    font-style: normal;
    line-height: inherit;
}
.conteiner{
    width: 700px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    border: inherit !important;
    background-clip: border-box;
    border-radius: 7px;
}
.card {
    position: relative;
    margin-bottom: 1.5rem;
    width: 100%;
}
.card-body {
    -ms-flex: 1 1 auto;
    width: 100%;
    flex: 1 1 auto;
    padding: 25px;
    margin: 0;
    position: relative;
}
.text-end{
    text-align: right;
}
.row{
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -0.75rem;
    margin-left: -0.75rem;
    width: 100%;
    Flex-direction:row;
}
.col-6{
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
    width: 50%;
}
.pt-5{
    padding-top: 1.5rem !important;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    margin-bottom: 0.66em;
    font-family: inherit;
    font-weight: 400;
    line-height: 1.1;
    color: inherit;
}
h3, .h3 {
    font-size: 1.5rem;
}
.fw-semibold {
    font-weight: 500 !important;
}
.fs-18 {
    font-size: 18px !important;
}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table{
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}
.table th{
    background: #f6f6fb;
    padding: 10px;
}
.table tr td{
    border: 1px solid #e9edf4;
    padding: 10px;
}
.company_phone{
    font-weight: bold;
    font-size: 16px;
}
.fw-bold {
    font-weight: 700 !important;
}
.text-uppercase {
    text-transform: uppercase !important;
}
.top-invoice{
    padding: 43px 70px;
    font-size: 16px;

}
.email{
    text-decoration: none;
    color: #545454;
}
</style>
<body>
<div class="wraper">
    <div class="conteiner">
        <div class="top-invoice">
            Dear <b>{{ $invoice->customer_name}}</b>,<br><br>
            Thanks for choosing us!  We're glad to help get your appliances back in tip-top shape.
            @if($invoice->company->companySettings && $invoice->company->companySettings->referral_enable)
                <p style="margin-top: 20px;">Your referal link is: {{ route('referral',['code'=>$referralCode]) }}</p>
                <p style="margin-top: 20px;">You can share this link with your friends to get discount on your next appointment. <a href="{{ route('referral.stat',['code'=>$referralCode]) }}">Read more</a> </p>
            @endif
        </div>
        <div class="card">
            <div class="card-body">
                @include('invoice.layout.head')
                <div class="table-responsive push">
                    @include('invoice.layout.services-table',['services' => $invoice->appointment->services])
                </div>
                <p style="text-align: center;margin-top:50px;">Payments history:</p>
                @include('invoice.layout.payment-table',['payments' => $invoice->appointment->payments])
            </div>
        </div>
    </div>
</div>
</body>
</head>