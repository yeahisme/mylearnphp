<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>分期付款</title>
</head>

<body>
<?php
function amortizationTable($paymentNum,$periodicPayment,$balance,$monthlyInterest){
	$paymentInterest = round($balance * $monthlyInterest,2);
	$paymentPrincipal = round($periodicPayment - $paymentInterest,2);
	$newBalance = round($balance - $paymentPrincipal,2);
	print "<tr>
		   <td>$paymentNum</td>
		   <td>\$" . number_format($balance,2) . "</td>
		   <td>\$" . number_format($periodicPayment,2) . "</td>
		   <td>\$" . number_format($paymentInterest,2) . "</td>
		   <td>\$" . number_format($paymentPrincipal,2) . "</td>
		   </tr>" ;
	if($newBalance > 0){
		$paymentNum++;
		amortizationTable($paymentNum,$periodicPayment,$newBalance,$monthlyInterest);
	}else{
		exit;
	}
}
$balance = 10000;
$interestRate = .0549;
$monthlyInterset = .0549/12;
$termLength = 2;
$paymentsPerYear = 12;
$paymentNumber = 1;
$totalPayments = $termLength * $paymentsPerYear;
$intCalc = 1 + $interestRate / $paymentsPerYear;
$periodicPayment = $balance * pow($intCalc,$totalPayments) * ($intCalc - 1)/(pow($intCalc,$totalPayments) - 1);
$periodicPayment = round($periodicPayment,2);
echo "<table width='50%' align='center' border='1'";
print "<tr>
	   <tr>
	   <th>Payment Number</th><th>Balance</th>
	   <th>Payment</th><th>Interest</th><th>Principal</th>
	   </tr>";
amortizationTable($paymentNumber,$periodicPayment,$balance,$monthlyInterset);
print "</table>";

?>
</body>
</html>
