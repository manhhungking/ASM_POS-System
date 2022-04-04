<!-- PHP INCLUDES -->

<?php

    include "connect.php";
    $_SESSION["table_id"] = 1;
?>

<!DOCTYPE html>
<html lang = "en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="css/style.css">

	<link href="https://fonts.googleapis.com/css family=Crimson+Text|Work+Sans:400,700" rel="stylesheet">

	<script src="https://kit.fontawesome.com/ee5b22327f.js" crossorigin="anonymous"></script>
	
	<script src="js/script.js"></script>
</head>
<?php

            if(isset($_POST['food_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                // Selected Menus

                $selected_menus = $_POST['selected_menus'];

                $con->beginTransaction();
                try
                {
                    $stmtgetCurrentOrderID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'pos_system' AND TABLE_NAME = 'placed_orders'");
            
                    $stmtgetCurrentOrderID->execute();
                    $order_id = $stmtgetCurrentOrderID->fetch();
                    
                    $stmt_table = $con->prepare("Select table_id from tables");
                    $stmt_table->execute();
                    $table = $stmt_table->fetchAll();
                    
                    $stmt_order = $con->prepare("insert into placed_orders(order_time, client_id, table_id) values(?, ?, ?)");
                    $stmt_order->execute(array(Date("Y-m-d H:i"),$table[0]));

                    foreach($selected_menus as $menu)
                    {
                        $stmt = $con->prepare("insert into in_order(order_id, menu_id, quantity) values(?, ?, ?)");
                        $stmt->execute(array($order_id[0],$menu,____));
                    }
                    
                    echo '<div class = "alert alert-success">';
                        echo 'Great! Your order has been created successfully.';
                    echo '</div>';

                    $con->commit();
                }
                catch(Exception $e)
                {
                    $con->rollBack();
                    echo '<div class = "alert alert-danger">'; 
                        echo $e->getMessage();
                    echo '</div>';
                }
            }

 ?>

<body>

	<aside id="sidebar">

	 	<input type="checkbox" id="check">
		<label for="check">
			<i class="fas fa-bars sticky" id="btn"></i>
			<i class="fas fa-times" id="cancel"></i>
		</label>

	 	<header id="avatar">
	 		<div class="logo">
	 			<div class="res_logo"></div>
	 		</div>
	 		<div class="res_name">Hong Kong Noodle Shop</div>
	 	</header>

	 	<div id="nav">
	 		<ul>
				<li><a href="#"><i class="far fa-user-circle fa-lg"></i>Profile</a></li>
				<li><a href="#"  id="manu_page"><i class="far fa-calendar-minus fa-lg"></i>Menu</a></li>
				<li><a href="#"><i class="fas fa-comment-dots fa-lg"></i>Contact</a></li>
				<li><a href="#"><i class="fas fa-tags fa-lg"></i>Voucher</a></li>
				<li><a href="#"><i class="fas fa-cogs fa-lg"></i>Setting</a></li>
				<li><a href="#"><i class="fas fa-ellipsis-h fa-lg"></i>About</a></li>
			</ul>
	 	</div>

	 </aside>

	<main>

	 	<section id="banner">
	 		<h1>Hong Kong Noodle Shop</h1>
			<p>An oasis of pleasure</p>

			<div id="open_cart">
				<img src="image/icon/cloche.png">
				<p>3</p>
			</div>
	 	</section>

	 	<section id="searchbox">
	 		<div id="search_bar">

				<div class="search_btn">
					<i class="fas fa-search"></i>
				</div>

				<input placeholder="What would you like to eat ?" type="text">

				<div class="filter_btn">
					<i class="fas fa-sliders-h"></i>
				</div>
			</div>

			<div id="category-head">
				<div class="category active" id="all">
					<object type="image/svg+xml" data="image/icon/all.svg"></object>
					<p>All</p>
				</div>
				<div class="category" id="Noodle">
					<object type="image/svg+xml" data="image/icon/noodles.svg"></object>
					<p>Noodle</p>
				</div>
				<div class="category" id="Rice">
					<object type="image/svg+xml" data="image/icon/rice-bowl.svg"></object>
					<p>Rice</p>
				</div>
				<div class="category" id="Soup">
					<object type="image/svg+xml" data="image/icon/soup.svg"></object>
					<p>Soup</p>
				</div>
				<div class="category" id="Dessert">
					<object type="image/svg+xml" data="image/icon/ice-cream.svg"></object>
					<p>Dessert</p>
				</div>
			</div>
	 	</section>

	 	<section id="menu">
	 		<?php
				$stmt_menu = $con->prepare("Select * from menus");
			    $stmt_menu->execute();
				$rows = $stmt_menu->fetchAll();

				foreach($rows as $row)
				{	
					$stmt_category = $con->prepare("Select category_name from menu_categories where category_id = ?");
			    	$stmt_category->execute(array($row['category_id']));
					$category = $stmt_category->fetch();

					
					$type = $category[0];
					$image = $row['menu_image'];
					$price = $row['menu_price'];
					$name = $row['menu_name'];

					echo '<div class="card all '.$type; echo '">';

						echo '<div class="card_image">';
							$source = "image/food_image/".$image;
							echo '<img src= '; echo $source; echo'>';
						echo '</div>';

						echo '<p class="card_price"><strong>$'; echo  $price; echo'</strong></p>';

						echo '<h3 class="card_name">'; echo $name; echo '</h3>';

						echo '<div class="card_star">';
							echo '<i class="fas fa-star"></i>';
							echo '<i class="fas fa-star"></i>';
							echo '<i class="fas fa-star"></i>';
							echo '<i class="fas fa-star"></i>';
							echo '<i class="fas fa-star"></i>';
						echo '</div>';

					echo '</div>';

				}

			?>
	 	</section>

	 	<section id="cart">

	 		<header>My order</header>

	 		<i class="fas fa-chevron-circle-left fa-3x" id="back"></i>

	 		<div id="bill">
	 			
	 		</div>

	 		<div id="total">
	 			<p class="total_text">Total</p>
				<p class="total_number">$0</p>
	 		</div>

	 		<div id="confirm_order">
	 			<p class="confirm_btn"><strong>Confirm order</strong></p>
	 		</div>

	 	</section>

	 	<div id="info">
	 		<?php
				$stmt_menu = $con->prepare("Select * from menus");
			    $stmt_menu->execute();
				$rows = $stmt_menu->fetchAll();

				foreach($rows as $row)
				{	
					$id = "info".strval($row['menu_id']);

					echo '<div class="information" id="'; echo $id; echo'">';

						echo '<i class="fas fa-chevron-circle-left fa-3x info_back"></i>';
						echo '<i class="far fa-heart fa-3x info_heart"></i>';

						echo '<div class="info_picture">';
							$source = "image/food_image/".$row['menu_image'];
							echo '<img src= '; echo $source; echo'>';
						echo '</div>';

						echo '<div class="info_name">'; echo $row['menu_name']; echo'</div>';

						echo '<div class="info_price">';
			 				echo '<p><strong>$'; echo $row['menu_price']; echo'</strong></p>';
							echo '<div class="quantity">';
								echo '<span class="minus">-</span>';
								echo '<span class="num">01</span>';
								echo '<span class="plus">+</span>';
							echo '</div>';
			 			echo '</div>';

			 			echo '<div class="info_star">';
			 				echo '<i class="fas fa-star fa"></i>';
							echo '<i class="fas fa-star fa"></i>';
							echo '<i class="fas fa-star fa"></i>';
							echo '<i class="fas fa-star fa"></i>';
							echo '<i class="fas fa-star fa5"></i>';
			 			echo '</div>';

			 			echo '<div class="info_des">';
			 				echo $row['menu_description'];
			 			echo '</div>';

			 			echo '<div class="info_btn">';
			 				echo '<div class="info_add">Add to cart</div>';
			 				echo '<div class="info_remove">Remove from cart</div>';
			 			echo '</div>';
			 		echo '</div>';
				}
					
			?>
	 	</div>

	 	<section id="payment">

	 		<i class="fas fa-chevron-circle-left fa-3x" id="payment_back"></i>

	 		<div id="payment_box">
	 			<div class="background">
	 				<div class="first_box"></div>
	 				<div class="second_box"></div>
	 				<div class="third_box">
		 				<p class="payment_text">Total Bill</p>
		 				<p class="payment_number">$0</p>
		 			</div>

		 			<p class="choose_text">Choose your payment method</p>

		 			<div class="pay_btn">
		 				<div id="paypal-button"></div>

		 				<p class="cash">Pay by Cash</p>
		 			</div>
	 			</div>
	 
	 		</div>
	 	</section>

	 	<form method="post" id="food_form" onsubmit="submit_alert()">
	 		<input type="number" name="total" id="total">
	 		<input type="text" name="payment_method" id="payment_method">
	 		<input type="checkbox" name="paid" id="paid">
	 		<input type="checkbox" name="confirmed" id="confirmed">

	 		<input type="number" name="menu_id[]" id="menu_id">
	 		<input type="number" name="quantity[]" id="quantity">
	 	</form>

	 </main>

	
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js" integrity="sha512-eP6ippJojIKXKO8EPLtsUMS+/sAGHGo1UN/38swqZa1ypfcD4I0V/ac5G3VzaHfDaklFmQLEs51lhkkVaqg60Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
<script>
	const confirm = document.getElementsByClassName("confirm_btn")[0]
	const payment_back = document.getElementById("payment_back")
	const t = gsap.timeline();
	t.pause();
	t.to("#payment", {opacity: 1, y:0, scale:1, duration: .3, pointerEvents: 'auto'});
	confirm.addEventListener('click',function(){t.play();})
	payment_back.addEventListener('click',function(){t.reverse();})

	const open = document.querySelectorAll(".card");
	const close = document.querySelectorAll(".info_back");
	const t1 = [];

	for(let i = 0; i < open.length; i++){
		t1[i] = gsap.timeline();
		t1[i].pause();
	}
	for(let i = 0; i < open.length; i++){
		t1[i].to("#info"+ String(i), {opacity: 1, /*y:0, scale:1, */duration: .3, pointerEvents: 'auto'});
	}

	for(let i = 0; i < open.length; i++){
		open[i].addEventListener('click',function(){
			t1[i].play();
		})
		close[i].addEventListener('click',function(){
			t1[i].reverse();
		})
	}
</script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'ASnEzxQEZ4RhdcE4SZfIrTZsUxiXkvrImjBxV-CUhVBpWA6Lvy7oONQVUzapm84yVe7JwWy7tMJwokWm',
      production: 'demo_production_client_id'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
      size: 'small',
      color: 'gold',
      shape: 'pill',
    },
    // Enable Pay Now checkout flow (optional)
    commit: true,
    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: /*<?php echo (round($_SESSION['sum']*0.000044, 2)); ?>,*/ "1",
            currency: "USD",
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        // Show a confirmation message to the buyer
        // Update client
        // Insert vô db:
        
      	});
    }
  }, '#paypal-button');

</script>
</body>