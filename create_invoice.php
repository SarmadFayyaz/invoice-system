<?php
ob_start();
session_start();
include('header.php');
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$items = $invoice->getItems();
$customers = $invoice->getCustomers();
if (!empty($_POST['companyName']) && $_POST['companyName']) {
   $invoice->saveInvoice($_POST);
   header("Location:invoice_list.php");
}
?>
<title>Invoice System | Create Invoice </title>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php'); ?>
<div class="container content-invoice">
   <div class="cards">
      <div class="card-body">
         <form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="">
            <div class="load-animate animated fadeInUp">
               <div class="row">
                  <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                     <h2 class="title">PHP Invoice System</h2>
                     <?php include('menu.php'); ?>
                  </div>
               </div>
               <input id="currency" type="hidden" value="$">
               <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                     <h3>From,</h3>
                     <small> Name: </small><?php echo $_SESSION['user']; ?><br>
                     <small> Address: </small><?php echo $_SESSION['address']; ?><br>
                     <small> Mobile: </small><?php echo $_SESSION['mobile']; ?><br>
                     <small> Email: </small><?php echo $_SESSION['email']; ?><br>
                     <small> NTN: </small><?php echo $_SESSION['ntn']; ?><br>
                     <small> STRN: </small><?php echo $_SESSION['strn']; ?><br>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                     <h3>To,</h3>
                     <div class="form-group">
                        <input list="customer" class="form-control companyName" name="companyName" id="companyName" placeholder="Company Name" autocomplete="off">
                        <datalist id="customer">
                           <?php foreach ($customers as $customer) {
                              echo ('<option value="' . $customer['name'] . '" data-address="' . $customer['address'] . '" data-ntn="' . $customer['ntn'] . '" data-strn="' . $customer['strn'] . '">');
                           } ?>
                        </datalist>
                     </div>
                     <div class="form-group">
                        <textarea class="form-control address" rows="2" name="address" id="address" placeholder="Your Address"></textarea>
                     </div>
                     <div class="row">
                        <div class="col">
                           <div class="form-group">
                              <input type="text" class="form-control ntn" name="ntn" id="ntn" placeholder="NTN" autocomplete="off">
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <input type="text" class="form-control strn" name="strn" id="strn" placeholder="STRN" autocomplete="off">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row justify-content-center">
                  <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
                     <div class="form-group">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title" autocomplete="off">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                     <table class="table table-condensed table-striped" id="invoiceItem">
                        <tr>
                           <th width="2%">
                              <div class="custom-control custom-checkbox mb-3">
                                 <input type="checkbox" class="custom-control-input" id="checkAll" name="checkAll">
                                 <label class="custom-control-label" for="checkAll"></label>
                              </div>
                           </th>
                           <th width="15%">Item No</th>
                           <th width="38%">Item Name</th>
                           <th width="15%">Quantity</th>
                           <th width="15%">Price Per Item</th>
                           <th width="15%">Total</th>
                        </tr>
                        <tr>
                           <td>
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="itemRow custom-control-input" id="itemRow_1">
                                 <label class="custom-control-label" for="itemRow_1"></label>
                              </div>
                           </td>
                           <td><input type="text" readonly name="productCode[]" id="productCode_1" class="form-control item_id" autocomplete="off"></td>
                           <td>
                              <input list="item_list_1" name="productName[]" id="productName_1" data-id="1" class="form-control item_name" autocomplete="off">
                              <datalist id="item_list_1">
                                 <?php foreach ($items as $item) {
                                    echo ('<option value="' . $item['name'] . '" data-id="' . $item['id'] . '">');
                                 } ?>
                              </datalist>
                           </td>
                           <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off"></td>
                           <td><input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off"></td>
                           <td><input type="number" name="total[]" id="total_1" class="form-control total" autocomplete="off" readonly></td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
                     <button class="btn btn-success" id="addRows" type="button">+ Add More</button>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Subtotal: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">$</span>
                           </div>
                           <input value="" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="Subtotal">
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Tax Rate: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">%</span>
                           </div>
                           <input value="" type="number" class="form-control" name="taxRate" id="taxRate" placeholder="Tax Rate">
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Tax Amount: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">$</span>
                           </div>
                           <input value="" type="number" class="form-control" name="taxAmount" id="taxAmount" placeholder="Tax Amount" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Total: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">$</span>
                           </div>
                           <input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Amount Paid: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">$</span>
                           </div>
                           <input value="" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="Amount Paid">
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                     <div class="form-group mt-3 mb-3 ">
                        <label>Amount Due: &nbsp;</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text currency">$</span>
                           </div>
                           <input value="" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Amount Due" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                     <h3>Notes: </h3>
                     <div class="form-group">
                        <textarea class="form-control txt" rows="5" name="notes" id="notes" placeholder="Your Notes"></textarea>
                     </div>
                     <br>
                     <div class="form-group">
                        <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
                        <input data-loading-text="Saving Invoice..." type="submit" name="invoice_btn" value="Save Invoice" class="btn btn-success submit_btn invoice-save-btm">
                     </div>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
         </form>
      </div>
   </div>
</div>
</div>
<?php include('footer.php'); ?>