<?php 

/****************************** HELPER FUNCTIONS ******************************/

function display_msg(){
	if(isset($_SESSION['message'])){

		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function set_message($msg){
	if(!empty($msg)){
		$_SESSION['message'] = $msg;
	}else{
		$msg = '';
	}
}

function redirect($location){
	header('Location: '. $location);
}

function query($sql){
	global $connection;

	return mysqli_query($connection, $sql);
}

function confirm($result){
	global $connection;

	if(!$result){
		die('Query failed ' . mysqli_error($connection));
	}
}

function escape_string($string){
	global $connection;

	return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result){
	return mysqli_fetch_array($result);
}

/********************************FRONT END FUNCTIONS ********************************/

// get products

function get_products(){
	$query = query('select * from products');

	confirm($query);

	while($row = fetch_array($query)){
		$product = <<<DELIMITER
			<div class="col-sm-4 col-lg-4 col-md-4">
				<div class="thumbnail">
					<a href="item.php?id={$row['id']}"><img src="{$row['product_image']}" alt=""></a>
					<div class="caption">
						<h4 class="pull-right">&#36;{$row['product_price']}</h4>
						<h4><a href="item.php?id={$row['id']}">{$row['product_title']}</a></h4>
						<p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
						<a class="btn btn-primary" target="" href="cart.php?add={$row['id']}">Add to cart</a>
					</div>
				</div>
			</div>
		DELIMITER;

		echo $product;
	}
}

function get_categories(){
	$query = query('select * from categories');
	confirm($query);

	while ($row = fetch_array($query)){
		$category_links = <<<DELIMITER
			<a href='category.php?id={$row['id']}' class='list-group-item'>{$row['title']}</a>
		DELIMITER;

		echo $category_links;
	}

}

function get_products_in_cat_page(){
	$query = query('select * from products where product_category_id = "'.escape_string($_GET['id']).'"');

	confirm($query);

	while($row = fetch_array($query)){
		$product = <<<DELIMITER
			<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="{$row['product_image']}" alt="">
                    <div class="caption">
                    	<h3>{$row['product_title']}</h3>
                    	<p>{$row['short_desc']}</p>
                        <p>
                        	<a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMITER;

		echo $product;
	}
}


function get_products_in_shop_page(){
	$query = query('select * from products');

	confirm($query);

	while($row = fetch_array($query)){
		$product = <<<DELIMITER
			<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="{$row['product_image']}" alt="">
                    <div class="caption">
                    	<h3>{$row['product_title']}</h3>
                    	<p>{$row['short_desc']}</p>
                        <p>
                        	<a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMITER;

		echo $product;
	}
}

function contact(){
	if(isset($_POST['submit'])){

		$send_to = 'nekimejl@mejl.mejl';
		$name = $_POST['name'];
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];

		$headers = 'From: "'.$name.'" "'.$email.'"';

		$result = mail($send_to, $subject, $message, $headers);
		if(!$result){
			set_message('Your message was not sent successfully');
			redirect('contact.php');
		}else{
			set_message('Your message was sent successfully');
			redirect('contact.php');

		}

	}
}

/********************************BACK END FUNCTIONS ********************************/

function login(){
	if(isset($_POST['submit'])){
		$username = escape_string($_POST['username']);
		$password = escape_string($_POST['password']);

		$query = query('select * from users where username = "'.$username.'" and password = "'.$password.'"');
		confirm($query);

		if(mysqli_num_rows($query) == 0){
			set_message('Your username and password are incorrect');
			redirect('login.php');
		}else{
			et_message('Welcome '. $username);
			redirect('admin');
		}
	}
}

