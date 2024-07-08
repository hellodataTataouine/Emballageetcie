<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $direction }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }}</title>
    <style>
        body {
            font-family: {{ $font_family }};
            direction: {{ $direction }};
        }
        .invoice-header, .company-info {
            text-align: {{ $default_text_align }};
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            margin: 0;
        }
        .company-info p {
            margin: 2px 0;
        }
        .invoice-info-table, .commande-table, .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-info-table th, .invoice-info-table td,
        .commande-table th, .commande-table td,
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .invoice-info-table th, .commande-table th, .invoice-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .invoice-table th {
            background-color: #c0c0c0; /* Grey header color */
        }
        .invoice-table tbody tr:nth-child(even) {
            background-color: #dcf0d4; /* Alternating row color */
        }
        .invoice-footer {
            margin-top: 20px;
            text-align: {{ $default_text_align }};
        }
        .invoice-footer p {
            margin: 0;
        }
        .footer-band {
            background-color: #c0c0c0; /* Grey band at top of footer */
            height: 5px;
        }
        .bank-info {
            border: 1px solid #c0c0c0;
            padding: 10px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }




        
    </style>
</head>
<body>



<table style="width: 100%; table-layout: fixed">
        <tr>
            <td colspan="4"
                style="border-right: 1px solid #e4e4e4; width: 300px; color: #323232; line-height: 1.5; vertical-align: top;">
                <p style="font-size: 15px; color: #5b5b5b; font-weight: bold; line-height: 1; vertical-align: top; ">
                    {{ localize('Bon de Commande') }}</p>
                <br>
                <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                         </p>

              
            </td>
            <td colspan="4" align="right"
                style="width: 300px; text-align: right; padding-left: 50px; line-height: 1.5; color: #323232;">
                <img src="{{ uploadedAsset(getSetting('favicon')) }}" alt="logo" border="0" />
                <p style="font-size: 12px;font-weight: bold; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                    {{ getSetting('system_title') }}</p>
                <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                    {{ getSetting('topbar_location') }}<br>
                    {{ localize('Téléphone') }}: {{ getSetting('navbar_contact_number') }}
                </p>
            </td>
        </tr>
        <tr class="visibleMobile">
            <td height="10"></td>
        </tr>
        <tr>
            <td colspan="10" style="border-bottom:1px solid #e4e4e4"></td>
        </tr>
    </table>








    <div class="company-info">
        <p>SAS EMBALLAGE ET CIE</p>
        <p>18 RUE DU CLOS BARROIS</p>
        <p>60180 NOGENT SUR OISE</p>
        <p>Téléphone: 03 44 25 60 66</p>
        <p>Email: contact@emballage-et-cie.fr</p>
        <p>Site web: <a href="https://emballage-et-cie.fr" target="_blank">emballage-et-cie.fr</a></p>
    </div>
    
    <h2>{{ __('Facture Information') }}</h2>
    <table class="commande-table">
        <thead>
            <tr>
                <th>{{ __('Indice') }}</th>
                <th>{{ __('Désignation') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Débit') }}</th>
                <th>{{ __('Crédit') }}</th>
                <th>{{ __('Solde') }}</th>
                <th>{{ __('Payée') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commande as $item)
                <tr>
                    <td>{{ $item['Indice'] }}</td>
                    <td>{{ $item['Designation'] }}</td>
                    <td>{{ date('d/m/Y', strtotime($item['Date'])) }}</td>
                    <td>{{ number_format($item['Debit'], 2, ',', ' ') }}</td>
                    <td>{{ number_format($item['Credit'], 2, ',', ' ') }}</td>
                    <td>{{ number_format($item['Solde'], 2, ',', ' ') }}</td>
                    <td>{{ $item['Payee'] ? __('Oui') : __('Non') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>{{ __('Details Commande') }}</h2>
    <table class="invoice-table">
        <thead>
            <tr>
                <th>{{ __('Désignation') }}</th>
                <th>{{ __('QTE') }}</th>
                <th>{{ __('PU HT') }}</th>
                <th>{{ __('Montant HT') }}</th>
                <th>{{ __('TVA') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order as $item)
                <tr>
                    <td>{{ $item['LibProd'] }}</td>
                    <td>{{ $item['Quantité'] }}</td>
                    <td>{{ number_format($item['PrixVente'], 2, ',', ' ') }}</td>
                    <td>{{ number_format($item['TotaleHT'], 2, ',', ' ') }}</td>
                    <td>{{ $item['TauxTVA'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    

    <div class="footer-band"></div>

    <div class="invoice-footer">
        <p>{{ __('Total HT') }}: {{ number_format($totalHT, 2, ',', ' ') }} €</p>
        <p>{{ __('Total TVA') }}: {{ number_format($totalTVA, 2, ',', ' ') }} €</p>
        <p>{{ __('Total TTC') }}: {{ number_format($totalTTC, 2, ',', ' ') }} €</p>
    </div>

    <div class="bank-info">
        <p>{{ __('Coordonnées bancaires société') }}:</p>
        <p>{{ __('Banque') }} : {{ __('BANQUE POPULIARE') }}</p>
        <p>{{ __('RIB') }} : 1020 7001 6023 2104 9556 381</p>
        <p>{{ __('IBAN') }} : FR76 1020 7001 6023 2104 9556 381</p>
        <p>{{ __('BIC / SWIFT') }} : CCBPFRPPMTG</p>
    </div>
</body>
</html>
