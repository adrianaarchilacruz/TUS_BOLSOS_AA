<?php
require 'config/config.php';
require 'config/database.php';
$db = new database;
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

//elimitar la sesion y lo del carrito//
//session_destroy();//
//para ver lo que se gzuarda en el carrito//
//print_r($_SESSION);//


$lista_carrito = array();

if($productos != null) {
    foreach($productos as $clave => $cantidad){  

        $sql = $con->prepare("SELECT id,nombre,precio,descuento, $cantidad AS cantidad  FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
        
    }

} else {
    header("Location: index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=tienda_, initial-scale=1.0">
    <title>Tienda TuBolSoAA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<header>

  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        
        <strong>TusBolsosAA</strong>
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
              <ul class="navbar-nav me-auto mb-4 mb-lg-0">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active">CATALOGO</a>
                </li>

              </ul>
       </div>
              <ul class="navbar-nav me-auto mb-9 mb-lg-9">
                <li class="nav-item">
             
                <a href="../Login-register/index.php" class="btn btn-success mb-2"><i class="fas fa-user-alt"></i>USUARIO</span></a>
            
                <a href="checkout.php" class="btn btn-primary mb-2"> CARRITO <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?> </span> </a>
            
                <a href="LOGIN-REGISTER/php/cerrar_sesion.php" class="btn btn-danger mb-2"><i class="fa-solid fa-user"></i>Cerrar sesion</a>
              </ul>

      
    </div>
  </div>
  
</header>
<!-- cajas de galeria -->
<main>
    <div class="container"> 

        <div class="row">
            <div class="col-6"> 
                <h4>DETALLES DE PAGO</h4>
                <div id="paypal-container-57JZVDZ3PGZGQ"></div>
            </div>

            <div class="col-6"> 
                <div class="table-responsive">
                    <table class="table">
                      <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Subtotal</th>
                              <th></th>
                           </tr>
                       </thead>
                        <tbody>
                           <?php if($lista_carrito == null){
                                  echo '<tr><td colspan="5"> class="text-center"><b>Lista vacia</b></td></tr>';
                            } else {

                        
                              $total = 0;
                              foreach($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;

                            ?>
                           
                                    <tr>
                                        <td><?php echo $nombre; ?></td>
                                        <td>
                                             <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal,2, '.',','); ?></div>
                                        </td>
                                   </tr>
                                <?php } ?>
                                <tr>
                                   
                                    <td colspan="2">
                                        <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                        </tbody>
                    <?php } ?>
                    </table> 
                </div>   
            </div>

        </div>
    </div>
</main>


    
<script> 
    function actualizaCantidad(cantidad, id){
      let url = 'clases/actualizar_carrito.php';
      let formData = new FormData();
      formData.append('action', 'agregar');
      formData.append('id', id);
      formData.append('cantidad', cantidad);

      fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
      }).then(response => response.json())
      .then(data => {  
        if(data.ok){

            let divsubtotal = document.getElementById('subtotal_' + id)
            divsubtotal.innerHTML = data.sub


        }
      })
    }
     
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   
<script src="https://www.paypal.com/sdk/js?client-id=BAAvJC1kGSxNhDKrXwBnn83UrOb9hXOT-9jNp8hWku52arW2MOgP43gGRLkRGJV6Odgsj67tR9hXLgkqqU&components=hosted-buttons&disable-funding=venmo&currency=USD"></script>
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
             console.log(detalles)

             let irl = 'claes/captura.php'

             return fetch (url, {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    detalles: detalles
                })
             })
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




