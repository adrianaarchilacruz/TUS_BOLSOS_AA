<?php 

define("CLIENT_ID", "BAAvJC1kGSxNhDKrXwBnn83UrOb9hXOT-9jNp8hWku52arW2MOgP43gGRLkRGJV6Odgsj67tR9hXLgkqqU");
define("CURRENCY", "USD");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}


?>