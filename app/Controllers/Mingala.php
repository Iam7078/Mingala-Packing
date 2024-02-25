<?php

/** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */

namespace App\Controllers;

use App\Models\CartonDetailEditModel;
use App\Models\CartonDetailModel;
use App\Models\CartonModel;
use App\Models\StockItemModel;
use App\Models\StockItemDetailModel;
use App\Models\StockItemDetailDeleteModel;
use App\Models\UserModel;
use App\Models\ItemModel;
use App\Models\PackingModel;

use Ifsnop\Mysqldump\Mysqldump;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use CodeIgniter\I18n\Time;

class Mingala extends BaseController
{
    public function index(): string
    {
        return view('auth/login');
    }

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function checkAccess($segment, $userRole)
    {
        $data = [
            'userRole' => $userRole
        ];
        if ($segment === 'home') {
            if (session('isLoggedIn')) {
                return $this->getDataDashHariIni($data);
            }
            return view('auth/login');
        } elseif ($segment === 'dash') {
            return $this->getDataDashHariIni($data);
        } elseif ($segment === 'DaToIt') {
            return $this->getDataDetailItemOrder($data);
        } elseif ($segment === 'ScSi') {
            return view('scan_stock_in');
        } elseif ($segment === 'ScPa') {
            return view('scan_packing');
        } elseif ($segment === 'MaIt') {
            return view('master_item');
        } elseif ($segment === 'MaItAd') {
            return view('master_item_add');
        } elseif ($segment === 'MaCa') {
            return $this->getDataCarton($data);
        } elseif ($segment === 'MaCaAdSa') {
            return $this->getDataCartonAddSa($data);
        } elseif ($segment === 'MaCaAd') {
            return view('master_carton_add');
        } elseif ($segment === 'ReSt') {
            return view('report_stock_item');
        } elseif ($segment === 'RePa') {
            return $this->getDataReportPacking($data);
        }

    }

    public function pack()
    {
        $segment = $this->request->uri->getSegment(2);

        if (!session('isLoggedIn')) {
            return view('auth/login');
        }

        $userRole = session('userRole');

        return $this->checkAccess($segment, $userRole);
    }


    // Login User
    public function login()
    {
        if ($this->request->isAJAX()) {
            $jsonData = $this->request->getJSON();
            $email = $jsonData->email;
            $password = $jsonData->password;
            $model = new UserModel();
            $user = $model->where('email', $email)->first();

            if ($user && $password === $user['password']) {
                session()->set([
                    'isLoggedIn' => true,
                    'userRole' => $user['role'],
                    'userName' => $user['username'],
                    'userEmail' => $user['email'],
                    'userPassword' => $user['password'],
                ]);

                $response = [
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'redirect' => '/pack/dash'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Email and password are invalid'
                ];
            }

            return $this->response->setJSON($response);
        }
    }
    // Login User Doneee


    // Logout
    public function logout()
    {
        session()->destroy();

        return view('auth/login');
    }
    // Logout Doneee



    // Dashboard
    public function getDataDashHariIni($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $formattedDate = date("Y-m-d");

        $itemModel = new ItemModel();
        $packingModel = new PackingModel();
        $data['totalQtyOrder'] = $itemModel->getTotalQty();
        $data['packingg'] = $packingModel->where('date', $formattedDate)->findAll();
        $data['totalPacking'] = $packingModel->getTotalPacking();
        $data['totalQtyPacking'] = $packingModel->getTotalQtyPacking();
        $data['totalPackingHariIni'] = count($data['packingg']);

        return view('dashboard', $data);
    }

    public function getDataDetailItemOrder($data)
    {
        $itemModel = new ItemModel();

        $data['item'] = $itemModel->select('style, MAX(mo) as mo, SUM(qty) as total_qty')
            ->groupBy('style')
            ->findAll();

        return view('total_qty_order_dash', $data);
    }

    public function getDataOrderByStyle()
    {
        $dataStyle = $this->request->getGet('dataStyle');
        $itemModel = new ItemModel();

        $data['item_by_style'] = $itemModel->where('style', $dataStyle)->findAll();
        $data['style'] = $dataStyle;

        return view('total_qty_order_dash_detail', $data);
    }

