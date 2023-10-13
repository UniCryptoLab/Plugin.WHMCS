<?php
require_once __DIR__ . '/../../../init.php';
require_once(dirname(__FILE__) . '/vendor/autoload.php');


App::load_function('gateway');
App::load_function('invoice');
$gatewayParams = getGatewayVariables('unipayment');

$client_id = $gatewayParams['client_id'];
$app_id = $gatewayParams['app_id'];
$client_secret = $gatewayParams['client_secret'];
$processing_status = $gatewayParams['processing_status'];

$notify = file_get_contents("php://input");
$notifyar= json_decode($notify, true) ;


$client = new \UniPayment\Client\UniPaymentClient();
$client->getConfig()->setClientId($client_id);
$client->getConfig()->setClientSecret($client_secret);
$response = $client->checkIpn($notify);

$invoiceid = $notifyar['order_id'];
$status = $notifyar['status'];

checkCbInvoiceID($invoiceid, $gatewayParams['paymentmethod']);


if ($response['code'] == 'OK') {
	logTransaction($gatewayParams['name'], $notifyar, $status);
	if ($processing_status == $status){
		addInvoicePayment($invoiceid, $notifyar['invoice_id'], $notifyar['price_amount'], '', $gatewayParams['paymentmethod']);
	}		
	
	echo "SUCCESS";
}
else {
	echo "Fail";	
}

exit;



