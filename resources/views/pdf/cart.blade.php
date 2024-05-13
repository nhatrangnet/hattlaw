<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>NhaTrangNet.net</title>
<style>
    @font-face {
      font-family: DejaVu Sans;
      src: url(SourceSansPro-Regular.ttf);
    }

    .clearfix:after {
      content: "";
      display: table;
      clear: both;
    }

    a {
      color: #0087C3;
      text-decoration: none;
    }

    body {
      position: relative;
      width: 97%;
      margin: 0 auto; 
      color: #555555;
      background: #FFFFFF; 
      font-family: DejaVu Sans;
      font-size: 11px;
    }

    header {
      padding: 10px 0;
      margin-bottom: 20px;
      border-bottom: 1px solid #AAAAAA;
    }

    #logo {
      float: right;
      margin-top: 8px;
      text-align: right;
    }

    #logo img {
      height: 70px;
    }

    #company {
      float: left;
      text-align: left;
    }
    #details {
    }
    #client {
      padding-left: 6px;
      border-left: 6px solid #0087C3;
      float: left;
      text-align:left;
    }
    #client .to {
    	text-transform: uppercase;
      color: #777777;
    }

    h2.name {
      font-size: 1.4em;
      font-weight: normal;
      margin: 0;
    }

    #invoice {
      float: right;
      text-align: right;
    }
    #invoice h1 {
      color: #0087C3;
      font-size: 2.4em;
      line-height: 1em;
      font-weight: normal;
      margin: 0  0 10px 0;
    }
    #invoice .date {
      font-size: 1.1em;
      color: #777777;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-spacing: 0;
    }

    table th,
    table td {
      padding: 10px;
      background: #EEEEEE;
      text-align: center;
      border-bottom: 1px solid #FFFFFF;
    }

    table th {
      white-space: nowrap;        
      font-weight: normal;
    }

    /*table td {
      text-align: right;
    }*/

    table td h3{
      color: #57B223;
      font-size: 1.2em;
      font-weight: normal;
      margin: 0 0 0.2em 0;
    }

    table .no {
      color: #FFFFFF;
      font-size: 1.1em;
      background: #57B223;
    }

    table .desc {
      text-align: left;
    }

    table .unit {
      background: #DDDDDD;
    }

    table .qty {
    }

    table .total {
      background: #57B223;
      color: #FFFFFF;
    }

    table td.unit,
    table td.qty,
    table td.total {
      font-size: 1.2em;
    }

    table tbody tr:last-child td {
      border: none;
    }

    table tfoot td {
      padding: 10px;
      background: #FFFFFF;
      border-bottom: none;
      font-size: 1.2em;
      white-space: nowrap; 
      border-top: 1px solid #AAAAAA; 
    }

    table tfoot tr:first-child td {
      border-top: none; 
    }

    table tfoot tr:last-child td {
      color: #57B223;
      border-top: 1px solid #57B223; 

    }

    table tfoot tr td:first-child {
      border: none;
    }

    #thanks{
      font-size: 1.2em;
      text-align: center;
    }

    #notices{
      padding-left: 6px;
      border-left: 6px solid #0087C3;  
    }

    #notices .notice {
      font-size: 1.2em;
    }

    footer {
      color: #777777;
      width: 97%;
      position: absolute;
      bottom: 0;
      border-top: 1px solid #AAAAAA;
      text-align: center;
    }
</style>
</head>
<body>
<header class="clearfix">
	<div id="company">
	  <h2 class="name">Gas Khánh Bình</h2>
	  <div>38A, 2 Tháng 4 p. Vĩnh Hải, Nha Trang Khánh Hòa</div>
	  <div>0258 3832822</div>
	  {{-- <div><a href="mailto:company@example.com">company@example.com</a></div> --}}
	</div>
	<div id="logo">
		NhaTrangNet.net <br>
		09 3536 7986
	</div>
	
</header>
<main>
<div id="details" class="clearfix">
  <div id="client" style="width:50%">
    <div class="to">{{trans('form.customer')}}:</div>
    <h2 class="name">{{$name}}</h2>
    <div class="address">{{$address}}</div>
    @if(!empty($email))
    	<div class="email"><a href="mailto:john@example.com">{{$email}}</a></div>
    @endif
  </div>
  <div id="invoice">
    {{trans('form.invoice')}} <br>
    {{trans('form.shipping_date')}}: <b>{{$shipping_date}}</b>
  </div>
</div><br>
<table border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="no">#</th>
      <th class="desc">{{trans('form.name')}}</th>
      <th class="unit">{{trans('form.price')}}</th>
      <th class="qty">{{trans('form.quantity')}}</th>
      <th class="total">{{trans('form.subtotal')}}</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($items as $key => $product)
    <tr>
      <td class="no">{{$key+1}}</td>
      <td class="desc">{{$product->name}}</td>
      <td class="unit">{{number_format($product->price)}}{{trans('form.money_symbol')}}</td>
      <td class="qty">{{$product->quantity}}</td>
      <td class="total">{{number_format($product->price*$product->quantity)}}{{trans('form.money_symbol')}}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"></td>
      <td colspan="2">{{trans('form.subtotal')}}</td>
      <td>{{number_format($subtotal)}}{{trans('form.money_symbol')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2">{{trans('form.shipping_charge')}}</td>
      <td>+{{number_format($shipping_charge)}}{{trans('form.money_symbol')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2">{{trans('form.tax')}} ({{$tax}}%)</td>
      <td>+{{number_format(($subtotal*$tax)/100)}}{{trans('form.money_symbol')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2">{{trans('form.discount')}}<i>({{$discount_percent}}%)</i></td>
      <td>-{{number_format(($subtotal*$discount_percent)/100)}}{{trans('form.money_symbol')}}</td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="2">{{trans('form.total')}}</td>
      <td><b>{{number_format($total)}}{{trans('form.money_symbol')}}</b></td>
    </tr>
  </tfoot>
</table>
<p>{{trans('form.note')}}</p>
<textarea name="note" rows="8">{{$note}}</textarea>
<br>
<div id="thanks">{{trans('form.thank_you')}}</div>
</div>
</main>
</body>
</html>