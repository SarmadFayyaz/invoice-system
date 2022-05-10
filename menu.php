<ul class="nav navbar-nav flex-row">
	<li class="dropdown">
		<button class="btn btn-primary dropdown-toggle border-0 text-capitalize" type="button" data-toggle="dropdown"><?php echo (str_replace('_', ' ', basename($_SERVER['PHP_SELF'], '.php'))) ?>
			<span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a class="dropdown-item" href="invoice_list.php">Invoice List</a></li>
			<li><a class="dropdown-item" href="create_invoice.php">Create Invoice</a></li>
			<li><a class="dropdown-item" href="quotation_list.php">Quotation List</a></li>
			<li><a class="dropdown-item" href="create_quotation.php">Create Quotation</a></li>
		</ul>
	</li>
	<?php
	if ($_SESSION['userid']) { ?>
		<li class="dropdown">
			<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Logged in <?php echo $_SESSION['user']; ?>
				<span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="account.php">Account</a></li>
				<li><a class="dropdown-item" href="action.php?action=logout">Logout</a></li>
			</ul>
		</li>
	<?php } ?>
</ul>
<br /><br /><br /><br />