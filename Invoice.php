<?php
class Invoice
{
	private $host  = 'localhost';
	private $user  = 'marveics_bts';
	private $password   = "marveics_bts";
	private $database  = "marveics_btsengineering";
	private $invoiceUserTable = 'invoice_user';
	private $customerTable = 'customer';
	private $invoiceOrderTable = 'invoice_order';
	private $quotationOrderTable = 'quotation_order';
	private $itemTable = 'item';
	private $invoiceOrderItemTable = 'invoice_order_item';
	private $quotationOrderItemTable = 'quotation_order_item';
	private $dbConnect = false;
	public function __construct()
	{
		if (!$this->dbConnect) {
			$conn = new mysqli($this->host, $this->user, $this->password, $this->database);
			if ($conn->connect_error) {
				die("Error failed to connect to MySQL: " . $conn->connect_error);
			} else {
				$this->dbConnect = $conn;
			}
		}
	}
	private function getData($sqlQuery)
	{
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			die('Error in query: ' . mysqli_error($this->dbConnect));
		}
		$data = array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[] = $row;
		}
		return $data;
	}
	private function getNumRows($sqlQuery)
	{
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			die('Error in query: ' . mysqli_error($this->dbConnect));
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function loginUsers($email, $password)
	{
		$sqlQuery = "
			SELECT id, email, first_name, last_name, address, mobile, ntn, strn, file_path
			FROM " . $this->invoiceUserTable . " 
			WHERE email='" . $email . "' AND password='" . $password . "'";
		return  $this->getData($sqlQuery);
	}
	public function checkLoggedIn()
	{
		if (!$_SESSION['userid']) {
			header("Location:index.php");
		}
	}
	public function saveInvoice($POST)
	{
		$customer_id = 0;
		if (!$this->checkCustomer($POST['companyName'])) {
			$setItem = "INSERT INTO " . $this->customerTable . "(name, address, ntn, strn) VALUES ('" . $POST['companyName'] . "','" . $POST['address'] . "','" . $POST['ntn'] . "','" . $POST['strn'] . "')";
			mysqli_query($this->dbConnect, $setItem);

			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
		} else {
			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];

			$sqlInsert = "UPDATE " . $this->customerTable . "', name = '" . $POST['companyName'] . "', address = '" . $POST['address'] . "', ntn = '" . $POST['ntn'] . "', strn = '" . $POST['strn'] . "' 
				WHERE customer_id = '" . $customer_id . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		// $sqlInsert = "INSERT INTO " . $this->invoiceOrderTable . "(user_id, order_receiver_name, order_receiver_address, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, note) VALUES ('" . $POST['userId'] . "', '" . $POST['companyName'] . "', '" . $POST['address'] . "', '" . $POST['subTotal'] . "', '" . $POST['taxAmount'] . "', '" . $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['notes'] . "')";
		$sqlInsert = "INSERT INTO " . $this->invoiceOrderTable . "(user_id, customer_id, title, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, note) VALUES ('" . $POST['userId'] . "', '" . $customer_id . "', '" . $POST['title'] . "', '" . $POST['subTotal'] . "', '" . $POST['taxAmount'] . "', '" . $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['notes'] . "')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
		for ($i = 0; $i < count($POST['productName']); $i++) {
			$item_id = 0;
			if (!$this->checkItem($POST['productName'][$i])) {
				$setItem = "INSERT INTO " . $this->itemTable . "(name) VALUES ('" . $POST['productName'][$i] . "')";
				mysqli_query($this->dbConnect, $setItem);

				$item = $this->getItem($POST['productName'][$i]);
				$item_id = $item[0]['id'];
			}

			$sqlInsertItem = "INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) VALUES ('" . $lastInsertId . "', '" . (($item_id != 0) ? $item_id : $POST['productCode'][$i]) . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
	}
	public function saveQuotation($POST)
	{
		$customer_id = 0;
		if (!$this->checkCustomer($POST['companyName'])) {
			$setItem = "INSERT INTO " . $this->customerTable . "(name, address, ntn, strn) VALUES ('" . $POST['companyName'] . "','" . $POST['address'] . "','" . $POST['ntn'] . "','" . $POST['strn'] . "')";
			mysqli_query($this->dbConnect, $setItem);

			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
		} else {
			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];

			$sqlInsert = "UPDATE " . $this->customerTable . "', name = '" . $POST['companyName'] . "', address = '" . $POST['address'] . "', ntn = '" . $POST['ntn'] . "', strn = '" . $POST['strn'] . "' 
				WHERE customer_id = '" . $customer_id . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		// $sqlInsert = "INSERT INTO " . $this->quotationOrderTable . "(user_id, order_receiver_name, order_receiver_address, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, note) VALUES ('" . $POST['userId'] . "', '" . $POST['companyName'] . "', '" . $POST['address'] . "', '" . $POST['subTotal'] . "', '" . $POST['taxAmount'] . "', '" . $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['notes'] . "')";
		$sqlInsert = "INSERT INTO " . $this->quotationOrderTable . "(user_id, customer_id, title, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, note) VALUES ('" . $POST['userId'] . "', '" . $customer_id . "', '" . $POST['title'] . "', '" . $POST['subTotal'] . "', '" . $POST['taxAmount'] . "', '" . $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['notes'] . "')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
		for ($i = 0; $i < count($POST['productName']); $i++) {
			$item_id = 0;
			if (!$this->checkItem($POST['productName'][$i])) {
				$setItem = "INSERT INTO " . $this->itemTable . "(name) VALUES ('" . $POST['productName'][$i] . "')";
				mysqli_query($this->dbConnect, $setItem);

				$item = $this->getItem($POST['productName'][$i]);
				$item_id = $item[0]['id'];
			}

			$sqlInsertItem = "INSERT INTO " . $this->quotationOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) VALUES ('" . $lastInsertId . "', '" . (($item_id != 0) ? $item_id : $POST['productCode'][$i]) . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
	}
	public function updateInvoice($POST)
	{
		$customer_id = 0;
		if (!$this->checkCustomer($POST['companyName'])) {
			// print_r(111111);
			$setItem = "INSERT INTO " . $this->customerTable . "(name, address, ntn, strn) VALUES ('" . $POST['companyName'] . "','" . $POST['address'] . "','" . $POST['ntn'] . "','" . $POST['strn'] . "')";
			mysqli_query($this->dbConnect, $setItem);

			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
		} else {
			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
			$sqlInsert = "UPDATE " . $this->customerTable . " SET " .
				" name = '" . $POST['companyName'] . "', address= '" . $POST['address'] . "', ntn = '" . $POST['ntn'] . "', strn = '" . $POST['strn'] . "' 
				WHERE customer_id = '" . $customer_id . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		if ($POST['invoiceId']) {
			$sqlInsert = "UPDATE " . $this->invoiceOrderTable . " SET " .
				" customer_id = '" . $customer_id . "', title= '" . $POST['title'] . "', order_total_before_tax = '" . $POST['subTotal'] . "', order_total_tax = '" . $POST['taxAmount'] . "', order_tax_per = '" . $POST['taxRate'] . "', order_total_after_tax = '" . $POST['totalAftertax'] . "', order_amount_paid = '" . $POST['amountPaid'] . "', order_total_amount_due = '" . $POST['amountDue'] . "', note = '" . $POST['notes'] . "' 
				WHERE user_id = '" . $POST['userId'] . "' AND order_id = '" . $POST['invoiceId'] . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		$this->deleteInvoiceItems($POST['invoiceId']);
		for ($i = 0; $i < count($POST['productName']); $i++) {
			$item_id = 0;
			if (!$this->checkItem($POST['productName'][$i])) {
				$setItem = "INSERT INTO " . $this->itemTable . "(name) VALUES ('" . $POST['productName'][$i] . "')";
				mysqli_query($this->dbConnect, $setItem);
				$item = $this->getItem($POST['productName'][$i]);
				$item_id = $item[0]['id'];
			}
			$sqlInsertItem = "INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
				VALUES ('" . $POST['invoiceId'] . "', '" . (($item_id != 0) ? $item_id : $POST['productCode'][$i]) . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
	}
	public function updateQuotation($POST)
	{
		$customer_id = 0;
		if (!$this->checkCustomer($POST['companyName'])) {
			// print_r(111111);
			$setItem = "INSERT INTO " . $this->customerTable . "(name, address, ntn, strn) VALUES ('" . $POST['companyName'] . "','" . $POST['address'] . "','" . $POST['ntn'] . "','" . $POST['strn'] . "')";
			mysqli_query($this->dbConnect, $setItem);

			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
		} else {
			$customer = $this->getCustomer($POST['companyName']);
			$customer_id = $customer[0]['customer_id'];
			$sqlInsert = "UPDATE " . $this->customerTable . " SET " .
				" name = '" . $POST['companyName'] . "', address= '" . $POST['address'] . "', ntn = '" . $POST['ntn'] . "', strn = '" . $POST['strn'] . "' 
				WHERE customer_id = '" . $customer_id . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		if ($POST['invoiceId']) {
			$sqlInsert = "UPDATE " . $this->quotationOrderTable . " SET " .
				" customer_id = '" . $customer_id . "', title= '" . $POST['title'] . "', order_total_before_tax = '" . $POST['subTotal'] . "', order_total_tax = '" . $POST['taxAmount'] . "', order_tax_per = '" . $POST['taxRate'] . "', order_total_after_tax = '" . $POST['totalAftertax'] . "', order_amount_paid = '" . $POST['amountPaid'] . "', order_total_amount_due = '" . $POST['amountDue'] . "', note = '" . $POST['notes'] . "' 
				WHERE user_id = '" . $POST['userId'] . "' AND order_id = '" . $POST['invoiceId'] . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		$this->deleteQuotationItems($POST['invoiceId']);
		for ($i = 0; $i < count($POST['productName']); $i++) {
			$item_id = 0;
			if (!$this->checkItem($POST['productName'][$i])) {
				$setItem = "INSERT INTO " . $this->itemTable . "(name) VALUES ('" . $POST['productName'][$i] . "')";
				mysqli_query($this->dbConnect, $setItem);
				$item = $this->getItem($POST['productName'][$i]);
				$item_id = $item[0]['id'];
			}
			$sqlInsertItem = "INSERT INTO " . $this->quotationOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
				VALUES ('" . $POST['invoiceId'] . "', '" . (($item_id != 0) ? $item_id : $POST['productCode'][$i]) . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
	}
	public function getInvoiceList()
	{
		$sqlQuery = "SELECT * FROM " . $this->invoiceOrderTable . " , " . $this->customerTable . " 
			WHERE " . $this->invoiceOrderTable . ".user_id = '" . $_SESSION['userid'] . "' AND " . $this->invoiceOrderTable . ".customer_id=" . $this->customerTable . ".customer_id";
		return  $this->getData($sqlQuery);
	}
	public function getQuotationList()
	{
		$sqlQuery = "SELECT * FROM " . $this->quotationOrderTable . " , " . $this->customerTable . " 
			WHERE " . $this->quotationOrderTable . ".user_id = '" . $_SESSION['userid'] . "' AND " . $this->quotationOrderTable . ".customer_id=" . $this->customerTable . ".customer_id";
		return  $this->getData($sqlQuery);
	}
	public function getInvoice($invoiceId)
	{
		$sqlQuery = "SELECT * FROM " . $this->invoiceOrderTable . " , " . $this->customerTable . " 
			WHERE user_id = '" . $_SESSION['userid'] . "' AND order_id = '$invoiceId' AND " . $this->invoiceOrderTable . ".customer_id=" . $this->customerTable . ".customer_id";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $row;
	}
	public function getQuotation($quotationId)
	{
		$sqlQuery = "SELECT * FROM " . $this->quotationOrderTable . " , " . $this->customerTable . " 
			WHERE user_id = '" . $_SESSION['userid'] . "' AND order_id = '$quotationId' AND " . $this->quotationOrderTable . ".customer_id=" . $this->customerTable . ".customer_id";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $row;
	}
	public function getInvoiceItems($invoiceId)
	{
		$sqlQuery = "SELECT * FROM " . $this->invoiceOrderItemTable . " 
			WHERE order_id = '$invoiceId'";
		return  $this->getData($sqlQuery);
	}
	public function getQuotationItems($quotationId)
	{
		$sqlQuery = "SELECT * FROM " . $this->quotationOrderItemTable . " 
			WHERE order_id = '$quotationId'";
		return  $this->getData($sqlQuery);
	}
	public function deleteInvoiceItems($invoiceId)
	{
		$sqlQuery = "DELETE FROM " . $this->invoiceOrderItemTable . " 
			WHERE order_id = '" . $invoiceId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function deleteQuotationItems($quotationId)
	{
		$sqlQuery = "DELETE FROM " . $this->quotationOrderItemTable . " 
			WHERE order_id = '" . $quotationId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function deleteInvoice($invoiceId)
	{
		$sqlQuery = "DELETE FROM " . $this->invoiceOrderTable . " 
			WHERE order_id = '" . $invoiceId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
		$this->deleteInvoiceItems($invoiceId);
		return 1;
	}
	public function deleteQuotation($quotationId)
	{
		$sqlQuery = "DELETE FROM " . $this->quotationOrderTable . " 
			WHERE order_id = '" . $quotationId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
		$this->deleteQuotationItems($quotationId);
		return 1;
	}
	public function getItems()
	{
		$sqlQuery = "SELECT * FROM " . $this->itemTable;
		return  $this->getData($sqlQuery);
	}
	public function getCustomers()
	{
		$sqlQuery = "SELECT * FROM " . $this->customerTable;
		return  $this->getData($sqlQuery);
	}
	public function getItem($name)
	{
		$sqlQuery = "SELECT * FROM " . $this->itemTable . " WHERE name='" . $name . "'";
		return $this->getData($sqlQuery);
	}
	public function getCustomer($name)
	{
		$sqlQuery = "SELECT * FROM " . $this->customerTable . " WHERE name='" . $name . "'";
		return $this->getData($sqlQuery);
	}
	public function checkItem($name)
	{
		$sqlQuery = "SELECT * FROM " . $this->itemTable . " WHERE name='" . $name . "'";
		$item = $this->getData($sqlQuery);
		if (count($item) > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function checkCustomer($name)
	{
		$sqlQuery = "SELECT * FROM " . $this->customerTable . " WHERE name='" . $name . "'";
		$item = $this->getData($sqlQuery);
		if (count($item) > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function getUser()
	{
		$sqlQuery = "SELECT * FROM " . $this->invoiceUserTable . " WHERE id='" . $_SESSION['userid'] . "'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $row;
	}
	public function updateUser($POST)
	{
		if ($POST['userId']) {

			// print_r($_FILES['signature']['tmp_name']);
			// exit;
			$file_path = '';
			if (isset($_FILES['signature'])) {
				$file_path = "img/uploads/" . $_FILES['signature']['name'];
				$file_tmp = $_FILES['signature']['tmp_name'];
				move_uploaded_file($file_tmp, $file_path);
			}
			$user = $this->getUser();
			$sqlInsert = "UPDATE " . $this->invoiceUserTable . " SET "
				. " email = '" . (!empty($POST['email']) ? $POST['email'] : $user['email'])
				. "', password= '" . (!empty($POST['password']) ? $POST['password'] : $user['password'])
				. "', first_name= '" . (!empty($POST['first_name']) ? $POST['first_name'] : $user['first_name'])
				. "', last_name= '" . (!empty($POST['last_name']) ? $POST['last_name'] : $user['last_name'])
				. "', mobile= '" . (!empty($POST['mobile']) ? $POST['mobile'] : $user['mobile'])
				. "', address= '" . (!empty($POST['address']) ? $POST['address'] : $user['address'])
				. "', ntn= '" . (!empty($POST['ntn']) ? $POST['ntn'] : $user['ntn'])
				. "', strn= '" . (!empty($POST['strn']) ? $POST['strn'] : $user['strn'])
				. "', file_path= '" . ($file_path != '' ? $file_path : $user['file_path'])
				. "' WHERE id = '" . $POST['userId'] . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
	}
}
