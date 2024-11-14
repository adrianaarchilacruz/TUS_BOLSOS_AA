<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="x-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PayPal JS SDK Standard Integration</title>

        <script src="https://www.paypal.com/sdk/js?client-id=BAAvJC1kGSxNhDKrXwBnn83UrOb9hXOT-9jNp8hWku52arW2MOgP43gGRLkRGJV6Odgsj67tR9hXLgkqqU&components=hosted-buttons&disable-funding=venmo&currency=USD"></script>


    </head>
    <body>

    <div id="paypal-container-57JZVDZ3PGZGQ"></div>
<script>
    paypal.HostedButtons({
    hostedButtonId: "57JZVDZ3PGZGQ",
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: 27
                }
            }]
        });
    },

    onApprove: function(data, actions){
        let URL = 'clases/captura.php'
        actions.order.capture().the(function(detalles) {
             window.location.href = "index.html"
        });

    },
    onCancel: function(data) {
        alert("Pago cancelado");
        console.log(data);
    }

    }).render("#paypal-container-57JZVDZ3PGZGQ")
  
</script>


    </body>
    
</html>