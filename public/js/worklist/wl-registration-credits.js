/**
 * Рабочий лист
 * Оформление - Кредиты
 */

(function(){
	let link = $(document).find('div[id="wsdesign4"]');
	let block = $(document).find('.client_kredit');

	link.on('show.bs.collapse', function() {
		block.html('<div class="input-group h4">Кредиты</div>');
		let worklist_id = $('span[name=wl_id]').html(); //беру ид раблиста
		let parameters = {'worklist_id' : worklist_id};
		let url = '/get/worklist/kredit';
		$.when(ajax(parameters, url)).then(function(data) {
			data = JSON.parse(data);
			block.append(data.html);
			for (i in data)
			{
				block.find('[name="wl_kredit[' + i + ']"]').val(data[i]);
			}
			block.find('[name="wl_kredit[payment]"]').val(number_format(block.find('[name="wl_kredit[payment]"]').val(), 0, '', ' '));
			block.find('[name="wl_kredit[sum]"]').val(number_format(block.find('[name="wl_kredit[sum]"]').val(), 0, '', ' '));
			block.find('[name="wl_kredit[price]"]').val(number_format(block.find('[name="wl_kredit[price]"]').val(), 0, '', ' '));
			block.find('[name="wl_kredit[valid_date]"]').val(timeConverter(block.find('[name="wl_kredit[valid_date]"]').val()));
			block.find('[name="wl_kredit[margin_kredit]"]').val(number_format(block.find('[name="wl_kredit[margin_kredit]"]').val(), 0, '', ' '));
			block.find('[name="wl_kredit[margin_product]"]').val(number_format(block.find('[name="wl_kredit[margin_product]"]').val(), 0, '', ' '));
			block.find('[name="wl_kredit[margin_other]"]').val(number_format(block.find('[name="wl_kredit[margin_other]"]').val(), 0, '', ' '));
			block.find('.calendar').datepicker();
		});
	});

	block.on('click', '#adder_app', function() {
		var content = block.find('.kredit_app_content');
		var first = content.find('.app_block:first');
		var clone = first.clone();
		clone.find('[type="text"]').val('');
		clone.find('select').val('');
		clone.find('[type="checkbox"]').prop('checked', false);
		clone.addClass('pdt-20');
		clone.find('.calendar').removeClass('hasDatepicker').attr('id', '');
		clone.find('input').each(function() {
			var name = $(this).attr('name');
			name = name.split(']');
			name = name.join('');
			name = name.split('[');
			name[2] = parseInt(name[2]) + 1;
			var newName = name[0] + '[' + name[1] + ']' + '[' + name[2] + ']' + '[' + name[3] + ']';
			if (name[3] == 'product')
				newName += '[]';
			$(this).attr('name',newName);
		});
		content.append(clone);
		content.find('.calendar').datepicker();
	});

	block.on('click', '.deleter_app', function() {
		var delBlock = $(this).closest('.app_block');
		log(delBlock);
		if (block.find('.app_block').length > 1)
			delBlock.remove();
		else
		{
			delBlock.find('input').val('');
			delBlock.find('select').val('');
			delBlock.removeClass('pdt-20');
		}
	});

	block.on('keyup', '.kredit_payment', function(e) {
		var text = 0;
		if ( (e.which >= 96 && e.which <= 105) || (e.which >= 48 && e.which <= 57 || e.which == 8) )
			text = text;
		else
			$(this).val($(this).val().substring(0, $(this).val().length - 1));

		var payment = parseInt(nonSpace($(this).val()));
		var price = parseInt(nonSpace($('.kredit_price').val()));
		var sum = price - payment;
		$(this).val(number_format(payment, 0, '', ' '));
		$('.kredit_sum').val(number_format(sum, 0, '', ' '));
	});

	block.on('keyup', '.money', function(e) {
		var text = 0;
		log(e.which);
		if ( (e.which >= 96 && e.which <= 105) || (e.which >= 48 && e.which <= 57) || e.which == 8 )
			text = $(this).val();
		else
			text = $(this).val().substring(0, $(this).val().length - 1);
		$(this).val(number_format(nonSpace(text), 0, '', ' '));
	});

	block.on('change', '.status', function() {		
		$(this).closest('.app_block').find('.status_date').val(getCurrentDate());
	});

	block.on('keyup', '.action_mon', function(e) {
		var text = 0;
		if ( (e.which >= 96 && e.which <= 105) || (e.which >= 48 && e.which <= 57 || e.which == 8) )
			text = text;
		else
			$(this).val($(this).val().substring(0, $(this).val().length - 1));

		if ($(this).val().length > 0)
			text = $(this).val();

		var date;
		if ($(this).closest('.app_block').find('.status_date').val().length == 0)
			date = new Date();
		else
		{
			var str = $(this).closest('.app_block').find('.status_date').val();
			str = str.split('.');
			date = new Date(str[2], (str[1]-1), str[0]);
		}
		date.setMonth(date.getMonth() + parseInt(text));
		var day = date.getDate();
		var mon = date.getMonth() + 1;
		var year = date.getFullYear();
		if (day < 10)
			day = '0' + day;
		if (mon < 10)
			mon = '0' + mon;
		var strDate = [day, mon, year].join('.');
		$(this).closest('.app_block').find('.action_date').val(strDate);
	});

})();