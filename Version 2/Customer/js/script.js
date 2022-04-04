if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {

    ready()
}


function ready() {
	
	
	/*Cart panel open-close*/

		function openFunction() {
			document.getElementById("cart").style.left="0";
			document.getElementById("back").style.left="0";

			document.getElementById("open_cart").style.display="none";
		}
		function closeFunction() {
			document.getElementById("cart").style.left="100%";
			document.getElementById("back").style.left="100%";

			document.getElementById("open_cart").style.display="block";
		}
		document.getElementById("open_cart").getElementsByTagName('img')[0].addEventListener('click', openFunction);
		document.getElementById("back").addEventListener('click',closeFunction);

	/*Fiter function*/
		const categoryTitle = document.getElementsByClassName('category');
		const allCategoryPosts = document.getElementsByClassName('all');

		for(let i = 0; i < categoryTitle.length; i++){
		    categoryTitle[i].addEventListener('click', filterPosts.bind(this, categoryTitle[i]));

		}

		function filterPosts(item){
		    changeActivePosition(item);
		    for(let i = 0; i < allCategoryPosts.length; i++){
		        if(allCategoryPosts[i].classList.contains(item.attributes.id.value)){
		            allCategoryPosts[i].style.display = "grid";
		        } else {
		            allCategoryPosts[i].style.display = "none";
		        }
		    }
		}

		function changeActivePosition(activeItem){
		    for(let i = 0; i < categoryTitle.length; i++){
		        categoryTitle[i].classList.remove('active');
		    }
		    activeItem.classList.add('active');
		};

	/*Quantity adjustment button*/

		var plus = document.querySelectorAll(".plus")
		var minus = document.querySelectorAll(".minus")
		var num = document.querySelectorAll(".num")

		
		for (let i = 0; i < num.length; i++) {
			plus[i].addEventListener("click", function(){
				cur_num = parseFloat(num[i].innerText)

				if (cur_num < 10){
					cur_num = "0" + String(cur_num+1)
				}

				num[i].innerText = cur_num;

				id = num[i].parentElement.parentElement.parentElement.id
				quantityChanged(id, cur_num)

			});
			minus[i].addEventListener("click", function(){
				cur_num = parseFloat(num[i].innerText)
				if (cur_num > 1){
					if (cur_num < 10){
						cur_num = "0" + String(cur_num-1)
					}
					num[i].innerText = cur_num;
					id = num[i].parentElement.parentElement.parentElement.id
					quantityChanged(id, cur_num)
				}
			});
		}

	var info_back_btn = document.getElementsByClassName('info_back')
	for(var i = 0; i < info_back_btn.length; i++){
		id = info_back_btn[i].parentElement.id
		
		info_back_btn[i].addEventListener('click',backBtnReset.bind(this, id))
	}

	var addToCartButtons = document.getElementsByClassName('info_add')
	for (var i = 0; i < addToCartButtons.length; i++) {
	    var button = addToCartButtons[i]
	    button.addEventListener('click', addToCartClicked)
	}

	var removeCartItemButtons = document.getElementsByClassName('info_remove')
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i]
        button.addEventListener('click', removeCartItem)
    }

    var add_btn = document.getElementsByClassName("info_add")
    var remove_btn = document.getElementsByClassName("info_remove")

    for (var i = 0; i < add_btn.length; i++) {
        var button = add_btn[i]
        button.addEventListener('click', switchBtn)
    }

    for (var i = 0; i < remove_btn.length; i++) {
        var button = remove_btn[i]
        button.addEventListener('click', switchBtn)
    }

    document.getElementsByClassName('cash')[0].addEventListener('click',setFormCash)

    document.getElementsByClassName('confirm_btn')[0].addEventListener('click',setTotal)

}

function setTotal() {
	document.getElementsByClassName('payment_number')[0].innerText = document.getElementsByClassName('total_number')[0].innerText
}

function backBtnReset(id){

		var cartItem = document.getElementsByClassName('cart_item')
		var boo = 0
		for(var j = 0; j < cartItem.length; j++){
			if (cartItem[j].id == id){
				boo = 1
			}
		}

		if (boo == 0){
			resetQuantity(id)
		}
	}

	
/*Cart function*/

function removeCartItem(event) {
	var buttonClicked = event.target
    var id = buttonClicked.parentElement.parentElement.id

	cart = document.getElementsByClassName('cart_item')

	var remove_idx = 0
	for (var i = 0; i < cart.length; i++) {
        if (cart[i].id == id) {
            remove_idx = i
        }
    }
    cart[remove_idx].remove()
    resetQuantity(id)
    updateCartTotal()
}

