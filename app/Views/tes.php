<?php $carton = array_reverse($carton); ?>
                        <?php $counter = 1; ?>
                        <?php include "phpqrcode/qrlib.php";
                        $penyimpanan = "cartonQr/";

                        if (!file_exists($penyimpanan))
                            mkdir($penyimpanan); ?>
                        <?php foreach ($carton as $carton): ?>
                            <?php
                            $idCarton = $carton['id_carton'];
                            $cobaCekModel = new \App\Models\CartonDetailModel();
                            $dataCobaCek = $cobaCekModel->where('id_carton', $idCarton)->findAll();

                            $idCarton = $carton['id_carton'];
                            QRcode::png($idCarton, $penyimpanan . $idCarton . ".png");

                            $idItems = [];
                            $qtys = [];
                            $iD = [];
                            foreach ($dataCobaCek as $item) {
                                $idItems[] = $item['id_item'];
                                $qtys[] = $item['qty'];
                                $iD[] = $item['id'];
                            }

                            $statusColor = 'red';
                            $statusText = 'Unfinished';
                            $packingModel = new \App\Models\PackingModel();
                            $cartonStatus = $packingModel->checkCartonStatus($idCarton);
                            if ($cartonStatus) {
                                $statusColor = 'green';
                                $statusText = 'Finished';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= $counter; ?>
                                </td>
                                <td>
                                    <img src="<?= base_url() ?>/cartonQr/<?= $carton['id_carton']; ?>.png" alt="QR Code">
                                </td>
                                <td>
                                    <?= $carton['id_carton']; ?>
                                </td>
                                <td>
                                    <?= $carton['nomor_carton']; ?>
                                </td>
                                <td>
                                    <?= $carton['qty_per_carton']; ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        echo "{$idItems[$i]}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $style = $item2['style'];
                                        }
                                        echo "{$style}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $color = $item2['color'];
                                        }
                                        echo "{$color}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        $cekItem = new \App\Models\ItemModel();
                                        $dataCekItem = $cekItem->where('id_item', $idItems[$i])->findAll();
                                        foreach ($dataCekItem as $item2) {
                                            $size = $item2['size'];
                                        }
                                        echo "{$size}<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    for ($i = 0; $i < count($idItems); $i++) {
                                        echo "{$qtys[$i]}<br>";
                                    }
                                    ?>
                                </td>
                                <td style='padding: 5px;'>
                                    <span
                                        style='background-color: <?= $statusColor; ?>; color: white; padding: 3px 10px; border-radius: 5px;'>
                                        <?= $statusText; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-link font-24 " data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <?php for ($i = 0; $i < count($idItems); $i++): ?>
                                                <a class="dropdown-item edit-button" data-id="<?= $idItems[$i] ?>"
                                                    data-qty="<?= $qtys[$i] ?>" data-qty_car="<?= $carton['qty_per_carton'] ?>"
                                                    data-id_carton="<?= $carton['id_carton'] ?>" data-id_key="<?= $iD[$i] ?>">
                                                    <i class='fas fa-edit'></i> Edit
                                                    <?= $idItems[$i] ?>
                                                </a>
                                            <?php endfor; ?>
                                            <a class="dropdown-item delete-button" data-id="<?= $carton['id_carton'] ?>"
                                                data-status="<?= $statusText; ?>"
                                                data-role="<?php echo session('userRole'); ?>" data-toggle="modal"
                                                data-target="#confirmation-modal">
                                                <i class='fas fa-trash-alt'></i> Delete
                                            </a>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach; ?>