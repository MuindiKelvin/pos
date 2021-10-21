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


<div style="margin-top: -19px; margin-bottom: 21px;">
<a  href="index.php"><button class="btn btn-default btn-large" style="float: left;"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
            <?php 
            include('../connect.php');
                $result = $db->prepare("SELECT * FROM products ORDER BY qty_sold DESC");
                $result->execute();
                $rowcount = $result->rowcount();
            ?>
            
            <?php 
            include('../connect.php');
                $result = $db->prepare("SELECT * FROM products where qty < 10 ORDER BY product_id DESC");
                $result->execute();
                $rowcount123 = $result->rowcount();

            ?>
                <div style="text-align:center;">
            Total Number of Products:  <font color="green" style="font:bold 22px 'Aleo';">[<?php echo $rowcount;?>]</font>
            </div>
            
            <div style="text-align:center;">
            <font style="color:rgb(255, 95, 66);; font:bold 22px 'Aleo';">[<?php echo $rowcount123;?>]</font> Products are below QTY of 10 
            </div>
</div>


<input type="text" style="padding:15px;" name="filter" value="" id="filter" placeholder="Search Product..." autocomplete="off" />
<a rel="facebox" href="addproduct.php"><Button type="submit" class="btn btn-info" style="float:right; width:230px; height:35px;" /><i class="icon-plus-sign icon-large"></i> Add Product</button></a><br><br>
<table class="hoverTable" id="resultTable" data-responsive="table" style="text-align: left;">
    <thead>
        <tr>
            <th width="12%"> Brand Name </th>
            <th width="14%"> Generic Name </th>
            <th width="13%"> Category / Description </th>
            <th width="7%"> Supplier </th>
            <th width="9%"> Date Received </th>
            <th width="10%"> Expiry Date </th>
            <th width="8%"> Original Price </th>
            <th width="8%"> Selling Price </th>
            <th width="6%"> QTY </th>
            <th width="5%"> Qty Left </th>
            <th width="8%"> Total </th>
            <!--<th width="8%"> Action </th>-->
        </tr>
    </thead>
    <tbody>
        
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
                include('../connect.php');
                $result = $db->prepare("SELECT *, price * qty as total FROM products ORDER BY product_id ASC");
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){
                $total=$row['total'];
                $availableqty=$row['qty'];
                if ($availableqty < 10) {
                echo '<tr class="alert alert-warning record" style="color: #fff; background:rgb(255, 95, 66);">';
                }
                else {
                echo '<tr class="record">';
                }
            ?>
        

            <td><?php echo $row['product_code']; ?></td>
            <td><?php echo $row['gen_name']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['supplier']; ?></td>
            <td><?php echo $row['date_arrival']; ?></td>
            <td><?php echo $row['expiry_date']; ?></td>
            <td><?php
            $oprice=$row['o_price'];
            echo formatMoney($oprice, true);
            ?></td>
            <td><?php
            $pprice=$row['price'];
            echo formatMoney($pprice, true);
            ?></td>
            <td><?php echo $row['qty_sold']; ?></td>
            <td><?php echo $row['qty']; ?></td>
            <td>
            <?php
            $total=$row['total'];
            echo formatMoney($total, true);
            ?>
            </td>           <!--<td><a rel="facebox" title="Click to edit the product" href="editproduct.php?id=<?php echo $row['product_id']; ?>"><button class="btn btn-warning"><i class="icon-edit"></i> </button> </a>
            <a href="#" id="<?php echo $row['product_id']; ?>" class="delbutton" title="Click to Delete the product"><button class="btn btn-danger"><i class="icon-trash"></i></button></a></td>-->
            </tr>
            <?php
                }
            ?>
        
        
        
    </tbody>
</table>
<div class="clearfix"></div>
</div>
</div>
</div>

<script src="js/jquery.js"></script>
  <script type="text/javascript">
$(function() {


$(".delbutton").click(function(){

//Save the link in a variable called element
var element = $(this);

//Find the id of the link that was clicked
var del_id = element.attr("id");

//Built a url to send
var info = 'id=' + del_id;
 if(confirm("Sure you want to delete this Product? There is NO undo!"))
          {

 $.ajax({
   type: "GET",
   url: "deleteproduct.php",
   data: info,
   success: function(){
   
   }
 });
         $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
        .animate({ opacity: "hide" }, "slow");

 }

return false;

});

});
</script>






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
