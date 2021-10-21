<?php  
$conn = new mysqli('localhost', 'root', '');  
mysqli_select_db($conn, 'sales');  
$sql = "SELECT `transaction_id`,`date`,`name`,`invoice_number`, `amount`, `profit` FROM `sales`";  
$setRec = mysqli_query($conn, $sql);  
$columnHeader = '';  
$columnHeader = "Transaction ID" . "\t" . "Transaction Date" . "\t" . "Customer Name" . "\t" . "Invoice Number" . "\t" . "Amount" . "\t". "Profit" . "\t";  
$setData = '';  
  while ($rec = mysqli_fetch_row($setRec)) {  
    $rowData = '';  
    foreach ($rec as $value) {  
        $value = '"' . $value . '"' . "\t";  
        $rowData .= $value;  
    }  
    $setData .= trim($rowData) . "\n";  
}  
  
header("Content-type: application/octet-stream");  
header("Content-Disposition: attachment; filename=Sales_Report.xls");  
header("Pragma: no-cache");  
header("Expires: 0");  

  echo ucwords($columnHeader) . "\n" . $setData . "\n";  
 ?> 