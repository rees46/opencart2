<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<input type="hidden" name="setting[rees46_action_lead]" value="<?php echo $rees46_action_lead; ?>" />
					<input type="hidden" name="setting[rees46_xml_exported]" value="<?php echo $rees46_xml_exported; ?>" />
					<div class="alert alert-info"><?php echo $text_help; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
						<li><a href="#tab-orders" data-toggle="tab"><?php echo $tab_orders; ?></a></li>
						<li><a href="#tab-customers" data-toggle="tab"><?php echo $tab_customers; ?></a></li>
						<li><a href="#tab-webpush" data-toggle="tab"><?php echo $tab_webpush; ?></a></li>
						<li><a href="#tab-modules" data-toggle="tab"><?php echo $tab_modules; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<?php if ($rees46_store_key == '' || $rees46_secret_key == '') { ?>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_info_1; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-store_key"><?php echo $entry_store_key; ?></label>
								<div class="col-sm-10">
									<input type="text" name="setting[rees46_store_key]" value="<?php echo $rees46_store_key; ?>" id="input-store_key" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-secret_key"><?php echo $entry_secret_key; ?></label>
								<div class="col-sm-10">
									<input type="text" name="setting[rees46_secret_key]" value="<?php echo $rees46_secret_key; ?>" id="input-secret_key" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-tracking_status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="setting[rees46_tracking_status]" id="input-tracking_status" class="form-control">
										<?php if ($rees46_tracking_status) { ?>
										<option value="0"><?php echo $text_disabled; ?></option>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<?php } else { ?>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<option value="1"><?php echo $text_enabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
								<div class="col-sm-10">
									<select name="setting[rees46_log]" id="input-log" class="form-control">
										<?php if ($rees46_log) { ?>
										<option value="0"><?php echo $text_disabled; ?></option>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<?php } else { ?>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<option value="1"><?php echo $text_enabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-products">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-xml_currency"><?php echo $entry_xml_currency; ?></label>
								<div class="col-sm-10">
									<select name="setting[rees46_xml_currency]" id="input-xml_currency" class="form-control">
										<?php foreach ($currencies as $currency) { ?>
										<?php if ($currency['code'] == $rees46_xml_currency) { ?>
										<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-xml_cron"><?php echo $entry_xml_cron; ?></label>
								<div class="col-sm-10">
									<input type="text" value="<?php echo $cron; ?>" id="input-xml_cron" class="form-control" readonly />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-orders">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_status_created; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($order_statuses as $order_status) { ?>
										<div class="checkbox">
											<label>
												<?php if (in_array($order_status['order_status_id'], $rees46_status_created)) { ?>
												<input type="checkbox" name="setting[rees46_status_created][]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
												<?php echo $order_status['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="setting[rees46_status_created][]" value="<?php echo $order_status['order_status_id']; ?>" />
												<?php echo $order_status['name']; ?>
												<?php } ?>
											</label>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_status_completed; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($order_statuses as $order_status) { ?>
										<div class="checkbox">
											<label>
												<?php if (in_array($order_status['order_status_id'], $rees46_status_completed)) { ?>
												<input type="checkbox" name="setting[rees46_status_completed][]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
												<?php echo $order_status['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="setting[rees46_status_completed][]" value="<?php echo $order_status['order_status_id']; ?>" />
												<?php echo $order_status['name']; ?>
												<?php } ?>
											</label>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_status_cancelled; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($order_statuses as $order_status) { ?>
										<div class="checkbox">
											<label>
												<?php if (in_array($order_status['order_status_id'], $rees46_status_cancelled)) { ?>
												<input type="checkbox" name="setting[rees46_status_cancelled][]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
												<?php echo $order_status['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="setting[rees46_status_cancelled][]" value="<?php echo $order_status['order_status_id']; ?>" />
												<?php echo $order_status['name']; ?>
												<?php } ?>
											</label>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_export_orders; ?></label>
								<div class="col-sm-10">
									<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_info_2; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
									<button type="button" onclick="startExport('orders');" class="btn btn-success" id="button-start-orders"><?php echo $button_export; ?></button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-customers">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-customers"><?php echo $entry_export_type; ?></label>
								<div class="col-sm-10">
									<select name="setting[rees46_customers]" id="input-customers" class="form-control">
										<?php if ($rees46_customers) { ?>
										<option value="1" selected="selected"><?php echo $text_customers; ?></option>
										<option value="0"><?php echo $text_subscribers; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_customers; ?></option>
										<option value="0" selected="selected"><?php echo $text_subscribers; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_export_customers; ?></label>
								<div class="col-sm-10">
									<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_info_3; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
									<button type="button" onclick="startExport('customers');" class="btn btn-success" id="button-start-customers"><?php echo $button_export; ?></button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-webpush">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_webpush_files; ?></label>
								<div class="col-sm-10">
									<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_info_4; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
									<button type="button" onclick="startCheck();" class="btn btn-success" id="button-start-check"><?php echo $button_check; ?></button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-modules">
							<div class="row">
								<div class="col-md-2">
									<ul class="nav nav-pills nav-stacked">
										<?php foreach ($modules as $module) { ?>
										<li id="module-<?php echo $module['module_id']; ?>" class="module"><a href="#tab-module-<?php echo $module['module_id']; ?>" data-toggle="tab"><?php echo $module['setting']['name']; ?><span style="display: block; float: right;"><i class="fa fa-remove" onclick="$('#module-<?php echo $module['module_id']; ?>').remove(); $('#tab-module-<?php echo $module['module_id']; ?>').remove(); $('form').append('<input type=\'hidden\' name=\'delete[]\' value=\'<?php echo $module['module_id']; ?>\' />'); $('.nav-stacked .module:first-child a').trigger('click'); return false;"></i></span></a></li>
										<?php } ?>
										<li class="add"><a id="module-add" onclick="addModule();" style="cursor: pointer;"><?php echo $button_add; ?><span style="display: block; float: right;"><i class="fa fa-plus"></i></span></a></li>
									</ul>
								</div>
								<div class="col-md-10">
									<div class="tab-content">
										<?php foreach ($modules as $module) { ?>
										<div class="tab-pane" id="tab-module-<?php echo $module['module_id']; ?>">
											<input type="hidden" name="module[<?php echo $module['module_id']; ?>][module_id]" value="<?php echo $module['module_id']; ?>" />
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-name<?php echo $module['module_id']; ?>"><?php echo $entry_name; ?></label>
												<div class="col-sm-10">
													<input type="text" name="module[<?php echo $module['module_id']; ?>][name]" value="<?php echo $module['setting']['name']; ?>" id="input-name<?php echo $module['module_id']; ?>" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-type<?php echo $module['module_id']; ?>"><?php echo $entry_type; ?></label>
												<div class="col-sm-10">
													<select name="module[<?php echo $module['module_id']; ?>][type]" id="input-type<?php echo $module['module_id']; ?>" class="form-control">
														<option value="interesting" <?php if ($module['setting']['type'] == 'interesting') { ?>selected="selected"<?php } ?>><?php echo $text_type_interesting; ?></option>
														<option value="also_bought" <?php if ($module['setting']['type'] == 'also_bought') { ?>selected="selected"<?php } ?>><?php echo $text_type_also_bought; ?></option>
														<option value="similar" <?php if ($module['setting']['type'] == 'similar') { ?>selected="selected"<?php } ?>><?php echo $text_type_similar; ?></option>
														<option value="popular" <?php if ($module['setting']['type'] == 'popular') { ?>selected="selected"<?php } ?>><?php echo $text_type_popular; ?></option>
														<option value="see_also" <?php if ($module['setting']['type'] == 'see_also') { ?>selected="selected"<?php } ?>><?php echo $text_type_see_also; ?></option>
														<option value="recently_viewed" <?php if ($module['setting']['type'] == 'recently_viewed') { ?>selected="selected"<?php } ?>><?php echo $text_type_recently_viewed; ?></option>
														<option value="buying_now" <?php if ($module['setting']['type'] == 'buying_now') { ?>selected="selected"<?php } ?>><?php echo $text_type_buying_now; ?></option>
														<option value="search" <?php if ($module['setting']['type'] == 'search') { ?>selected="selected"<?php } ?>><?php echo $text_type_search; ?></option>
														<option value="supply" <?php if ($module['setting']['type'] == 'supply') { ?>selected="selected"<?php } ?>><?php echo $text_type_supply; ?></option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-title<?php echo $module['module_id']; ?>"><?php echo $entry_title; ?></label>
												<div class="col-sm-10">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
													<span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>
													<input type="text" name="module[<?php echo $module['module_id']; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $module['setting']['title'][$language['language_id']]; ?>" id="input-title<?php echo $module['module_id']; ?>" class="form-control" />
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-limit<?php echo $module['module_id']; ?>"><?php echo $entry_limit; ?></label>
												<div class="col-sm-10">
													<input type="text" name="module[<?php echo $module['module_id']; ?>][limit]" value="<?php echo $module['setting']['limit']; ?>" id="input-limit<?php echo $module['module_id']; ?>" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-width<?php echo $module['module_id']; ?>"><?php echo $entry_width; ?></label>
												<div class="col-sm-10">
													<input type="text" name="module[<?php echo $module['module_id']; ?>][width]" value="<?php echo $module['setting']['width']; ?>" id="input-width<?php echo $module['module_id']; ?>" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-height<?php echo $module['module_id']; ?>"><?php echo $entry_height; ?></label>
												<div class="col-sm-10">
													<input type="text" name="module[<?php echo $module['module_id']; ?>][height]" value="<?php echo $module['setting']['height']; ?>" id="input-height<?php echo $module['module_id']; ?>" class="form-control" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-template<?php echo $module['module_id']; ?>"><?php echo $entry_template; ?></label>
												<div class="col-sm-10">
													<select name="module[<?php echo $module['module_id']; ?>][template]" id="input-template<?php echo $module['module_id']; ?>" class="form-control">
														<option value="rees46_default" <?php if ($module['setting']['template'] == 'rees46_default') { ?>selected="selected"<?php } ?>><?php echo $text_template_default; ?></option>
														<option value="bestseller" <?php if ($module['setting']['template'] == 'bestseller') { ?>selected="selected"<?php } ?>><?php echo $text_template_bestseller; ?></option>
														<option value="featured" <?php if ($module['setting']['template'] == 'featured') { ?>selected="selected"<?php } ?>><?php echo $text_template_featured; ?></option>
														<option value="latest" <?php if ($module['setting']['template'] == 'latest') { ?>selected="selected"<?php } ?>><?php echo $text_template_latest; ?></option>
														<option value="special" <?php if ($module['setting']['template'] == 'special') { ?>selected="selected"<?php } ?>><?php echo $text_template_special; ?></option>
														<option value="rees46_basic" <?php if ($module['setting']['template'] == 'rees46_basic') { ?>selected="selected"<?php } ?>><?php echo $text_template_basic; ?></option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-discount<?php echo $module['module_id']; ?>"><?php echo $entry_discount; ?></label>
												<div class="col-sm-10">
													<select name="module[<?php echo $module['module_id']; ?>][discount]" id="input-discount<?php echo $module['module_id']; ?>" class="form-control">
														<?php if ($module['setting']['discount'] == 1) { ?>
														<option value="0"><?php echo $text_disabled; ?></option>
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<?php } else { ?>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<option value="1"><?php echo $text_enabled; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group" id="autocomplete<?php echo $module['module_id']; ?>">
												<label class="col-sm-2 control-label"><?php echo $entry_brands; ?></label>
												<div class="col-sm-10">
													<input type="text" value="" placeholder="<?php echo $text_autocomplete; ?>" class="form-control autocomplete" />
													<div class="well well-sm" style="height: 100px; overflow: auto;">
														<?php if (isset($module['manufacturers'])) { ?>
														<?php foreach ($module['manufacturers'] as $manufacturer) { ?>
														<div class="module-autocomplete<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
															<input type="hidden" name="module[<?php echo $module['module_id']; ?>][manufacturers][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
														</div>
														<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="form-group" id="autocomplete-exclude<?php echo $module['module_id']; ?>">
												<label class="col-sm-2 control-label"><?php echo $entry_exclude_brands; ?></label>
												<div class="col-sm-10">
													<input type="text" value="" placeholder="<?php echo $text_autocomplete; ?>" class="form-control autocomplete" />
													<div class="well well-sm" style="height: 100px; overflow: auto;">
														<?php if (isset($module['manufacturers_exclude'])) { ?>
														<?php foreach ($module['manufacturers_exclude'] as $manufacturer) { ?>
														<div class="module-autocomplete<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
															<input type="hidden" name="module[<?php echo $module['module_id']; ?>][manufacturers_exclude][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
														</div>
														<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-status<?php echo $module['module_id']; ?>"><?php echo $entry_block_status; ?></label>
												<div class="col-sm-10">
													<select name="module[<?php echo $module['module_id']; ?>][status]" id="input-status<?php echo $module['module_id']; ?>" class="form-control">
														<?php if ($module['setting']['status'] == 1) { ?>
														<option value="0"><?php echo $text_disabled; ?></option>
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<?php } else { ?>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<option value="1"><?php echo $text_enabled; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
<?php if (isset($module_id)) { ?>
$('.nav-tabs li:nth-child(6) a').trigger('click');
$('#module-<?php echo $module_id; ?> a').trigger('click');
<?php } else { ?>
$('.nav-stacked .module:first-child a').trigger('click');
<?php } ?>

$('.autocomplete').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		var id = $(this).parent().parent('.form-group').attr('id');

		$('#' + id + ' .autocomplete').val('');

		$('#' + id + ' .module-autocomplete' + item['value']).remove();

		if (isNaN(id.replace('autocomplete',''))) {
			$('#' + id + ' .well').append('<div class="module-autocomplete' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="module[' + id.replace('autocomplete-exclude','') + '][manufacturers_exclude][]" value="' + item['value'] + '" /></div>');
		} else {
			$('#' + id + ' .well').append('<div class="module-autocomplete' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="module[' + id.replace('autocomplete','') + '][manufacturers][]" value="' + item['value'] + '" /></div>');
		}
	}
});

$('.well').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<div class="tab-pane" id="tab-module-' + module_row + '">';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-name' + module_row + '"><?php echo $entry_name; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" name="module[' + module_row + '][name]" value="<?php echo $text_tab_module; ?> ' + module_row + '" id="input-name' + module_row + '" class="form-control" />';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-type' + module_row + '"><?php echo $entry_type; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<select name="module[' + module_row + '][type]" id="input-type' + module_row + '" class="form-control">';
	html += '				<option value="interesting"><?php echo $text_type_interesting; ?></option>';
	html += '				<option value="also_bought"><?php echo $text_type_also_bought; ?></option>';
	html += '				<option value="similar"><?php echo $text_type_similar; ?></option>';
	html += '				<option value="popular"><?php echo $text_type_popular; ?></option>';
	html += '				<option value="see_also"><?php echo $text_type_see_also; ?></option>';
	html += '				<option value="recently_viewed"><?php echo $text_type_recently_viewed; ?></option>';
	html += '				<option value="buying_now"><?php echo $text_type_buying_now; ?></option>';
	html += '				<option value="search"><?php echo $text_type_search; ?></option>';
	html += '				<option value="supply"><?php echo $text_type_supply; ?></option>';
	html += '			</select>';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-title' + module_row + '"><?php echo $entry_title; ?></label>';
	html += '		<div class="col-sm-10">';
	<?php foreach ($languages as $language) { ?>
	html += '			<div class="input-group pull-left">';
	html += '				<span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>';
	html += '				<input type="text" name="module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" value="" id="input-title' + module_row + '" class="form-control" />';
	html += '			</div>';
	<?php } ?>
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-limit' + module_row + '"><?php echo $entry_limit; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" name="module[' + module_row + '][limit]" value="" id="input-limit' + module_row + '" class="form-control" />';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-width' + module_row + '"><?php echo $entry_width; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" name="module[' + module_row + '][width]" value="" id="input-width' + module_row + '" class="form-control" />';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-height' + module_row + '"><?php echo $entry_height; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" name="module[' + module_row + '][height]" value="" id="input-height' + module_row + '" class="form-control" />';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-template' + module_row + '"><?php echo $entry_template; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<select name="module[' + module_row + '][template]" id="input-template' + module_row + '" class="form-control">';
	html += '				<option value="rees46_default"><?php echo $text_template_default; ?></option>';
	html += '				<option value="bestseller"><?php echo $text_template_bestseller; ?></option>';
	html += '				<option value="featured"><?php echo $text_template_featured; ?></option>';
	html += '				<option value="latest"><?php echo $text_template_latest; ?></option>';
	html += '				<option value="special"><?php echo $text_template_special; ?></option>';
	html += '				<option value="rees46_basic"><?php echo $text_template_basic; ?></option>';
	html += '			</select>';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-discount' + module_row + '"><?php echo $entry_discount; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<select name="module[' + module_row + '][discount]" id="input-discount' + module_row + '" class="form-control">';
	html += '				<option value="0"><?php echo $text_disabled; ?></option>';
	html += '				<option value="1"><?php echo $text_enabled; ?></option>';
	html += '			</select>';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group" id="autocomplete' + module_row + '">';
	html += '		<label class="col-sm-2 control-label" for="input-manufacturers' + module_row + '"><?php echo $entry_brands; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" value="" placeholder="<?php echo $text_autocomplete; ?>" class="form-control autocomplete" />';
	html += '			<div class="well well-sm" style="height: 100px; overflow: auto;"></div>';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group" id="autocomplete-exclude' + module_row + '">';
	html += '		<label class="col-sm-2 control-label" for="input-manufacturers-exclude' + module_row + '"><?php echo $entry_exclude_brands; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<input type="text" value="" placeholder="<?php echo $text_autocomplete; ?>" class="form-control autocomplete" />';
	html += '			<div class="well well-sm" style="height: 100px; overflow: auto;"></div>';
	html += '		</div>';
	html += '	</div>';
	html += '	<div class="form-group">';
	html += '		<label class="col-sm-2 control-label" for="input-status' + module_row + '"><?php echo $entry_block_status; ?></label>';
	html += '		<div class="col-sm-10">';
	html += '			<select name="module[' + module_row + '][status]" id="input-status' + module_row + '" class="form-control">';
	html += '				<option value="0"><?php echo $text_disabled; ?></option>';
	html += '				<option value="1"><?php echo $text_enabled; ?></option>';
	html += '			</select>';
	html += '		</div>';
	html += '	</div>';
	html += '</div>';

	$('.row .tab-content').append(html);

	$('.nav-stacked .add').before('<li id="module-' + module_row + '" class="module"><a href="#tab-module-' + module_row + '" data-toggle="tab"><?php echo $text_tab_module; ?> ' + module_row + '<span style="display: block; float: right;"><i class="fa fa-remove" onclick="$(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); $(\'.nav-stacked .module:first-child a\').trigger(\'click\'); return false;"></i></span></a></li>');

	$('#module-' + module_row + ' a').trigger('click');

	module_row++;

	$('.autocomplete').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			var id = $(this).parent().parent('.form-group').attr('id');

			$('#' + id + ' .autocomplete').val('');

			$('#' + id + ' .module-autocomplete' + item['value']).remove();

			if (isNaN(id.replace('autocomplete',''))) {
				$('#' + id + ' .well').append('<div class="module-autocomplete' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="module[' + id.replace('autocomplete-exclude','') + '][manufacturers_exclude][]" value="' + item['value'] + '" /></div>');
			} else {
				$('#' + id + ' .well').append('<div class="module-autocomplete' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="module[' + id.replace('autocomplete','') + '][manufacturers][]" value="' + item['value'] + '" /></div>');
			}
		}
	});

	$('.well').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
}

function startExport(type, next) {
	if (!next) {
		next = 1;
	}

	$.ajax({
		url: 'index.php?route=module/rees46/export&token=' + getURLVar('token'),
		type: 'post',
		data: 'type=' + type + '&next=' + next,
		dataType: 'json',
		beforeSend: function() {
			$('#button-start-' + type).button('loading');
		},
		success: function(json) {
			$('.alert-danger, .alert-success').remove();

			if (json['success']) {
				$('#tab-' + type).prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['next']) {
				startExport(type, json['next']);
			} else {
				$('#button-start-' + type).button('reset');
			}

			if (json['error']) {
				$('#tab-' + type).prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function startCheck() {
	$.ajax({
		url: 'index.php?route=module/rees46/startCheck&token=' + getURLVar('token'),
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-start-check').button('loading');
		},
		success: function(json) {
			$('#tab-webpush .alert-danger, #tab-webpush .alert-success').remove();
			$('#button-start-check').button('reset');

			if (json['success_loaded']) {
				$.map(json['success_loaded'], function(success) {
					$('#button-start-check').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				});
			}

			if (json['error_loaded']) {
				$.map(json['error_loaded'], function(error) {
					$('#button-start-check').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + error + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				});
			}

			if (json['error']) {
				$('#tab-webpush').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//--></script>
<?php echo $footer; ?>