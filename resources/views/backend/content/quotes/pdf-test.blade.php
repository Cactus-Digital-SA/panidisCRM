<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offer #{{ $quote->getReferenceCode() ?? ' ' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: top;
        }
        .no-border td {
            border: none;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
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
        .offer-info {
            margin-top: 10px;
            border: 1px solid #000;
            font-weight: bold;
        }
        .offer-info td {
            padding: 4px 6px;
        }
        .offer-info th {
            background-color: #f0f0f0;
            border: 1px solid #000;
        }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .small { font-size: 10px; }
        .conditions {
            width: 50%;
            font-size: 10px;
            margin-top: 20px;
        }
        .signature {
            width: 50%;
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
        }
        .logo {
            max-width: 180px;
            max-height: 100px;
        }
    </style>
</head>
<body>

{{-- Header --}}
<table class="header no-border">
    <tr>
        <td class="header-left">
            <img src="{{ asset('assets/img/logo/logo_black.png') }}" alt="" class="logo" width="180">
        </td>
        <td class="header-center">
            <h3>OFFER #{{ $quote->getReferenceCode() ?? ' - ' }}</h3>
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
    <tr style="text-align:center;">
        <th style="width: 25%;">CUSTOMER:</th>
        <td>{{ $quote->getCompany()->getErpId() .' '. $quote->getCompany()->getName() ?? ' - ' }}</td>
        <td style="width: 30%;"><strong>VALID UNTIL:</strong> {{ $quote->getValidUntil()->format('d/m/Y') ?? ' - ' }}</td>
    </tr>
</table>

{{-- Items Table --}}
<table style="margin-top: 10px;">
    <thead>
    <tr style="background-color:#f0f0f0;">
        <th style="width:10%;">PHOTO</th>
        <th style="width:35%;">CODE-DESCRIPTION</th>
        <th style="width:15%;">COLOR</th>
        <th style="width:10%;">UNIT</th>
        <th style="width:15%;">UNIT NET</th>
        <th style="width:15%;">COMMENT</th>
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
            <td><span style="font-weight: bolder;">{{ $item->getSku() }}</span><br>{{ $item->getProductName() }}</td>
            <td>{{ $item->getColor() }}</td>
            <td>{{ $item->getUnitType()->value }}</td>
            <td>{{ number_format($item->getPrice(), 2) }} €</td>
            <td> </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table width="100%" >
    <tr>
        <!-- Notes on the left -->
        <td width="50%" valign="top">
            <strong>Conditions:</strong><br>
            All prices are NET<br>
            Delivery Terms: {{ $quote->getDeliveryTerms() ?? '' }}<br>
            Payment Terms: {{ $quote->getPaymentTerms() ?? '' }}
        </td>

        <!-- Service Include on the right with right-aligned text -->
        <td width="50%" valign="top" style="">
            <strong>Signed by:</strong><br>
            @foreach($quote->getAssignees() as $assignee)
                {{$assignee->getName()}} <br>
            @endforeach
        </td>
    </tr>
</table>


</body>
</html>
