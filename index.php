<?php


session_start();

include './includeitems/configitems.php';//link to connection page to connect to database


/*
File:		index.php
Author:		Levi Meahan
Purpose:	View products
*/


require("products.php");
require("layout.php");



// Initialize cart
if(!isset($_SESSION['shopping_cart'])) {
	$_SESSION['shopping_cart'] = array();
}
// Empty cart
if(isset($_GET['empty_cart'])) {
	$_SESSION['shopping_cart'] = array();
}


// **PROCESS FORM DATA**

$message = '';

// Add product to cart
if(isset($_POST['add_to_cart'])) {
	$product_id = $_POST['product_id'];
	
	// Check for valid item
	if(!isset($products[$product_id])) {
		$message = "Invalid item!<br />";
	}
	// If item is already in cart, tell user
	else if(isset($_SESSION['shopping_cart'][$product_id])) {
		$message = "Item already in cart!<br />";
	}
	// Otherwise, add to cart
	else {
		$_SESSION['shopping_cart'][$product_id]['product_id'] = $_POST['product_id'];
		$_SESSION['shopping_cart'][$product_id]['quantity'] = $_POST['quantity'];
		$message = "Added to cart!";
        header("location:./index.php?view_cart=1"); 
	}
}
// Update Cart
if(isset($_POST['update_cart'])) {
	$quantities = $_POST['quantity'];
	foreach($quantities as $id => $quantity) {
		if(!isset($products[$id])) {
			$message = "Invalid product!";
			break;
		}
		$_SESSION['shopping_cart'][$id]['quantity'] = $quantity;
	}
	if(!$message) {
		$message = "Cart updated!<br>";
	}
}


// **DISPLAY PAGE**
echo $header;

echo $message;

// View a product
if(isset($_GET['view_product'])) {
	$product_id = $_GET['view_product'];
	
	if(isset($products[$product_id])) {
		// Display site links

        
        
        echo "<div class='container'>
  <div class='box''>
    <div class='product-img'>
      ". $products[$product_id]['dinkIcon'] ."
    </div>
    <div class='product-info'>
      <h1> ". $products[$product_id]['name'] ."</h1>
      <br>
      <p class='price'>£". $products[$product_id]['price'] ."</p>
       <br>
      <p class='description'>". $products[$product_id]['description'] ."</p>
   
      <form action='./index.php?view_product=$product_id' method='post'>
      <select class='quantity'name='quantity' >
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        </select>
          <input type='hidden' name='product_id' value='$product_id' />
         
      <button style=' margin: 2em 0 0; margin-bottom: 20px; ' type='submit' name='add_to_cart'value='Add to cart' class='button button2'>ADD TO CART</button>
      </form>
    </div>
  </div>
</div>";

    
	}
	else {
		echo "Invalid product!";
	}
}
















