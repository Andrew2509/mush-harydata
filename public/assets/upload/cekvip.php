<?php 
require '../config/koneksi.php';
$sql_5 = mysqli_query($conn,"SELECT * FROM `tb_tripayapi` WHERE cuid = 4") or die(mysqli_error());
				$s5 = mysqli_fetch_array($sql_5);
				$merchantCodes = $s5['merchant_code'];
				$apiKey = $s5['api_key'];
$signe = $merchantCodes.$apiKey;
        $sign = md5($signe);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://vip-reseller.co.id/api/profile',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
              'key' => $apiKey,
              'sign' => $sign
              
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
 ?>