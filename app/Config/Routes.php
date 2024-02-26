<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Mingala::index');
$routes->get('/pack/(:any)', 'Mingala::pack/$1');
$routes->post('/login-user', 'Mingala::login');
$routes->get('/logout-user', 'Mingala::logout');

// DashBoard
$routes->get('/get-data-order-by-style', 'Mingala::getDataOrderByStyle');
$routes->get('/get-order-report', 'Mingala::getOrderReport');
$routes->get('/data-packaged-item', 'Mingala::dataTabelPackagedItem');
$routes->get('/data-packing-today', 'Mingala::dataTabelPackingToday');
//

//Master Item
$routes->get('/data-item', 'Mingala::dataItemTabel');
$routes->post('/import-excel-item', 'Mingala::importExcelItem');
$routes->post('/edit-item', 'Mingala::editItem');
$routes->post('/delete-item', 'Mingala::deleteItem');
$routes->post('/cek-tambah-item', 'Mingala::cekTambahItem');
//Doneee


//Master Carton
$routes->get('/data-carton', 'Mingala::dataCartonTabel');
$routes->post('/import-excel-carton', 'Mingala::importExcelCarton');
$routes->post('/edit-carton', 'Mingala::editCarton');
$routes->post('/delete-carton', 'Mingala::deleteCarton');
$routes->post('/scan-carton', 'Mingala::scanCarton');
$routes->post('/check-qty-item-carton', 'Mingala::cekQtySisaCarton');
$routes->post('/add-carton-satu-item', 'Mingala::addCartonSatuItem');
$routes->post('/cek-item-tambah-carton', 'Mingala::cekItemTambahCarton');
$routes->post('/add-stock-carton', 'Mingala::addStockCarton');
//Doneee

//Stock In
$routes->post('/scan-by-item', 'Mingala::scanByItem');
$routes->post('/add-to-stock', 'Mingala::addToStock');
// Doneee

//Scan Packing
$routes->post('/cek-carton-semua', 'Mingala::cekCartonSemua');
$routes->post('/save-packing', 'Mingala::savePacking');
// Doneee

//Report Stock In
$routes->get('/data-stock', 'Mingala::dataStockTabel');
$routes->post('/edit-stock', 'Mingala::editStockIn');
$routes->post('/cek-data-stock', 'Mingala::cekDataStock');
$routes->get('/export-data-stock-hari-ini', 'Mingala::exportDataStockHariIni');
//Doneee

//Report Packing Tabel
$routes->get('/export-data-packing', 'Mingala::exportDataPacking');
//Doneee

//Export Database
$routes->get('export-database', 'Mingala::exportDatabase');
