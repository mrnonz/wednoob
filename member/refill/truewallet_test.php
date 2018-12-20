<?php
//Show all error, remove it once you finished your code.
ini_set('display_errors', 1);
//Include TrueWallet class.
include ('class.truewallet.php');
$wallet = new TrueWallet('waltalkingjames@outlook.com','itemnoobstore90','email');
$token = json_decode($wallet->GetToken(),true)['data']['accessToken']; 
echo '</br>---------print_r($wallet)-----------</br>';
echo print_r($wallet);
echo '</br>-----------$token---------</br>';
echo $token;
// echo '</br>-----------json_decode($wallet->GetToken())---------</br>';
// echo print_r(json_decode($wallet->GetToken()));
echo '</br>-----------$wallet->Profile($token)---------</br>';
$getTran = json_decode($wallet->getTran($token,'2018-01-01','2019-01-01'));
echo print_r($wallet->Profile($token));
// echo $wallet;
// echo $wallet;
// echo print($wallet);
echo '</br>----------print_r($getTran)----------</br>';
echo print_r($getTran);

// foreach (json_decode($getTran,true)['data']['activities'] as $key => $value) {
//   echo '</br>--------'.$key.'------------</br>';
//   echo print_r($value);
// }

// 50001521390117
// echo json_decode($getTran,true)['529448086']['activities'];
$Tran = $getTran->data->activities;

echo '</br>----------- print_r($Tran)---------</br>';
echo '<pre>'; print_r($Tran); echo '</pre>';
echo '</br>----------end----------</br>';
foreach($Tran as $transaction){
  $tran_type = $transaction->text3En;
  

  $tran_type_want = "creditor";
  if($tran_type == $tran_type_want){
    
  
echo '</br>000000000000000000000000000000000000000</br>';
    $last_report = $transaction->reportID;
    $fti_u = "0";
    if($full_last_report = json_decode($wallet->CheckTran($token,$last_report))){
      $flr = $full_last_report->data;
      $fti = $flr->section4->column2->cell1->value;
      $ftam = $flr->amount;
      $ftm = $flr->personalMessage->value;
      $ftphone = $flr->ref1;
      $ftdate = $flr->section4->column1->cell1->value;
      echo $last_report ;
      echo '</br>===========</br>';
      echo print_r($ftam);
      echo '</br>===========</br>';
      echo print_r($ftphone);
      echo '</br>===========</br>';
      echo print_r($fti);
    }
    
echo '</br>000000000000000000000000000000000000000</br>';
  }
  
  
}


// echo $wallet->checkTran($token,'529448086');
?>
