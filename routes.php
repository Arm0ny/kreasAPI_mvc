<?php

//Products
$router->route('GET', 'products/read', 'products');
$router->route('POST', 'products/create', 'products');
$router->route('PUT', 'products/update', 'products');
$router->route('DELETE', 'products/delete', 'products');

//Orders
$router->route('GET', 'orders/read', 'orders');
$router->route('POST', 'orders/create', 'orders');
$router->route('PUT', 'orders/update', 'orders');
$router->route('DELETE', 'orders/delete', 'orders');

//orderDetails
$router->route('GET', 'orderDetails/read', 'orderDetails');
$router->route('POST', 'orderDetails/create', 'orderDetails');
$router->route('PUT', 'orderDetails/update', 'orderDetails');
$router->route('DELETE', 'orderDetails/delete', 'orderDetails');

//savedCo2
$router->route('GET', 'savedCo2/readByDate', 'savedCo2');
$router->route('GET', 'savedCo2/readByDestCountry', 'savedCo2');
$router->route('GET', 'savedCo2/readByProductId', 'savedCo2');

