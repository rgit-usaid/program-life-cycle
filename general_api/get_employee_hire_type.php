<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## array for direct hire employee type
$data['direct']['GS'] = 'GS';
$data['direct']['FSO'] = 'FSO';
$data['direct']['FSL'] = 'FSL';
$data['direct']['FE'] = 'FE';
$data['direct']['FP'] = 'FP';
$data['direct']['FO'] = 'FO';
$data['direct']['DLI'] = 'DLI';
$data['direct']['RASA/PASA'] = 'RASA/PASA';
$data['direct']['AD'] = 'AD';

## array for non direct hire employee type 
$data['non_direct']['PIO'] = 'PIO';
$data['non_direct']['BREN'] = 'BREN';
$data['non_direct']['IPA'] = 'IPA';
$data['non_direct']['ICS'] = 'ICS';
$data['non_direct']['FSN-PSC'] = 'FSN-PSC';
$data['non_direct']['FSN-DH'] = 'FSN-DH';
$data['non_direct']['WIDF'] = 'WIDF';
$data['non_direct']['WCPL'] = 'WCPL';
$data['non_direct']['URBF'] = 'URBF';
$data['non_direct']['STRF'] = 'STRF';
$data['non_direct']['PRBF'] = 'PRBF';
$data['non_direct']['PPOF'] = 'PPOF';
$data['non_direct']['GHF'] = 'GHF';
$data['non_direct']['EDUF'] = 'EDUF';
$data['non_direct']['DEMF'] = 'DEMF';
$data['non_direct']['AAAS'] = 'AAAS';
$data['non_direct']['JFF'] = 'JFF';
$data['non_direct']['MANS'] = 'MANS';
$data['non_direct']['FRAN'] = 'FRAN';
$data['non_direct']['CASU'] = 'CASU';
$data['non_direct']['RSSA'] = 'RSSA';
$data['non_direct']['PAPA'] = 'PAPA';
$data['non_direct']['OTH'] = 'OTH';
$data['non_direct']['TAAC'] = 'TAAC';
$data['non_direct']['SDMT'] = 'SDMT';
$data['non_direct']['TCN'] = 'TCN';
$data['non_direct']['HCSF'] = 'HCSF';
$data['non_direct']['PASA'] = 'PASA';
deliverResponse(200,'Record Found',$data);     
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>