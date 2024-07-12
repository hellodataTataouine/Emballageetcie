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
    display: flex;
    justify-content: space-between;
}

.company-info, .facture-info {
    width: 45%;
}

.company-info p, .facture-info p {
    margin: 0;
    padding: 0.5rem 0;
}
        .logo {
            width: 45%;
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
            page-break-inside: avoid;
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
            page-break-inside: avoid;
        }








        @import url('https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400;500&family=Hanuman:wght@300;400;700&family=Hind+Siliguri:wght@400;500&family=Kanit:wght@400;500&family=Open+Sans:wght@400;500&family=Roboto:wght@400;500&display=swap');

        * {
            box-sizing: border-box;
            font-family: '<?php echo $font_family; ?>';
        }

        pre,
        p {
            padding: 0;
            margin: 0;
            font-family: '<?php echo $font_family; ?>';
        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding: 1px;
            font-family: '<?php echo $font_family; ?>';
        }

        td,
        th {
            text-align: left;
            font-family: '<?php echo $font_family; ?>';
        }

        .visibleMobile {
            display: none;
            font-family: '<?php echo $font_family; ?>';
        }

        .hiddenMobile {
            display: block;
            font-family: '<?php echo $font_family; ?>';
        }

        .text-left {
            text-align: <?php echo $default_text_align; ?>;
            font-family: '<?php echo $font_family; ?>';
        }

        .text-right {
            text-align: <?php echo $reverse_text_align; ?>;
            font-family: '<?php echo $font_family; ?>';
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
                    {{ $commande['Designation'] ?? 'N/A' }}<br>
                    
                    {{ localize('Date de la commande') }} : {{ $commande['Date'] ?? 'N/A' }}<br>

                    {{ localize('Client') }} : {{ $user['name']}}<br>
                    {{ localize('Code Client') }}  : {{$user['CODETIERS'] }}
                </p>

               
            </td>
            <td colspan="4" align="right"
                style="width: 300px; text-align: right; padding-left: 50px; line-height: 1.5; color: #323232;">
                <img src="https://emballage-et-cie.fr/public/uploads/media/LopdO22CoDNmNDutkZ3kgZryjtZ8GVTvqZNGWLgC.jpg" alt="logo" border="0" />
                <p style="font-size: 12px;font-weight: bold; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                    Emballage et Cie</p>
                <p style="font-size: 12px; color: #5b5b5b; line-height: 24px; vertical-align: top;">
                    18 RUE DU CLOS BARROIS, 60180 Nogent-sur-Oise, France
                     <br>
                    {{ localize('Téléphone') }} : 0344256066 <br>

                    N° de TVA Intracom: FR57840855670

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



    <h2>{{ __('Détails de la facture') }}</h2>
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
