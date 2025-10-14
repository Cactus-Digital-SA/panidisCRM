<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offer #{{ $quote->getReferenceCode() ?? ' ' }}</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h3 { font-size: 16px; color: #333; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; border-bottom: 2px solid #ddd;}
        td { font-size:12px; line-height: 1.1; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .no-border { border: none; }
        .offer-info { background: #f8f9fa; padding: 6px 8px; border-radius: 8px; margin-bottom: 10px;}
        .items { border-radius: 8px; margin-bottom: 10px;  }
        .total-row { background: #f8f9fa; font-size: 15px; padding: 15px; font-weight: bold; }
        th.column-header-200,
        td.column-cell-200 {
            width: 100px;
            min-width: 100px;
        }

        .header {
            background: #f8f9fa;
            padding: 6px 8px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .header-left {
            width: 33%;
        }
        .header-center {
            width: 33%;
        }
        .header-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }

        .logo {
            margin-left: -10px;
            max-width: 220px;
            max-height: 60px;
        }
    </style>
</head>
<body>

{{-- Header --}}
<table class="header no-border" style="margin-top: 0px">
    <tr>
        <td class="header-left">
            <img src="{{ public_path('/assets/img/logo/logo_black.png') }}" alt="" class="logo" >
{{--            <img src="{{ public_path('assets/img/logo/logo_black.png') }}" alt="" class="logo" width="180">--}}
        </td>
        <td class="header-center text-center">
            <h3>OFFER <br>#{{ $quote->getReferenceCode() ?? ' - ' }}</h3>
        </td>
        <td class="header-right">
            <strong>PANIDIS S.A.</strong><br>
            11th klm ONR Thessaloniki - Kilkis<br>
            57008 IONIA, GREECE<br>
            TEL: 2310-780352<br>
            VAT: EL-998757379
        </td>
    </tr>
</table>

{{-- Customer Info --}}
<table class="offer-info">
    <tr>
        <td style="width: 70%; border-bottom: 0;"><b>CUSTOMER : {{ $quote->getCompany()->getErpId() .' '. $quote->getCompany()->getName() ?? ' - ' }}</b></td>
        <td style="width: 30%; border-bottom: 0;"><strong>VALID UNTIL:</strong> {{ $quote->getValidUntil()->format('d/m/Y') ?? ' - ' }}</td>
    </tr>
</table>

{{-- Items Table --}}
<table class="items">
    <thead>
        <tr>
            <th style="width:10%; border-top-left-radius: 8px;">PHOTO</th>
            <th style="width:35%;">CODE-DESCRIPTION</th>
            <th style="width:15%;">COLOR</th>
            <th style="width:10%;">UNIT</th>
            <th style="width:15%;">UNIT NET</th>
            <th style="width:15%; border-top-right-radius: 8px;">COMMENT</th>
        </tr>
    </thead>
    <tbody style="text-align: center;">
    @foreach($quote->getItems() as $item)
        <tr>
            <td class="text-center">
                @if(!empty($item->getitem()->getImagePath()))
                    <img src="{{ $item->getitem()->getImagePath() }}" alt="photo" width="50">
                @endif
            </td>
            <td style=""><span style="font-weight: bolder;">{{ $item->getSku() }}</span><br>{{ $item->getProductName() }}</td>
            <td>{{ $item->getColor() }}</td>
            <td>{{ $item->getUnitType()->value }}</td>
            <td>{{ number_format($item->getPrice(), 2) }} €</td>
            <td> </td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<div class="summary-table">
    <table>
        <tbody>
        <tr>
            <td class="text-right bold">Subtotal:</td>
            <td class="text-right column-cell-200">€ {{ number_format($quote->getSubtotal(), 2) }}</td>
        </tr>
        <tr>
            <td class="text-right bold">Tax {{$quote->getTaxRate()->label()}}:</td>
            <td class="text-right column-cell-200">€ {{ number_format($quote->getTax(), 2) }}</td>
        </tr>
        <tr class="total-row">
            <td class="text-right">Total:</td>
            <td class="text-right column-cell-200">€ {{ number_format($quote->getTotal(), 2) }}</td>
        </tr>
        </tbody>
    </table>
</div>
<br>
<table width="100%" >
    <tr>
        <!-- Conditions on the left -->
        <td width="60%" valign="top" style="border-bottom: none;">
            <strong>Conditions:</strong><br>
            All prices are NET<br>
            Delivery Terms: {{ $quote->getDeliveryTerms() ?? '' }}<br>
            Payment Terms: {{ $quote->getPaymentTerms() ?? '' }}
        </td>

        <!-- Signed by on the right with right-aligned text -->
        <td width="40%" valign="top" style="border-bottom: none;">
            <strong>Signed by:</strong><br>
            @foreach($quote->getAssignees() as $assignee)
                {{$assignee->getName()}} <br>
            @endforeach
        </td>
    </tr>
</table>


</body>
</html>
