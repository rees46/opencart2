<?php if (isset($status)) { ?><!-- REES46 start -->
<script type="text/javascript"><!--
(function(r){window.r46=window.r46||function(){(r46.q=r46.q||[]).push(arguments)};var s=document.getElementsByTagName(r)[0],rs=document.createElement(r);rs.async=1;rs.src='//cdn.rees46.com/v3.js';s.parentNode.insertBefore(rs,s);})('script');
r46('init', '<?php echo $shop_id; ?>');
<?php if (isset($customer_id)) { ?>
r46('profile', 'set', {id: <?php echo $customer_id; ?>, email: '<?php echo $customer_email; ?>', birthday: '', gender: '' });
<?php } ?>
<?php if (isset($guest_email)) { ?>
r46('profile', 'set', {email: '<?php echo $guest_email; ?>'});
<?php } ?>
<?php if (isset($product_id)) { ?>
r46('track', 'view', '<?php echo $product_id; ?>');
<?php } ?>

$(document).ajaxComplete(function(e, xhr, settings) {
	if (settings.url == 'index.php?route=checkout/cart/add') {
		var product_id, quantity;

		settings.data.split('&').forEach(function(pair) {
			var parts = pair.split('=');

			if (parts[0] == 'product_id') {
				product_id = parts[1];
			}

			if (parts[0] == 'quantity') {
				quantity = parts[1];
			}
		});

		r46('track', 'cart', {id: product_id, amount: quantity});
	} else if (settings.url == 'index.php?route=checkout/cart/edit' || settings.url == 'index.php?route=checkout/cart/remove') {
		$.getJSON('index.php?route=common/rees46/getCartProducts', function(cart){
			r46('track', 'cart', cart);
		});
	}
});
--></script>
<!-- REES46 end --><?php } ?>