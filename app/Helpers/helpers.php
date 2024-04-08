<?php

use App\Models\Transaction;
use App\Models\User;

function convert_date($date) {
    return date("j F Y, H:i:s", strtotime($date));
}

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka, 0, ".", ".");
	return $hasil_rupiah;
}

function cekRolePermission(){
    $user = User::with('roles')->get();
    return $user;
}