// View cart
else if(isset($_GET['view_cart'])) {
	// Display site links
	
	echo "
	<p>
		
	</p>";
	
	if(empty($_SESSION['shopping_cart'])) {
		echo "Your cart is empty.<br />";
	}
	else {
		echo "<form action='./index.php?view_cart=1' method='post'>
		 <div id='w'>
    <div id='page'>
      <table id='cart'>
        <thead>
          <tr>
            
            <th class='second'>Qty</th>
            <th class='third'>Product</th>
            <th class='fourth'>Category</th>
            <th class='second'>Price</th>
          
          
          
          </tr>
        </thead>";
				foreach($_SESSION['shopping_cart'] as $id => $product) {
					$product_id = $product['product_id'];
					
					echo "
                
                
            <tr class='productitm'>
          
            <td><input type='number' value=". $product['quantity'] ." min='0' max='99' class='qtyinput'></td>
            <td><a href='./index.php?view_product=$id' style=' color: #1aa6c9;'>" . $products[$product_id]['name'] . "</a></td>
            <td>" . $products[$product_id]['category'] ."</td>
            <td>" . $products[$product_id]['price'] ."</td>
            
          </tr>
                
                        
                   
							
					";
				}
			echo " 
          <tr class'checkoutrow'>
            <td colspan='6' class='checkout'>
            
    


<div class='comone'>

<a href='/index.php?checkout=1' class='button'>Check out</a>

<a href='./index.php?empty_cart=1' class='button'>Empty Cart</a>

</div>
          </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  
  
";
		
	}
}
// Checkount
else if(isset($_GET['checkout'])) {
	// Display site links
	echo "<p>
		<a href='./index.php'>DropShop</a></p>";
	
	echo "<h3>Checkout</h3>";
	
	if(empty($_SESSION['shopping_cart'])) {
		echo "Your cart is empty.<br />";
	}
	else {
        
      
        
        
		echo "<form action='./index.php?checkout=1' method='post'>
		<table style='width:500px;' cellspacing='0'>
				<tr>
					<th style='border-bottom:1px solid #000000;'>Name</th>
					<th style='border-bottom:1px solid #000000;'>Item Price</th>
					<th style='border-bottom:1px solid #000000;'>Quantity</th>
					<th style='border-bottom:1px solid #000000;'>Cost</th>
                    
				</tr>";
				
				$total_price = 0;
				foreach($_SESSION['shopping_cart'] as $id => $product) {
                   
                    
                    
					$product_id = $product['product_id'];
					
					
					$total_price += $products[$product_id]['price'] * $product['quantity'];
					echo "
                    
                    
                     <tr class='productitm'>
          
            <td><input type='number' value=". $product['quantity'] ." min='0' max='99' class='qtyinput'></td>
            <td><a href='./index.php?view_product=$id' style=' color: #1aa6c9;'>" . $products[$product_id]['name'] . "</a></td>
            <td>" . $products[$product_id]['category'] ."</td>
            <td>" . $products[$product_id]['price'] ."</td>
            
          </tr>
                    
                    
                    
                    <tr>
						<td style='border-bottom:1px solid #000000;'><a href='./index.php?view_product=$id'>" . $products[$product_id]['name'] . "</a></td>
						<td style='border-bottom:1px solid #000000;'>£" . $products[$product_id]['price'] . "</td> 
						<td style='border-bottom:1px solid #000000;'>" . $product['quantity'] . "</td>
						<td style='border-bottom:1px solid #000000;'>£" . ($products[$product_id]['price'] * $product['quantity']) . "</td>
						

					";
                    
                    
                  
        $query = "SELECT itemName FROM item WHERE itemId = ". $products[$product_id]['itemID'] ." ";
                    
        $data = selectitem($query);//calls function in the connection page above
        

        foreach( $data as  $user){ 
                          
            
//        echo " <td style='border-bottom:1px solid #000000; color:white;'>  " . $user['itemName'] . "</td> </tr>";
       
            
         
        $amounnt =  $product['quantity'];
            
            
        $email =  $_SESSION["email"];
        
        
        $currentDateTime = date("j of F Y, \a\\t g.i a", time());
        

        $item = $user['itemName'];
        

        
        if(isset($_POST['submit'])){
        
        $SQL = "INSERT INTO purchase (email, Purchasesdate, item , amount) VALUES (' $email ', ' $currentDateTime ', '$item', $amounnt)";

        $result = insertitem($SQL);
        
        }
        
            
            
            
            
            
        
        }
                   
        }
             
        
  
        
        
        
     echo "
            
             </table>
			<p  style=' color: #fff;'> Total price: £"  	. $total_price . "</p>";   
        
        
    echo "<form method='post'>
        
    <input type='submit' name='submit' value='Make payment'/>  </form>";
        
        
        
        
    $currentDateTime = date("j of F Y, \a\\t g.i a", time());
        
        
 //  show date time stamp
        
 //  echo " <p  style=' color: #fff;'> Time " . $currentDateTime . "</p>";
        
        
 //   echo " <p  style=' color: #fff;'> email " . $email . "</p>";
    
}
    
    
       
}
// View all products
else {
	// Display site links
	

	// Loop to display all products
	foreach($products as $id => $product) {
    
        
        echo "<div class='pic'>";
        echo "<a href='./index.php?view_product=$id'> " . $product['dinkIcon'] ." </a> ";
        
         //    echo  "<a href='./index.php?view_product=$id'> "<img src= '. $product['dinkIcon'] .' width='200' height='200'/> </a>"";

        
        echo "<div class='text'>";
        
        echo "<p class ='item_name'>". $product['name']."</p> ";
        
        echo "<p class ='item_dec'>". $product['description']."</p> ";
        
        echo "</div>";
        echo "</div>";
        
        
	}
	
    
  
}

echo $footer;

?>