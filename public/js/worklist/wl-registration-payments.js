/**
 * Рабочий лист
 * Оформление - Платежи
 */

(function(){
	let link = $(document).find('div[id="wsdesign1"]')
	let block = $(document).find('.client_pays')

	
	
	//нажатие на заголовок вкладки ПЛАТЕЖИ
	link.on('show.bs.collapse',function() {
		block.html('<div class="input-group h4">Платежи</div>');
		let worklist_id = $('span[name=wl_id]').html(); //беру ид раблиста
		let parameters = {'worklist_id' : worklist_id};
		let url = '/get/worklist/pays';
		$.when(ajax(parameters, url)).then(function(data) {
			block.append(data);
			block.find('[name="wl_pay_date[]"]').datepicker();
		});
	});

	//добавление новой строки с вводом суммы даты и платежа
	block.on('click','#adder_pay',function() {
		var content = $(this).closest('.pay_content')
		var line = $(this).closest('.item').clone()
		if (line.find('[name="wl_pay_sum[]"]').val()) {
			line.removeClass('item');
			line.find('#adder_pay').remove();
			line.find('input').val('');
			line.find('[type="checkbox"]').prop('checked',false);
			line.find('[name="wl_pay_date[]"]').datepicker();
			content.append(line);
		}
	});

	//Проверка что все поля заполнены нажатие на чекбокс
	block.on('change','[type="checkbox"]',function(){
		if($(this).prop('checked')==true)
		{
			var check = $(this)
			var line = check.closest('.input-group')
			line.find('[type="text"]').each(function(){
				if($(this).val()=='')
				{
					alert('Не заполненны поля')
					check.prop('checked',false)
					return false
				}
			})
		}
	})

	//удаление строки с инпутами
	block.on('click','.fa-times',function(){
		var line = $(this).closest('.input-group')
		if(line.find("#adder_pay").length)
		{
			line.find('input').val('')
			line.find('[type="checkbox"]').prop('checked',false)
		}
		else
			line.remove()
	})

	block.on('keyup','[name="wl_pay_sum[]"]',function(e){
		var text = 0
		var sum =0
		var total = block.find("#wl_pay_carprice").attr('data-price')
		
		if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57) )
			text = text
		else
			$(this).val($(this).val().substring(0,$(this).val().length-1))

		block.find('[name="wl_pay_sum[]"]').each(function(){
			if($(this).val())
				sum+=parseInt($(this).val())
		})

		$(this).closest('.input-group').find('[name="wl_pay_debt[]"]').val(total-sum)
		block.find('#wl_pay_client').html(sum+' р.')
	})

	block.on('click','#adder_pay_pts',function(){log(1)
		var parent = $(this).closest('.info')
		var newInput = parent.find('.item').clone().removeClass('item')
		parent.append(newInput)
	})
})();