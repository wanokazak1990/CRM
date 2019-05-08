/**
 * Рабочий лист
 * Оформление - Контракты (Договоры)
 */

(function(){
	let link = $(document).find('div[id="wsdesign2"]');
	let block = $(document).find('.client_contract');

	link.on('show.bs.collapse',function(){
		block.html('<div class="input-group h4">Договоры</div>');
		let worklist_id = $('span[name=wl_id]').html(); //беру ид раблиста
		let parameters = {'worklist_id' : worklist_id};
		let url = '/get/worklist/contracts';
		$.when(ajax(parameters, url)).then(function(data) {
			block.append(data);
			block.find('.calendar').datepicker();
		});
	});

	block.on('click','#adder_ship',function(){
		var parent = $(this).closest('.input-group').find('div[class="input-group no-gutters"]');
		var newBlock = '<div class="col-3"><input type="text" class="form-control calendar" name="contract[ship][]"></div>';
		parent.append(newBlock);
		$('[name="contract[ship][]"]').datepicker();
	});

	block.on('change', '[name="contract[crash]"]',function() {
		if ($(this).prop('checked') == true)
			$('[name="contract[date_crash]"]').prop('disabled', false);
		else
			$('[name="contract[date_crash]"]').prop('disabled', true);
	});

})();