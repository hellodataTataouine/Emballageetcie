<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réception de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cher(e) {{ $clientnom }},</h2>
        
        <p>Nous vous confirmons que votre commande a bien été reçue. Voici les détails :</p>
        
        <h3>Détails de la Commande :</h3>
        
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix de Vente</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($FullOrder as $orderItem)
                    <tr>
                        <td>{{ $orderItem['Référence'] }}</td>
                        <td>{{ $orderItem['LibProd'] }}</td>
                        <td>{{ $orderItem['Quantité'] }}</td>
                        <td>{{ $orderItem['PrixVente'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Sous-total:</strong> {{ $total_commandeHT }}</p>
        <p><strong>Coût d'expédition:</strong> {{ $shipping_cost }}</p>
        <p><strong>Prix total:</strong> {{ $total_commande }}</p>
        <p><strong>Moyen de Paiement:</strong> {{ $Payment }}</p>
        <p><strong>Adresse de Livraison:</strong> {{ $Adresse }}, {{ $codepostal }} {{ $Ville }}</p>
        <p><strong>Adresse de facturation:</strong> {{ $billingUserAddress }}</p>
        <p><strong>Type de livraison:</strong> {{ $Livraison }}</p>
        <p><strong>Téléphone:</strong> {{ $Phone }}</p>
        @if ($Payment === "vir")
            <p class="mb-0">
                <strong>Coordonnées bancaires:</strong><br>
                <strong>Banque:</strong> <br>
                <strong>Agence:</strong><br>
                <strong>Compte:</strong> <br>
                <strong>Clé:</strong> <br>
                <strong>IBAN:</strong> <br>
                <strong>BIC/SWIFT:</strong> <br>
                <strong>Titulaire:</strong><br>
               <br>
                
            </p>
        @endif
        <div class="footer">
            <p>Merci pour votre commande.</p>
            <p>Cordialement,</p>
            
        </div>
    </div>
</body>
</html>
