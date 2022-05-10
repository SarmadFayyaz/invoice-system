<?php
ob_start();
session_start();
include('header.php');
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>
<title>Invoice System | Quotation List</title>
<script src="js/quotation.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php'); ?>
<div class="container">
  <h2 class="title mt-5">PHP Invoice System</h2>
  <?php include('menu.php'); ?>
  <table id="data-table" class="table table-condensed table-striped">
    <thead>
      <tr>
        <th>Quotation No.</th>
        <th>Create Date</th>
        <th>Customer Name</th>
        <th>Quotation Total</th>
        <th>Print</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <?php
    $invoiceList = $invoice->getQuotationList();
    foreach ($invoiceList as $invoiceDetails) {
      $invoiceDate = date("d/M/Y, H:i:s", strtotime($invoiceDetails["order_date"]));
      echo '
        <tr>
        <td>' . $invoiceDetails["order_id"] . '</td>
        <td>' . $invoiceDate . '</td>
        <td>' . $invoiceDetails["name"] . '</td>
        <td>' . $invoiceDetails["order_total_after_tax"] . '</td>
        <td><a href="print_quotation.php?quotation_id=' . $invoiceDetails["order_id"] . '" target="_blank" title="Print Quotation"><i class="fas fa-print"></i></a></td>
        <td><a href="edit_quotation.php?update_id=' . $invoiceDetails["order_id"] . '"  title="Edit Quotation"><i class="fas fa-edit"></i></a></td>
        <td><a href="#" id="' . $invoiceDetails["order_id"] . '" class="deleteQuotation"  title="Delete Quotation"><i class="fas fa-trash"></i></a></td>
        </tr>
        ';
    }
    ?>
  </table>
</div>
<?php include('footer.php'); ?>