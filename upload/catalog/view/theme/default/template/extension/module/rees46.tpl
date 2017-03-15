<!-- BEGIN REES46 BLOCK <?php echo $module_id; ?> -->
<script type="text/javascript"><!--
<?php if ($css) { ?>
r46('add_css', 'recommendations'); 
<?php } ?>
r46('recommend', '<?php echo $type; ?>', <?php echo $params; ?>, function(results) {
	if (results.length > 0) {
		$('#rees46-recommended-<?php echo $module_id; ?>').load('index.php?route=extension/module/rees46/getProducts&module_id=<?php echo $module_id; ?>&product_ids=' + results);
	}
});
--></script>
<div id="rees46-recommended-<?php echo $module_id; ?>" class="rees46-recommended" data-recommended-type="<?php echo $type; ?>"></div>
<!-- END REES46 BLOCK <?php echo $module_id; ?> -->