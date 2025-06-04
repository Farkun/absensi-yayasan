<?php
function hitunghari($tanggal_mulai, $tanggal_akhir)
{
    $tanggal_1 = date_create($tanggal_mulai);
    $tanggal_2 = date_create($tanggal_akhir); // waktu sekarang
    $diff = date_diff($tanggal_1, $tanggal_2);
    return $diff->days + 1;

}