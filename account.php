<?php
ob_start();
session_start();
include('header.php');
include 'Invoice.php';
$invoice = new Invoice();
$user = $invoice->getUser();
$_SESSION['user'] = $user['first_name'] . " " . $user['last_name'];
$_SESSION['userid'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['address'] = $user['address'];
$_SESSION['mobile'] = $user['mobile'];
$_SESSION['ntn'] = $user['ntn'];
$_SESSION['strn'] = $user['strn'];
$_SESSION['file_path'] = $user['file_path'];
$invoice->checkLoggedIn();
if (!empty($_POST['userId']) && $_POST['userId']) {
	$invoice->updateUser($_POST);
	header("Location:account.php");
}
?>
<title>Invoice System | Edit Invoice</title>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php'); ?>
<div class="container content-invoice">
	<div class="cards">
		<div class="card-body">
			<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="" enctype="multipart/form-data">
				<input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
				<div class="load-animate animated fadeInUp">
					<div class="row">
						<div class="col-xs-12">
							<h1 class="title">PHP Invoice System</h1>
							<?php include('menu.php'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>First Name: &nbsp;</label>
								<input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" autocomplete="off" value="<?php echo $user['first_name']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Last Name: &nbsp;</label>
								<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" autocomplete="off" value="<?php echo $user['last_name']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Email: &nbsp;</label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off" value="<?php echo $user['email']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Password: &nbsp;</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" value="">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Mobile: &nbsp;</label>
								<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" autocomplete="off" value="<?php echo $user['mobile']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Address: &nbsp;</label>
								<input type="text" class="form-control" name="address" id="address" placeholder="Address" autocomplete="off" value="<?php echo $user['address']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>NTN: &nbsp;</label>
								<input type="text" class="form-control" name="ntn" id="ntn" placeholder="NTN" autocomplete="off" value="<?php echo $user['ntn']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>STRN: &nbsp;</label>
								<input type="text" class="form-control" name="strn" id="strn" placeholder="STRN" autocomplete="off" value="<?php echo $user['strn']; ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<div class="form-group">
								<label>Signature: &nbsp;</label>
								<input type="file" class="form-control" name="signature" id="signature">
							</div>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<input data-loading-text="Updating Invoice..." type="submit" name="invoice_btn" value="Update Invoice" class="btn btn-success submit_btn invoice-save-btm">
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