function quantityChanged(id, quantity) {
	cartItem = document.getElementsByClassName('cart_item')
	for (var i = 0; i < cartItem.length; i++ ){
		if( cartItem[i].id == id){
			var old_money = parseFloat(cartItem[i].getElementsByClassName('cartMoney')[0].innerText.replace('$', ''))
			var old_quan = parseFloat(cartItem[i].getElementsByClassName('cartQuan')[0].innerText.replace('x', ''))
			var price = old_money/old_quan
			cartItem[i].getElementsByClassName('cartQuan')[0].innerText = "x" + String(parseFloat(quantity))
			cartItem[i].getElementsByClassName('cartMoney')[0].innerText = "$"+String(parseFloat(price * quantity))
			updateCartTotal()
		}
	}
}

function addToCartClicked(event) {
	var button = event.target
    var shopItem = button.parentElement.parentElement
    var id = shopItem.id
    var title = shopItem.getElementsByClassName('info_name')[0].innerText
    var price = shopItem.getElementsByClassName('info_price')[0].getElementsByTagName("p")[0].innerText
    var quantity = shopItem.getElementsByClassName('info_price')[0].getElementsByClassName('quantity')[0].getElementsByClassName('num')[0].innerText
    var imageSrc = shopItem.getElementsByClassName('info_picture')[0].getElementsByTagName("img")[0].src
    addItemToCart(id, title, price, quantity, imageSrc)
    updateCartTotal()
}

function addItemToCart(id, title, price, quantity, imageSrc) {
	var cartItem = document.createElement('div')
    cartItem.classList.add('cart_item')
    cartItem.id = id
    var cart = document.getElementById('bill')

    imageSrc = imageSrc.replace('http://localhost/SE_assignment_task_4/', '')
    price = parseFloat(price.replace('$', ''))
    
    quantity = parseFloat(quantity)
    money = Math.round(price*quantity * 100) / 100
    var cartItemContents = 
    	`<div class="cartImg">
    		<img src="${imageSrc}">
    	</div>
    	 <div class="cartName">${title}</div>
    	 <div class="cartQuan">x${quantity}</div>
    	 <div class="cartMoney">$${money}</div>`
    cartItem.innerHTML = cartItemContents
    cart.append(cartItem)
}

function switchBtn(event){
	var button = event.target
	if (button.classList[0] == "info_add"){
		button.parentElement.getElementsByClassName("info_remove")[0].style.display = "flex"
		button.style.display = "none"
	}
	else{
		button.parentElement.getElementsByClassName("info_add")[0].style.display = "flex"
		button.style.display = "none"
	}
}
function updateCartTotal() {
	var cartItemContainer = document.getElementById('bill')
    var cartRows = cartItemContainer.getElementsByClassName('cart_item')
    var total = 0
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i]
        var money = cartRow.getElementsByClassName('cartMoney')[0].innerText
       
        money = parseFloat(money.replace('$', ''))
        total = total + money
    }
    document.getElementsByClassName('total_number')[0].innerText = '$' + total
}
function resetQuantity(id){
	var item = document.getElementsByClassName("information")
	for (var i = 0; i < item.length; i++) {
        if (item[i].id == id) {
            item[i].getElementsByClassName('info_price')[0].getElementsByClassName('num')[0].innerText = "0" + String(1)
        }
    }
}

function setFormCash() {
	document.getElementById('total').value = parseFloat(document.getElementsByClassName('total_text')[0].innerText.replace('$', ''))
	document.getElementById('paid').checked = false;
	document.getElementById('confirmed').checked = false;
	document.getElementById('payment_method').value = "cash";

	var cart_item = document.getElementsByClassName('cart_item')
	for (var i = 0; i < cart_item.length; i++) {
		document.getElementById('menu_id').value = parseInt(cart_item[i].id.replace('info', ''))
		document.getElementById('quantity').value = parseInt(cart_item[i].getElementsByClassName('cartQuan')[0].innerText.replace('x', ''))
	}	

	document.getElementById('food_form').submit()

	console.log("Submitted")
}

function setFormPaypal() {
	document.getElementById('total').value = parseFloat(document.getElementsByClassName('total_text').innerText.replace('$', ''))
	document.getElementById('paid').checked = document.cookie;
	document.getElementById('confirmed').checked = false;
	document.getElementById('payment_method').value = "paypal";

	var cart_item = document.getElementsByClassName('cart_item')
	for (var i = 0; i < cart_item.length; i++) {
		document.getElementById('menu_id').value = parseInt(cart_item[i].id.replace('info', ''))
		document.getElementById('quantity').value = parseInt(cart_item[i].getElementsByClassName('cartQuan')[0].innerText.replace('x', ''))
	}	

	document.getElementById('food_form').submit()
}

function submit_alert(){
	alert("form_submited");
}