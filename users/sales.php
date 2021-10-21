<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id']==0)) {
  header('location:logout.php');
  } else{
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>POS User Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
      <?php include_once('includes/navbar.php');?>
        <div id="layoutSidenav">
          <?php include_once('includes/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                 <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <hr />
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                                                    
<form action="incoming.php" method="post" >
                                            
<input type="hidden" name="pt" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" name="invoice" value="<?php echo $_GET['invoice']; ?>" />
<select name="product" style="width:650px; "class="chzn-select" required>
<option></option>
    <?php
    include('../connect.php');
    $result = $db->prepare("SELECT * FROM products");
        $result->bindParam(':userid', $res);
        $result->execute();
        for($i=0; $row = $result->fetch(); $i++){
    ?>
        <option value="<?php echo $row['product_id'];?>"><?php echo $row['product_code']; ?> - <?php echo $row['gen_name']; ?> - <?php echo $row['product_name']; ?> | Expires at: <?php echo $row['expiry_date']; ?></option>
    <?php
                }
            ?>
</select>
<input type="number" name="qty" value="1" min="1" placeholder="Qty" autocomplete="off" style="width: 68px; height:30px; padding-top:6px; padding-bottom: 4px; margin-right: 4px; font-size:15px;" / required>
<input type="hidden" name="discount" value="" autocomplete="off" style="width: 68px; height:30px; padding-top:6px; padding-bottom: 4px; margin-right: 4px; font-size:15px;" />
<input type="hidden" name="date" value="<?php echo date("m/d/y"); ?>" />
<Button type="submit" class="btn btn-info" style="width: 123px; height:35px; margin-top:-5px;" /><i class="icon-plus-sign icon-large"></i> Add</button>
</form>
<table class="table table-bordered" id="resultTable" data-responsive="table">
    <thead>
        <tr>
            <th> Product Name </th>
            <th> Generic Name </th>
            <th> Category / Description </th>
            <th> Price </th>
            <th> Qty </th>
            <th> Amount </th>
            <th> Profit </th>
            <th> Action </th>
        </tr>
    </thead>
    <tbody>
        
            <?php
                include('../connect.php');
                $result = $db->prepare("SELECT * FROM sales_order WHERE invoice= :userid");
                $result->bindParam(':userid', $id);
                $result->execute();
                for($i=1; $row = $result->fetch(); $i++){
            ?>
            <tr class="record">
            <td hidden><?php echo $row['product']; ?></td>
            <td><?php echo $row['product_code']; ?></td>
            <td><?php echo $row['gen_name']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td>
            <?php
            $ppp=$row['price'];
            echo formatMoney($ppp, true);
            ?>
            </td>
            <td><?php echo $row['qty']; ?></td>
            <td>
            <?php
            $dfdf=$row['amount'];
            echo formatMoney($dfdf, true);
            ?>
            </td>
            <td>
            <?php
            $profit=$row['profit'];
            echo formatMoney($profit, true);
            ?>
            </td>
            <td width="90"><a href="../main/delete.php?id=<?php echo $row['transaction_id']; ?>&invoice=<?php echo $_GET['invoice']; ?>&dle=<?php echo $_GET['id']; ?>&qty=<?php echo $row['qty'];?>&code=<?php echo $row['product'];?>"><button class="btn btn-mini btn-warning"><i class="icon icon-remove"></i> Cancel </button></a></td>
            </tr>
            <?php
                }
            ?>
            <tr>
            <th> </th>
            <th>  </th>
            <th>  </th>
            <th>  </th>
            <th>  </th>
            <td> Total Amount: </td>
            <td> Total Profit: </td>
            <th>  </th>
        </tr>
            <tr>
                <th colspan="5"><strong style="font-size: 12px; color: #222222;">Total:</strong></th>
                <td colspan="1"><strong style="font-size: 12px; color: #222222;">
                <?php
                function formatMoney($number, $fractional=false) {
                    if ($fractional) {
                        $number = sprintf('%.2f', $number);
                    }
                    while (true) {
                        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
                        if ($replaced != $number) {
                            $number = $replaced;
                        } else {
                            break;
                        }
                    }
                    return $number;
                }
                $resultas = $db->prepare("SELECT sum(amount) FROM sales_order WHERE invoice= :a");
                $resultas->bindParam(':a', $sdsd);
                $resultas->execute();
                for($i=0; $rowas = $resultas->fetch(); $i++){
                $fgfg=$rowas['sum(amount)'];
                echo formatMoney($fgfg, true);
                }
                ?>
                </strong></td>
                <td colspan="1"><strong style="font-size: 12px; color: #222222;">
            <?php 
                $resulta = $db->prepare("SELECT sum(profit) FROM sales_order WHERE invoice= :b");
                $resulta->bindParam(':b', $sdsd);
                $resulta->execute();
                for($i=0; $qwe = $resulta->fetch(); $i++){
                $asd=$qwe['sum(profit)'];
                echo formatMoney($asd, true);
                }
                ?>
        
                </td>
                <th></th>
            </tr>
        
    </tbody>
</table>
<a rel="facebox" href="checkout.php?pt=<?php echo $_GET['id']?>&invoice=<?php echo $_GET['invoice']?>&total=<?php echo $fgfg ?>&totalprof=<?php echo $asd ?>&cashier=<?php echo $_SESSION['SESS_FIRST_NAME']?>"><button class="btn btn-success btn-large btn-block"><i class="icon icon-save icon-large"></i> SAVE</button></a>









     </div>
              
                        </div>
                   
                    </div>
                </main>
          <?php include('includes/footer.php');?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
<?php } ?>
