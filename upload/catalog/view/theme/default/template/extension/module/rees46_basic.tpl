<div class="rees46 rees46-recommend">
	<div class="recommender-block-title"><?php echo $heading_title; ?></div>
	<div class="recommended-items">
		<?php foreach ($products as $product) { ?>
		<div class="recommended-item">
			<div class="recommended-item-photo">
				<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
			</div>
			<div class="recommended-item-title">
				<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
			</div>
			<?php if ($product['price']) { ?>
			<div class="recommended-item-price">
				<?php if (!$product['special']) { ?>
				<?php echo $product['price']; ?>
				<?php } else { ?>
				<?php echo $product['special']; ?>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="recommended-item-action">
				<a href="<?php echo $product['href']; ?>"><?php echo $text_more; ?></a>
			</div>
		</div>
		<?php } ?>
	</div>
</div>