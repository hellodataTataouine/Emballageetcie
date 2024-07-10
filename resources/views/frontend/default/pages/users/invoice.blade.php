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
        .container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .company-info {
            width: 50%;
        }
        .logo {
            width: 50%;
            text-align: right;
        }
        .logo img {
            max-width: 100%;
            height: auto;
        }
        .user-address {
            width: 45%;
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












<div class="container">
        <div class="company-info">
            <p>SAS EMBALLAGE ET CIE</p>
            <p>18 RUE DU CLOS BARROIS</p>
            <p>60180 NOGENT SUR OISE</p>
            <p>Téléphone: 03 44 25 60 66</p>
            <p>Email: contact@emballage-et-cie.fr</p>
            <p>Site web: <a href="https://emballage-et-cie.fr" target="_blank">www.emballage-et-cie.fr</a></p>
        </div>
        <div class="logo">
            <img src="https://emballage-et-cie.fr/public/uploads/media/LopdO22CoDNmNDutkZ3kgZryjtZ8GVTvqZNGWLgC.jpg" alt="logo" class="img-fluid">
        </div>
    </div>
    
    <div class="container">
        <div class="facture-info">
        <p>Facture: {{ $commande['Indice'] ?? 'N/A' }}</p>
        <p>Date: {{ $commande['Date'] ?? 'N/A' }}</p>
            <P>Client: {{ $user['name']}}
            <p>Code Client: {{ $user['CODETIERS'] }}</p>
         

        </div>
        <div class="user-address">
            <!-- <p>{{ $user['Adresse'] }}</p>
            <p>{{ $user['Ville'] }}</p>
            <p>{{ $user['CodePostal'] }}</p>
            <p>{{ $user['Pays'] }}</p> -->
        </div>
    </div>

    <h2>{{ __('Details de la facture') }}</h2>
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
            @foreach ($facture_detail as $item)
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