    public function getOrderReport()
    {
        $itemModel = new ItemModel();
        $distinctSizes = $itemModel->distinct()->select('size')->findAll();
        $sizes = [];
        foreach ($distinctSizes as $size) {
            $sizes[] = $size['size'];
        }
        usort($sizes, function ($a, $b) {
            $order = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL'];
            return array_search($a, $order) - array_search($b, $order);
        });

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('B2', 'ORDER REPORT');
        $activeWorksheet->setCellValue('B4', 'No');
        $activeWorksheet->setCellValue('C4', 'STYLE');
        $activeWorksheet->setCellValue('D4', 'MO');
        $activeWorksheet->setCellValue('E4', 'COLOR');
        $activeWorksheet->setCellValue('F4', 'SIZE');

        $sizeColumn = 'F';
        $sizeColumns = [];
        foreach ($sizes as $size) {
            $sizeColumns[$size] = $sizeColumn;
            $sizeColumn++;
        }

        foreach ($sizeColumns as $size => $column) {
            $activeWorksheet->setCellValue($column . '5', $size);
        }

        $sizeLong = chr(ord($sizeColumn) - 1);

        $activeWorksheet->setCellValue($sizeColumn . '4', 'TOTAL');
        $columnTotal = $sizeColumn;
        $activeWorksheet->setCellValue(++$sizeColumn . '4', 'TOTAL QTY');
        $columnTotalQty = $sizeColumn;

        $dataOrder = $itemModel->select('style, MAX(mo) as mo, SUM(qty) as total_qty')->groupBy('style')->findAll();
        $dataOrder = array_reverse($dataOrder);
        $no = 1;
        $columnData = 6;
        foreach ($dataOrder as $key => $value) {
            // Item By Style
            $activeWorksheet->setCellValue('B' . $columnData, $no);
            $activeWorksheet->setCellValue('C' . $columnData, $value['style']);
            $activeWorksheet->setCellValue('D' . $columnData, $value['mo']);
            $activeWorksheet->setCellValue($columnTotalQty . $columnData, $value['total_qty']);

            // Item By Color
            $dataOrderColor = $itemModel->distinct()->select('style, color')->where('style', $value['style'])->findAll();
            $mergeEndColumn = $columnData + count($dataOrderColor) - 1;
            $activeWorksheet->mergeCells("B$columnData:B$mergeEndColumn")->mergeCells("C$columnData:C$mergeEndColumn")
                ->mergeCells("D$columnData:D$mergeEndColumn")->mergeCells("$columnTotalQty$columnData:$columnTotalQty$mergeEndColumn");

            $styleAlignment = $activeWorksheet->getStyle("B$columnData:D$mergeEndColumn");
            $styleAlignment->getAlignment()->setVertical("middle")->setHorizontal("center");

            $styleAlignment = $activeWorksheet->getStyle("E$columnData:E$mergeEndColumn");
            $styleAlignment->getAlignment()->setVertical("middle");

            $styleAlignment = $activeWorksheet->getStyle("F$columnData:$columnTotalQty$mergeEndColumn");
            $styleAlignment->getAlignment()->setVertical("middle")->setHorizontal("center");

            $columnColor = 0;
            foreach ($dataOrderColor as $key => $value2) {
                $activeWorksheet->setCellValue('E' . ($columnData + $columnColor), $value2['color']);

                $dataQty = $itemModel->select('size, qty')
                    ->where('style', $value['style'])
                    ->where('color', $value2['color'])
                    ->findAll();

                $totalQty = 0;
                foreach ($dataQty as $row) {
                    $sizeColumn2 = $sizeColumns[$row['size']];
                    $activeWorksheet->setCellValue($sizeColumn2 . ($columnData + $columnColor), $row['qty']);
                    $totalQty += $row['qty'];
                }

                $activeWorksheet->setCellValue($columnTotal . ($columnData + $columnColor), $totalQty);
                $columnColor++;
            }
            $columnData += $columnColor;
            $no++;
        }

        // Style semuane
        $columnsToCenter = ['B', 'C', 'D', 'E', $columnTotal, $columnTotalQty];
        foreach ($columnsToCenter as $columnLetter) {
            $mergeRange = $columnLetter . '4:' . $columnLetter . '5';
            $activeWorksheet->mergeCells($mergeRange);
        }

        $lastColumn = $sizeColumn;

        $alignHeader = 'B2:' . $lastColumn . '5';
        $style = $activeWorksheet->getStyle($alignHeader);
        $style->getAlignment()->setVertical("middle")->setHorizontal("center");

        $activeWorksheet->mergeCells('B2:' . $lastColumn . '3');
        $activeWorksheet->mergeCells('F4:' . chr(ord($lastColumn) - 2) . '4');

        $headerStyle = $activeWorksheet->getStyle('B2:' . $lastColumn . '5');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff');

        $headerStyle2 = $activeWorksheet->getStyle('F5:' . $sizeLong . '5');
        $headerStyle2->getFont()->setBold(true);
        $headerStyle2->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1cc88a');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $activeWorksheet->getStyle('B2:' . $lastColumn . ($columnData - 1))->applyFromArray($styleArray);
        $columnLetters = range('B', $lastColumn);
        foreach ($columnLetters as $colLetter) {
            $activeWorksheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $filename = "Order_Report.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    // Dashboard Doneee



    // Master Item Doneee
    public function dataItemTabel()
    {
        $itemModel = new ItemModel();
        $cartonDetailModel = new CartonDetailModel();

        $dataItem = $itemModel->findAll();

        $data = [];

        $dataItem = array_reverse($dataItem);
        foreach ($dataItem as $item) {
            $idItem = $item['id_item'];
            $userStatusCol = 'red';
            $userStatusText = 'Unfinished';

            $dataPacking = $cartonDetailModel->sumQtyByItemId($idItem);
            $cekId = $cartonDetailModel->cekIdItem($idItem);
            $jumlahPac = isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0;

            if ($item['qty'] == $jumlahPac) {
                $userStatusCol = 'green';
                $userStatusText = 'Finished';
            }

            $qrCode = new QrCode($idItem);
            $qrCode->setSize(67);
            $writer = new PngWriter();
            $gambarQrCode = $writer->write($qrCode);
            $penyimpanan = "itemQr/";
            $namaFile = $penyimpanan . $idItem . '.png';
            file_put_contents($namaFile, $gambarQrCode->getString());

            $data[] = [
                'id_item' => $idItem,
                'style' => $item['style'],
                'mo' => $item['mo'],
                'color' => $item['color'],
                'size' => $item['size'],
                'date_wh' => $item['date_wh'],
                'qty_order' => $item['qty'],
                'qty_packing' => isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0,
                'qty_carton' => isset($cekId['total_qty']) ? $cekId['total_qty'] : 0,
                'status_color' => $userStatusCol,
                'status_text' => $userStatusText,
                'userRole' => session('userRole')
            ];
        }
        return $this->response->setJSON(['data' => $data]);
    }

    public function importExcelItem()
    {
        $data = [];
        $file = $this->request->getFile('excelFile');

        if ($file->isValid() && $file->getExtension() == 'xlsx') {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();

                $itemModel = new ItemModel();
                $stockModel = new StockItemModel();

                foreach ($worksheet->getRowIterator(2) as $row) {
                    $cellIterator = $row->getCellIterator();

                    $style = $cellIterator->seek('B')->current()->getValue();
                    $color = $cellIterator->seek('C')->current()->getValue();
                    $size = $cellIterator->seek('D')->current()->getValue();
                    $qty = $cellIterator->seek('E')->current()->getValue();
                    $mo = $cellIterator->seek('F')->current()->getValue();
                    $date_wh = $cellIterator->seek('G')->current()->getValue();

                    if (empty($style) && empty($color) && empty($size) && empty($qty) && empty($mo) && empty($date_wh)) {
                        continue;
                    }

                    $excelDate = Date::excelToDateTimeObject($date_wh);
                    $formattedDate = $excelDate->format('Y-m-d');

                    if ($itemModel->isDuplicate($style, $color, $size)) {
                        $errorMessage = "Duplikat data ditemukan untuk Style: $style, Color: $color, Size: $size.";
                        return $this->response->setJSON(['success' => false, 'error' => $errorMessage])->setStatusCode(400);
                    }

                    if (!filter_var($qty, FILTER_VALIDATE_INT) || $qty <= 0) {
                        $errorMessage = "Masukkan nilai qty yang valid (angka bulat positif lebih dari 0).";
                        return $this->response->setJSON(['success' => false, 'error' => $errorMessage])->setStatusCode(400);
                    }

                    $lastItemId = $itemModel->getLastItemId();
                    $lastIdNumber = $lastItemId ? (int) substr($lastItemId, 2) + 1 : 1;
                    $idItem = 'IM' . str_pad($lastIdNumber, 10, '0', STR_PAD_LEFT);

                    $rowData = [
                        'style' => $style,
                        'color' => $color,
                        'size' => $size,
                        'qty' => $qty,
                        'mo' => $mo,
                        'date_wh' => $formattedDate,
                        'id_item' => $idItem,
                    ];

                    $itemModel->insert($rowData);

                    $stockData = ['id_item' => $idItem, 'qty' => 0];
                    $stockModel->insert($stockData);

                    $data['messages'][] = "Data berhasil diimpor untuk ID Item: $idItem";
                }
                $data['success'] = true;
            } catch (\Throwable $th) {
                $data['error'] = $th->getMessage();
                $data['success'] = false;
            }
        } else {
            $data['error'] = 'File Excel tidak valid.';
            $data['success'] = false;
        }
        return $this->response->setJSON($data);
    }

    public function editItem()
    {
        $request = $this->request->getJSON();
        $itemModel = new ItemModel();
        $data = [
            'style' => $request->style,
            'color' => $request->color,
            'size' => $request->size,
            'qty' => $request->qty,
            'mo' => $request->mo,
            'date_wh' => $request->date_wh,
        ];

        $updated = $itemModel->update($request->id, $data);

        if ($updated) {
            return $this->response->setJSON(['message' => 'Data berhasil diubah']);
        } else {
            return $this->response->setJSON(['message' => 'Perubahan data gagal']);
        }

    }

    public function deleteItem()
    {
        $request = $this->request->getJSON();
        $itemModel = new ItemModel();
        $stockModel = new StockItemModel();
        $stockItemDetailDeleteModel = new StockItemDetailDeleteModel();
        $cartonModel = new CartonModel();
        $cartonDetailModel = new CartonDetailModel();
        $packingModel = new PackingModel();

        $dataCarton = $cartonDetailModel->where('id_item', $request->id_item)->findAll();

        foreach ($dataCarton as $dataCarton) {
            if ($request->cekPac == 1 && $dataCarton['status'] == 1) {
                $dataPacking = $packingModel->where('id_carton', $dataCarton['id_carton'])->first();
                $deleted = $packingModel->delete($dataPacking['id_packing']);
            }

            $deleted2 = $cartonModel->delete($dataCarton['id_carton']);
            $deleted3 = $cartonDetailModel->delete($dataCarton['id_carton']);
        }

        $deleted4 = $itemModel->delete($request->id_item);
        $deleted5 = $stockModel->delete($request->id_item);
        $deleted6 = $stockItemDetailDeleteModel->delete($request->id_item);

        if (($request->cekPac == 1 && $deleted && $deleted2 && $deleted3) || ($request->cekCar == 1 && $deleted2 && $deleted3) || (!$request->cekPac && !$request->cekCar && $deleted4 && $deleted5 && $deleted6)
        ) {
            return $this->response->setJSON(['success' => true, 'message' => 'Item berhasil dihapus']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Item gagal dihapus']);
        }
    }

    public function cekTambahItem()
    {
        $request = $this->request->getJSON();
        $itemModel = new ItemModel();
        $stockItemModel = new StockItemModel();

        $duplicate = $itemModel->cekDuplikatItem($request->style, $request->color, $request->size);

        if ($duplicate) {
            return $this->response->setJSON(['duplicate' => $duplicate]);
        }

        $lastItemId = $itemModel->getLastItemId();
        $lastIdNumber = $lastItemId ? (int) substr($lastItemId, 2) + 1 : 1;
        $idItem = 'IM' . str_pad($lastIdNumber, 10, '0', STR_PAD_LEFT);

        $data = [
            'id_item' => $idItem,
            'style' => $request->style,
            'color' => $request->color,
            'size' => $request->size,
            'qty' => $request->qty,
            'mo' => $request->mo,
            'date_wh' => $request->date_wh,
        ];

        $data2 = [
            'id_item' => $idItem,
            'qty' => 0,
        ];

        if ($itemModel->insert($data) && $stockItemModel->insert($data2)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }
    // Master item doneeeeee



    // Master Carton
    public function getDataCarton($data)
    {
        $cartonModel = new CartonModel();
        $data['carton'] = $cartonModel->findAll();

        return view('master_carton', $data);
    }

    public function importExcelCarton()
    {
        $file = $this->request->getFile('excelFile');

        if (!$file->isValid() || $file->getExtension() !== 'xlsx') {
            return $this->response->setJSON(['success' => false, 'error' => 'File Excel tidak valid.'])->setStatusCode(400);
        }

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $cartonModel = new CartonModel();
            $cartonDetailModel = new CartonDetailModel();
            $itemModel = new ItemModel();

            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $nomorCarton = $cellIterator->seek('B')->current()->getValue();
                $idItem = $cellIterator->seek('C')->current()->getValue();
                $qtyItem = $cellIterator->seek('D')->current()->getValue();

                if (empty($nomorCarton) && empty($idItem) && empty($qtyItem)) {
                    continue;
                }

                if (!filter_var($nomorCarton, FILTER_VALIDATE_INT) || $nomorCarton <= 0 || !filter_var($qtyItem, FILTER_VALIDATE_INT) || $qtyItem <= 0) {
                    $errorMessage = "Masukkan nomor carton dan qty yang valid (angka bulat positif lebih dari 0).";
                    return $this->response->setJSON(['success' => false, 'error' => $errorMessage])->setStatusCode(400);
                }

                $itemData = $itemModel->find($idItem);

                if (empty($itemData)) {
                    $errorMessage = 'Item dengan ID ' . $idItem . ' tidak ditemukan dalam database.';
                    return $this->response->setJSON(['success' => false, 'error' => $errorMessage])->setStatusCode(400);
                }

                $qtyOrder = $itemData['qty'];
                $totalQty = $cartonDetailModel->getTotalQtyByIdItem($idItem);
                $availableQty = $qtyOrder - $totalQty;

                if ($qtyItem > $availableQty) {
                    $errorMessage = 'Qty untuk item ' . $idItem . ' melebihi qty order. Qty sisa : ' . $availableQty;
                    return $this->response->setJSON(['success' => false, 'error' => $errorMessage])->setStatusCode(400);
                }

                $newCartonId = $cartonModel->generateNextCartonId();
                $cartonData = [
                    'id_carton' => $newCartonId,
                    'nomor_carton' => $nomorCarton,
                    'qty_per_carton' => $qtyItem
                ];
                $rowData = [
                    'id_carton' => $newCartonId,
                    'id_item' => $idItem,
                    'qty' => $qtyItem
                ];

                $cartonModel->insertCarton($cartonData);
                $this->db->table('carton_detail')->insertBatch($rowData);
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $th) {
            return $this->response->setJSON(['success' => false, 'error' => $th->getMessage()])->setStatusCode(500);
        }
    }

    public function editCarton()
    {
        $request = $this->request->getJSON();
        $itemModel = new ItemModel();
        $cartonModel = new CartonModel();
        $cartonDetailModel = new CartonDetailModel();
        $cartonDetailEditModel = new CartonDetailEditModel();
        $idItem = $request->id_item;
        $qtyAwal = $request->qty;

        $itemData = $itemModel->find($idItem);
        $qtyOrder = $itemData['qty'];
        $totalQty = $cartonDetailModel->getTotalQtyByIdItem($idItem);
        $availableQty = $qtyOrder - ($totalQty - $request->qty_awal);

        if ($qtyAwal > $availableQty) {
            $errorMessage = 'Qty untuk item ' . $idItem . ' melebihi qty order. Qty sisa : ' . $availableQty;
            return $this->response->setJSON(['success' => false, 'message' => $errorMessage]);
        }

        $data = [
            'qty' => $request->qty,
        ];

        $data2 = [
            'qty_per_carton' => $request->qty_per_carton,
        ];

        $updated = $cartonModel->update($request->id_carton, $data2);
        $updated2 = $cartonDetailEditModel->update($request->id_key, $data);

        if ($updated && $updated2) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diubah']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Perubahan data gagal']);
        }
    }

    public function deleteCarton()
    {
        $request = $this->request->getJSON();
        $cartonModel = new CartonModel();
        $cobaCekCartonModel = new CartonDetailModel();

        $deleted1 = $cartonModel->delete($request->id_carton);
        $deleted2 = $cobaCekCartonModel->delete($request->id_carton);

        if ($request->cek == 1) {
            $packingModel = new PackingModel();
            $dataPacking = $packingModel->where('id_carton', $request->id_carton)->first();
            $idPacking = $dataPacking['id_packing'];
            $deleted3 = $packingModel->delete($idPacking);

            if (!$deleted3) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus packing terkait']);
            }
        }

        if ($deleted1 && $deleted2) {
            return $this->response->setJSON(['success' => true, 'message' => 'Carton berhasil dihapus']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Carton gagal dihapus']);
        }
    }

    public function cekQtySisaCarton()
    {
        $id_item = $this->request->getVar('id_item');

        $itemModel = new ItemModel();
        $cartonDetailModel = new CartonDetailModel();

        $dataItem = $itemModel->where('id_item', $id_item)->first();

        $result = $cartonDetailModel->getTotalQtyByIdItem($id_item);

        $response = [
            'qty_order' => $dataItem['qty'],
            'qty_carton' => $result
        ];

        return $this->response->setJSON($response);
    }

    public function getDataCartonAddSa($data)
    {
        $itemModel = new ItemModel();
        $data['item'] = $itemModel->findAll();

        return view('master_carton_tambah', $data);
    }

    public function addCartonSatuItem()
    {
        $cartonModel = new CartonModel();
        $idItem = $this->request->getVar('id_item');
        $totalQty = $this->request->getVar('qty');
        $jumlah = $this->request->getVar('jumlah');
        $cek = $this->request->getVar('cek');

        $startNomorCarton = ($cek == 1) ? $cartonModel->getNomorCarton() : 0;

        for ($i = 1; $i <= $jumlah; $i++) {
            $newCartonId = $cartonModel->generateNextCartonId();
            $cartonNomor = $startNomorCarton + $i;

            $cartonData = [
                'id_carton' => $newCartonId,
                'nomor_carton' => $cartonNomor,
                'qty_per_carton' => $totalQty
            ];

            $cartonModel->insertCarton($cartonData);

            $cartonDetailData = [
                'id_carton' => $newCartonId,
                'id_item' => $idItem,
                'qty' => $totalQty
            ];

            $this->db->table('carton_detail')->insert($cartonDetailData);
        }

        return $this->response->setJSON(['message' => 'Data berhasil disimpan']);
    }

    public function cekItemTambahCarton()
    {
        $request = $this->request->getJSON();
        $itemModel = new ItemModel();
        $cartonDetailModel = new CartonDetailModel();

        $itemExists = $itemModel->isItemExists($request->id_item);

        if (!$itemExists) {
            return $this->response->setJSON(['exists' => $itemExists]);
        }

        $dataItem = $itemModel->where('id_item', $request->id_item)->first();

        $result = $cartonDetailModel->getTotalQtyByIdItem($request->id_item);

        $response = [
            'exists' => $itemExists,
            'qty_order' => $dataItem['qty'],
            'qty_carton' => $result
        ];

        return $this->response->setJSON($response);
    }

    public function addStockCarton()
    {
        $cartonModel = new CartonModel();
        $totalQty = array_sum($this->request->getVar('qty'));

        $newCartonId = $cartonModel->generateNextCartonId();

        $nomorCarton = $this->request->getVar('nomor_carton');

        $cartonData = [
            'id_carton' => $newCartonId,
            'nomor_carton' => $nomorCarton,
            'qty_per_carton' => $totalQty
        ];

        $cartonModel->insertCarton($cartonData);

        $idItems = $this->request->getVar('id_item');
        $qtys = $this->request->getVar('qty');

        $cartonDetailData = [];

        foreach ($idItems as $key => $idItem) {
            $cartonDetailData[] = [
                'id_carton' => $newCartonId,
                'id_item' => $idItem,
                'qty' => $qtys[$key]
            ];
        }

        $this->db->table('carton_detail')->insertBatch($cartonDetailData);

        return $this->response->setJSON(['message' => 'Data berhasil disimpan']);
    }
    // Master Carton Doneee



    // Scan Stock In
    public function scanByItem()
    {
        $request = $this->request->getJSON();
        $id_item = $request->id_item;

        $itemModel = new ItemModel();
        $itemData = $itemModel->find($id_item);

        if ($itemData) {
            $style = $itemData['style'];
            $color = $itemData['color'];
            $size = $itemData['size'];
            $mo = $itemData['mo'];
            $qtyItem = $itemData['qty'];
            $dateWh = $itemData['date_wh'];

            $detailModel = new CartonDetailModel();
            $resultt = $detailModel->sumQtyByItemId($id_item);
            $qtyCekPac = isset($resultt['total_qty']) ? $resultt['total_qty'] : 0;

            $itemModel2 = new StockItemModel();
            $qtyCarton = $itemModel2->getTotalQtystock($id_item);

            $data = [
                'success' => true,
                'id_item' => $id_item,
                'style' => $style,
                'color' => $color,
                'size' => $size,
                'mo' => $mo,
                'qty_order' => $qtyItem,
                'qty_stock' => $qtyCarton,
                'date_wh' => $dateWh,
                'qty_packing' => $qtyCekPac
            ];

            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }
    public function addToStock()
    {
        date_default_timezone_set('Asia/Jakarta');
        $formattedDate = date("Y-m-d");

        $request = $this->request->getJSON();
        $stockModel = new StockItemModel();
        $stockDetailModel = new StockItemDetailModel();

        if (!$request || !isset($request->id_item) || !isset($request->qty)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid.'])->setStatusCode(400);
        }

        $idItem = $request->id_item;
        $qty = $request->qty;

        $result = $stockModel->addOrUpdateStockItem($idItem, $qty, $formattedDate);
        $result2 = $stockDetailModel->addStockToday($idItem, $qty, $formattedDate);

        if ($result && $result2) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data stock berhasil ditambahkan.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menambahkan data stock.'])->setStatusCode(500);
        }
    }
    // Scan Stock In Doneee



    // Scan Packing
    public function cekCartonSemua()
    {
        $request = $this->request->getJSON();
        $idCarton = $request->id_carton;

        $itemModel = new ItemModel();
        $stockModel = new StockItemModel();
        $cartonModel = new CartonModel();
        $cartonDetailModel = new CartonDetailModel();
        $packingModel = new PackingModel();

        $statusPacking = $packingModel->where('id_carton', $idCarton)->first();
        if ($statusPacking) {
            $erormessage = '';
            return $this->response->setJSON(['success' => false, 'message' => 'Carton dengan ID : ' . $idCarton . ' sudah dipacking']);
        }

        $dataCarton = $cartonModel->where('id_carton', $idCarton)->first();
        if (!$dataCarton) {
            $erormessage = 'Data carton dengan ID : ' . $idCarton . ' tidak ada';
            return $this->response->setJSON(['success' => false, 'message' => $erormessage]);
        }
        $nomorCarton = $dataCarton['nomor_carton'];
        $qtyTotalCarton = $dataCarton['qty_per_carton'];

        $dataDetailCarton = $cartonDetailModel->where('id_carton', $idCarton)->findAll();
        $idItem = [];
        $styleItem = [];
        $colorItem = [];
        $sizeItem = [];
        $qtyOrder = [];
        $qtyCarton = [];
        $qtyStock = [];
        $qtyPacking = [];
        $qtyPerCarton = [];

        foreach ($dataDetailCarton as $row) {
            $idItem[] = $row['id_item'];
            $qtyPerCarton[] = $row['qty'];

            $dataItem = $itemModel->where('id_item', $row['id_item'])->first();
            $styleItem[] = $dataItem['style'];
            $colorItem[] = $dataItem['color'];
            $sizeItem[] = $dataItem['size'];
            $qtyOrder[] = $dataItem['qty'];

            $dataStockItem = $stockModel->where('id_item', $row['id_item'])->first();
            $qtyStock[] = $dataStockItem['qty'];
            $qtyCarton[] = $cartonDetailModel->getTotalQtyByIdItem($row['id_item']);
            $cekQtyPacking = $cartonDetailModel->sumQtyByItemId($row['id_item']);
            $qtyPacking[] = isset($cekQtyPacking['total_qty']) ? $cekQtyPacking['total_qty'] : 0;
        }

        $data = [
            'success' => true,
            'id_carton' => $idCarton,
            'nomor_carton' => $nomorCarton,
            'id_item' => $idItem,
            'style' => $styleItem,
            'color' => $colorItem,
            'size' => $sizeItem,
            'qty_total_carton' => $qtyTotalCarton,
            'qty_per_carton' => $qtyPerCarton,
            'qty_order' => $qtyOrder,
            'qty_carton' => $qtyCarton,
            'qty_stock' => $qtyStock,
            'qty_packing' => $qtyPacking
        ];

        return $this->response->setJSON($data);
    }
    public function scanCarton()
    {
        $request = $this->request->getJSON();
        $idCarton = $request->id_carton;

        $itemModel = new ItemModel();
        $stockModel = new StockItemModel();
        $cartonModel = new CartonModel();
        $cartonDetailModel = new CartonDetailModel();

        $dataCarton = $cartonModel->where('id_carton', $idCarton)->first();
        if (!$dataCarton) {
            $erormessage = 'Data carton dengan ID : ' . $idCarton . ' tidak ada';
            return $this->response->setJSON(['success' => false, 'message' => $erormessage]);
        }
        $nomorCarton = $dataCarton['nomor_carton'];
        $qtyTotalCarton = $dataCarton['qty_per_carton'];

        $dataDetailCarton = $cartonDetailModel->where('id_carton', $idCarton)->findAll();
        $idItem = [];
        $styleItem = [];
        $colorItem = [];
        $sizeItem = [];
        $qtyOrder = [];
        $qtyCarton = [];
        $qtyStock = [];
        $qtyPacking = [];
        $qtyPerCarton = [];

        foreach ($dataDetailCarton as $row) {
            $idItem[] = $row['id_item'];
            $qtyPerCarton[] = $row['qty'];

            $dataItem = $itemModel->where('id_item', $row['id_item'])->first();
            $styleItem[] = $dataItem['style'];
            $colorItem[] = $dataItem['color'];
            $sizeItem[] = $dataItem['size'];
            $qtyOrder[] = $dataItem['qty'];

            $dataStockItem = $stockModel->where('id_item', $row['id_item'])->first();
            $qtyStock[] = $dataStockItem['qty'];
            $qtyCarton[] = $cartonDetailModel->getTotalQtyByIdItem($row['id_item']);
            $cekQtyPacking = $cartonDetailModel->sumQtyByItemId($row['id_item']);
            $qtyPacking[] = isset($cekQtyPacking['total_qty']) ? $cekQtyPacking['total_qty'] : 0;
        }

        $data = [
            'success' => true,
            'id_carton' => $idCarton,
            'nomor_carton' => $nomorCarton,
            'id_item' => $idItem,
            'style' => $styleItem,
            'color' => $colorItem,
            'size' => $sizeItem,
            'qty_total_carton' => $qtyTotalCarton,
            'qty_per_carton' => $qtyPerCarton,
            'qty_order' => $qtyOrder,
            'qty_carton' => $qtyCarton,
            'qty_stock' => $qtyStock,
            'qty_packing' => $qtyPacking
        ];

        return $this->response->setJSON($data);
    }

    public function savePacking()
    {
        $request = service('request');
        $jsonData = $request->getJSON();

        $id_carton = $jsonData->id_carton;
        $qty_carton = $jsonData->qty_carton;

        $lastIdPacking = $this->db->table('tb_packing')
            ->select('MAX(id_packing) as last_id_packing')
            ->get()
            ->getRowArray();

        $lastIdPacking = $lastIdPacking['last_id_packing'];

        if ($lastIdPacking) {
            $idPacking = 'PK' . str_pad((intval(substr($lastIdPacking, 2)) + 1), 10, '0', STR_PAD_LEFT);
        } else {
            $idPacking = 'PK0000000001';
        }

        $dataToInsert = [
            'id_packing' => $idPacking,
            'id_carton' => $id_carton,
            'qty_carton' => $qty_carton,
        ];

        $packingModel = new PackingModel();
        $packingModel->insert($dataToInsert);

        $cartonModel2 = new CartonDetailModel();

        $dataToUpdate = ['status' => 1];
        $condition = ['id_carton' => $id_carton];
        $cartonModel2->update($condition, $dataToUpdate);

        return $this->response->setJSON([
            'id_packing' => $idPacking,
            'qty_carton' => $qty_carton
        ]);
    }
    // Scan Packing Doneee



    // Report Stock Item
    public function dataStockTabel()
    {
        $itemModel = new ItemModel();
        $stockModel = new StockItemModel();
        $stockDetailModel = new StockItemDetailModel();
        $cartonDetailModel = new CartonDetailModel();
        $formattedDate = date("Y-m-d");

        $dataItem = $itemModel->findAll();

        $data = [];

        foreach ($dataItem as $item) {
            $idItem = $item['id_item'];
            $dataStockItem = $stockModel->where('id_item', $idItem)->first();
            $dataPacking = $cartonDetailModel->sumQtyByItemId($idItem);
            $dataStockHarian = $stockDetailModel->where('id_item', $idItem)->where('date', $formattedDate)->first();

            $wip = $item['qty'] - (isset($dataStockItem['qty']) ? $dataStockItem['qty'] : 0);

            $wip_packing = (isset($dataStockItem['qty']) ? $dataStockItem['qty'] : 0) - (isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0);

            $data[] = [
                'id_item' => $idItem,
                'style' => $item['style'],
                'mo' => $item['mo'],
                'color' => $item['color'],
                'size' => $item['size'],
                'qty_order' => $item['qty'],
                'qty_stock' => isset($dataStockItem['qty']) ? $dataStockItem['qty'] : 0,
                'qty_packing' => isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0,
                'id_stock_today' => isset($dataStockHarian['id']) ? $dataStockHarian['id'] : 0,
                'qty_stock_today' => isset($dataStockHarian['qty']) ? $dataStockHarian['qty'] : 0,
                'wip' => $wip,
                'wip_packing' => $wip_packing,
                'date' => isset($dataStockItem['date']) ? $dataStockItem['date'] : null,
                'role' => session('userRole')
            ];
        }
        return $this->response->setJSON(['data' => $data]);
    }

    public function editStockIn()
    {
        $request = $this->request->getJSON();
        $stockModel = new StockItemModel();
        $stockDetailModel = new StockItemDetailModel();

        $data = [
            'qty' => $request->qty,
        ];
        
        $data2 = [
            'qty' => $request->qty_detail,
        ];

        $updated = $stockModel->update($request->id, $data);
        $updated2 = $stockDetailModel->update($request->id_detail, $data2);

        if ($updated && $updated2) {
            return $this->response->setJSON(['message' => 'Data changed successfully']);
        } else {
            return $this->response->setJSON(['message' => 'Data changed failed']);
        }
    }

    public function cekDataStock()
    {
        $request = $this->request->getJSON();
        $stockItemDetailModel = new StockItemDetailModel();
        $itemModel = new ItemModel();

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $stockData = $stockItemDetailModel->where('date >=', $dateFrom)
            ->where('date <=', $dateTo)
            ->findAll();

        $combinedData = [];

        foreach ($stockData as $stock) {
            $itemData = $itemModel->find($stock['id_item']);
            if ($itemData) {
                $combinedData[] = [
                    'id_item' => $stock['id_item'],
                    'qty' => $stock['qty'],
                    'date' => $stock['date'],
                    'style' => $itemData['style'],
                    'color' => $itemData['color'],
                    'size' => $itemData['size']
                ];
            }
        }

        return $this->response->setJSON(['success' => true, 'data' => $combinedData]);
    }

    public function exportDataStockHariIni()
    {
        $dateFrom = $this->request->getGet('dateFrom');
        $dateTo = $this->request->getGet('dateTo');
        $todayFormatted = date("d F Y", strtotime($dateFrom));
        $todayFormatted2 = date("d F Y", strtotime($dateTo));

        $itemModel = new ItemModel();
        $cartonDetailemModel = new CartonDetailModel();
        $stockItemModel = new StockItemModel();
        $stockItemDetailModel = new StockItemDetailModel();
        $dataStock = $stockItemDetailModel->where('date >=', $dateFrom)
            ->where('date <=', $dateTo)
            ->findAll();

        if ($dateFrom == $dateTo) {
            $title = "Data stok barang $todayFormatted";
            $filename = "Data_Stock_" . $todayFormatted . ".xlsx";
        } else {
            $title = "Data stok barang dari $todayFormatted sampai $todayFormatted2";
            $filename = "Data_Stock_" . $todayFormatted . "-" . $todayFormatted2 . ".xlsx";
        }

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('B2', $title);
        $activeWorksheet->setCellValue('B4', 'No');
        $activeWorksheet->setCellValue('C4', 'Id Item');
        $activeWorksheet->setCellValue('D4', 'Style');
        $activeWorksheet->setCellValue('E4', 'Color');
        $activeWorksheet->setCellValue('F4', 'Size');
        $activeWorksheet->setCellValue('G4', 'Qty Order');
        $activeWorksheet->setCellValue('H4', 'Qty Stock');
        $activeWorksheet->setCellValue('I4', 'Qty Stock Today');
        $activeWorksheet->setCellValue('J4', 'WIP');
        $activeWorksheet->setCellValue('K4', 'Qty Packing');
        $activeWorksheet->setCellValue('L4', 'WIP Packing');
        $activeWorksheet->setCellValue('M4', 'Date');

        $alignHeader = 'B2:M4';
        $stylee = $activeWorksheet->getStyle($alignHeader);
        $stylee->getAlignment()->setVertical("middle");
        $stylee->getAlignment()->setHorizontal("center");

        $activeWorksheet->mergeCells('B2:M3');

        $column = 5;
        foreach ($dataStock as $key => $value) {
            // Item Stock
            $activeWorksheet->setCellValue('B' . ($column), ($column - 4));
            $activeWorksheet->setCellValue('C' . ($column), $value['id_item']);
            $activeWorksheet->setCellValue('I' . ($column), $value['qty']);
            $activeWorksheet->setCellValue('M' . ($column), $value['date']);

            // Item Detail
            $dataItem = $itemModel->where('id_item', $value['id_item'])->first();
            $qtyTotalStock = $stockItemModel->where('id_item', $value['id_item'])->first();
            $dataPacking = $cartonDetailemModel->sumQtyByItemId($value['id_item']);
            $activeWorksheet->setCellValue('D' . ($column), $dataItem['style']);
            $activeWorksheet->setCellValue('E' . ($column), $dataItem['color']);
            $activeWorksheet->setCellValue('F' . ($column), $dataItem['size']);
            $activeWorksheet->setCellValue('G' . ($column), $dataItem['qty']);
            $activeWorksheet->setCellValue('H' . ($column), $qtyTotalStock['qty']);
            $activeWorksheet->setCellValue('J' . ($column), ($dataItem['qty'] - $qtyTotalStock['qty']));
            $activeWorksheet->setCellValue('K' . ($column), isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0);
            $activeWorksheet->setCellValue('L' . ($column), ($qtyTotalStock['qty'] - (isset($dataPacking['total_qty']) ? $dataPacking['total_qty'] : 0)));

            $columnsToCenter = ['B', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

            foreach ($columnsToCenter as $columnLetter) {
                $mergeRange = $columnLetter . $column . ':' . $columnLetter . $column;
                $style = $activeWorksheet->getStyle($mergeRange);
                $style->getAlignment()->setHorizontal("center");
            }

            $column++;
        }

        $activeWorksheet->getStyle('B2:M4')->getFont()->setBold(true);
        $activeWorksheet->getStyle('B2:M4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('00ffff');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $activeWorksheet->getStyle('B2:M' . ($column - 1))->applyFromArray($styleArray);

        $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('D')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('E')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('F')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('G')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('H')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('I')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('J')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('K')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('L')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('M')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachnebt;filename=' . $filename);
        header('cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    // Report Stock Item Doneee



    //Report Packing Tabel
    public function getDataReportPacking($data)
    {
        $itemModel = new PackingModel();

        $data['packing'] = $itemModel->findAll();


        return view('report_packing_tabel', $data);
    }

    public function exportDataPacking()
    {
        $status = $this->request->getGet('status');
        $packingModel = new PackingModel();
        $cartonModel = new CartonModel();
        $cartonDetailModel = new CartonDetailModel();
        $itemModel = new ItemModel();

        if ($status == 1) {
            $today = date("Y-m-d");
            $todayFormatted = date("d F Y", strtotime($today));
            $title = "Data Packing $todayFormatted";
            $dataPacking = $packingModel->where('date', $today)->findAll();
            $filename = "Data_Packing_" . $today . ".xlsx";
        } else {
            $title = "Data Packing";
            $dataPacking = json_decode($this->request->getGet('dataPacking'), true);
            $filename = "Data_Packing.xlsx";
        }


        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('B2', $title);
        $activeWorksheet->setCellValue('B4', 'No');
        $activeWorksheet->setCellValue('C4', 'Packing Detail');
        $activeWorksheet->setCellValue('C5', 'Id Packing');
        $activeWorksheet->setCellValue('D5', 'Date');
        $activeWorksheet->setCellValue('E4', 'Carton Detail');
        $activeWorksheet->setCellValue('E5', 'No Carton');
        $activeWorksheet->setCellValue('F5', 'Id Carton');
        $activeWorksheet->setCellValue('G5', 'Qty Carton');
        $activeWorksheet->setCellValue('H4', 'Item Detail');
        $activeWorksheet->setCellValue('H5', 'Id Item');
        $activeWorksheet->setCellValue('I5', 'Style Item');
        $activeWorksheet->setCellValue('J5', 'Color Item');
        $activeWorksheet->setCellValue('K5', 'Size Item');
        $activeWorksheet->setCellValue('L5', 'Qty Item');
        $activeWorksheet->setCellValue('M5', 'Qty Order');
        $activeWorksheet->setCellValue('N5', 'Qty Packing');
        $activeWorksheet->setCellValue('O5', 'WIP');

        $alignHeader = 'B2:O5';
        $stylee = $activeWorksheet->getStyle($alignHeader);
        $stylee->getAlignment()->setVertical("middle");
        $stylee->getAlignment()->setHorizontal("center");

        $activeWorksheet->mergeCells('B2:O3');
        $activeWorksheet->mergeCells('C4:D4');
        $activeWorksheet->mergeCells('E4:G4');
        $activeWorksheet->mergeCells('H4:O4');
        $activeWorksheet->mergeCells('B4:B5');

        $noUrut = 1;
        $column = 6;
        foreach ($dataPacking as $key => $value) {
            if ($status == 1){
                $detailPacking = $packingModel->where('id_packing', $value['id_packing'])->first();
            } else {
                $detailPacking = $packingModel->where('id_packing', $value[1])->first();
            }
            $dataDetailCarton = $cartonDetailModel->where('id_carton', $detailPacking['id_carton'])->findAll();

            $rowCount = 0;
            if (count($dataDetailCarton) > 1) {
                $activeWorksheet->mergeCells('B' . $column . ':B' . ($column + count($dataDetailCarton) - 1));
                $activeWorksheet->mergeCells('C' . $column . ':C' . ($column + count($dataDetailCarton) - 1));
                $activeWorksheet->mergeCells('D' . $column . ':D' . ($column + count($dataDetailCarton) - 1));
                $activeWorksheet->mergeCells('E' . $column . ':E' . ($column + count($dataDetailCarton) - 1));
                $activeWorksheet->mergeCells('F' . $column . ':F' . ($column + count($dataDetailCarton) - 1));
                $activeWorksheet->mergeCells('G' . $column . ':G' . ($column + count($dataDetailCarton) - 1));

                $mergeRange = 'B' . $column . ':G' . ($column + count($dataDetailCarton) - 1);

                $style = $activeWorksheet->getStyle($mergeRange);
                $style->getAlignment()->setVertical("middle");
            }

            foreach ($dataDetailCarton as $row) {
                // Packing Detail
                if ($row === reset($dataDetailCarton)) {
                    $activeWorksheet->setCellValue('B' . $column, $noUrut);
                    $activeWorksheet->setCellValue('C' . $column, $detailPacking['id_packing']);
                    $activeWorksheet->setCellValue('D' . $column, $detailPacking['date']);
                }

                // Carton Detail
                if ($row === reset($dataDetailCarton)) {
                    $dataCarton = $cartonModel->where('id_carton', $detailPacking['id_carton'])->first();
                    $activeWorksheet->setCellValue('E' . $column, $dataCarton['nomor_carton']);
                    $activeWorksheet->setCellValue('F' . $column, $detailPacking['id_carton']);
                    $activeWorksheet->setCellValue('G' . $column, $detailPacking['qty_carton']);
                }

                // Item Detail
                $dataItem = $itemModel->where('id_item', $row['id_item'])->first();
                $activeWorksheet->setCellValue('H' . ($column + $rowCount), $row['id_item']);
                $activeWorksheet->setCellValue('I' . ($column + $rowCount), $dataItem['style']);
                $activeWorksheet->setCellValue('J' . ($column + $rowCount), $dataItem['color']);
                $activeWorksheet->setCellValue('K' . ($column + $rowCount), $dataItem['size']);
                $activeWorksheet->setCellValue('L' . ($column + $rowCount), $row['qty']);
                $activeWorksheet->setCellValue('M' . ($column + $rowCount), $dataItem['qty']);

                $cekQtyPacking = $cartonDetailModel->sumQtyByItemId($row['id_item']);
                $qtyPacking = isset($cekQtyPacking['total_qty']) ? $cekQtyPacking['total_qty'] : 0;
                $activeWorksheet->setCellValue('N' . ($column + $rowCount), $qtyPacking);
                $activeWorksheet->setCellValue('O' . ($column + $rowCount), ($dataItem['qty'] - $qtyPacking));

                $rowCount++;
            }
            $mergeRage = 'C' . $column . ':O' . ($column + $rowCount);
            $style2 = $activeWorksheet->getStyle($mergeRage);
            $style2->getAlignment()->setHorizontal("left");

            $columnsToCenter = ['B', 'E', 'G', 'K', 'L', 'M', 'N', 'O'];

            foreach ($columnsToCenter as $columnLetter) {
                $mergeRange = $columnLetter . $column . ':' . $columnLetter . ($column + $rowCount - 1);
                $style = $activeWorksheet->getStyle($mergeRange);
                $style->getAlignment()->setHorizontal("center");
            }
            $column += $rowCount;
            $noUrut++;
        }

        $activeWorksheet->getStyle('B2:O5')->getFont()->setBold(true);
        $activeWorksheet->getStyle('B2:O5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('00ffff');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $activeWorksheet->getStyle('B2:O' . ($column - 1))->applyFromArray($styleArray);

        $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('D')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('E')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('F')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('G')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('H')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('I')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('J')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('K')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('L')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('M')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('N')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('O')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachnebt;filename=' . $filename);
        header('cache-Control: max-age=0');
        $writer->save('php://output');
        exit();

    }
    //Report Packing Tabel Doneee



    //Export Database
    public function exportDatabase()
    {
        date_default_timezone_set('Asia/Jakarta');

        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db = 'mingala';

        $dump = new Mysqldump("mysql:host=$host;dbname=$db", $user, $pass);

        $filename = 'backup_mingala_' . date('Y-m-d') . '.sql';
        $path = WRITEPATH . $filename;

        $dump->start($path);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);
        unlink($path);
    }

}