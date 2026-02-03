<?php

$sql = "SELECT idFormaPago, nombreFormaPago FROM tb_formas_pago";
$result = mysqli_query($conection, $sql);

$formas_pago = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $formas_pago[] = $row;
    }
}

?>
