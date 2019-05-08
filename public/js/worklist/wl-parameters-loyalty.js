/**
 * Рабочий лист
 * Параметры - Программа лояльности
 */

(function(){
	let wl_id = ''
	let container = ''
	let loyalty = $('.loyalty_program')
	let typeProgram = ['Скидки','Подарки','Сервисы']
	let modal = $("#pv-modal")
	let sale = 0;
	var repartion = 0;
	let dopsale = 0;
	let doprepartion = 0;

	$(document).on('shown.bs.collapse','#wsparam6',function(){
		wl_id = $('span[name=wl_id]').html(); //беру ид раблиста
		if (wl_id != '' && wl_id != '-')
		{
			let url = '/worklist/loyalty/program'
			let parameters = {'wl_id':wl_id}
			$.when(ajax(parameters,url)).then(function(data){
				sale = 0;
				repartion = 0;
				dopsale = 0;
				doprepartion = 0;
				loyalty.html('');
				loyalty.append('<div class="input-group h4">Программа лояльности</div>');
				modal.find(".pv-action").remove();
				writeProgram(JSON.parse(data));
				priceVidget();
			});
		}
	});

	$(document).on('click','#open-pv-vidget',function(){
		priceVidget();
	});

	function priceVidget()
	{
		url = '/worklist/car/price'
		let parameters = {'wl_id':wl_id}
		$.when(ajax(parameters,url)).then(function(data){					
			var price = JSON.parse(data);
			modal.css('display','block');
			modal.find('#pv-base').html(number_format(price.base,0,'',' '));
			modal.find('#pv-pack').html(number_format(price.pack,0,'',' '));
			modal.find('#pv-dops').html(number_format(price.dops,0,'',' '));
			modal.find('#pv-total').html(number_format(price.total,0,'',' '));
		});
	};

	//значок вопроса у компании
	$(document).on('click','.loyalty-block a',function(){
		text = $(this).attr('title');
		alert(text);
	});

	//включение акции и проверка возможности её работы
	$(document).on('change','.loyalty-block input[type="checkbox"]',function(){
		let itemElem = $(this)
		let itemBlock = itemElem.closest('.loyalty-block')
		let itemNomen = itemBlock.find('.nomenal')
		let itemReturn = itemBlock.find('.return')
		let itemName = itemBlock.find('.loyalty-name')
		let itemStatus = ($(this).prop("checked"))

		if (itemStatus) {
			makeSale(itemElem,itemNomen.val(),'+')
			makeRepartion(itemElem,itemReturn.val(),'+')

			itemNomen.removeAttr('disabled')
			itemReturn.removeAttr('disabled')
			itemElem.closest('label').addClass('green-check')
			disabledOther(itemElem)
			var appended = modal.find('.default').clone()
			appended.addClass('pv-action')
			modal.find('.pv-programms').append(appended)
			appended.removeClass('default')
			appended.find('.pv-name').html(itemElem.attr('text'))
			appended.attr('data-id',itemElem.val())
		} else {
			makeSale(itemElem,itemNomen.val(),'-')
			makeRepartion(itemElem,itemReturn.val(),'-')
			itemReturn.attr('disabled','disabled')
			itemNomen.attr('disabled','disabled')
			itemElem.closest('label').removeClass('green-check')
			enabledOther(itemElem)
			modal.find('[data-id="'+itemElem.val()+'"]').remove()
		}
	})

	$(document).on('keyup','.nomenal',function(){
		sale = 0;
		dopsale = 0;
		var val = nonSpace($(this).val());
		$(this).val(number_format(val,0,'',' '));
		$('.loyalty-block').find('.nomenal').each(function(){
			if($(this).prop('disabled')==false)					
				makeSale($(this).closest('.loyalty-block').find('input[type="checkbox"]'),$(this).val(),'+')			
		});
	});

	$(document).on('keyup','.return',function(){
		repartion = 0;
		doprepartion = 0;
		var val = nonSpace($(this).val());
		$(this).val(number_format(val,0,'',' '));
		$('.loyalty-block').find('.return').each(function(){
			if($(this).prop('disabled')==false)					
				makeRepartion($(this).closest('.loyalty-block').find('input[type="checkbox"]'),$(this).val(),'+')			
		});
	});

	function makeSale(obj,val,opt)
	{	
		if(obj.attr('razdel')==1 || obj.attr('razdel')==4)
		{
			if(opt=='+')
				sale += parseInt(nonSpace(val))
			else if(opt=="-")
				sale -= parseInt(nonSpace(val))
			$('[name="loyalty_sale"]').val(number_format(sale,0,'',' ')+" руб.")
		}
		if(obj.attr('razdel')==2)
		{
			if(opt=='+')
				dopsale += parseInt(nonSpace(val))
			else if(opt=="-")
				dopsale -= parseInt(nonSpace(val))
			$('[name="loyalty_dop_sale"]').val(number_format(dopsale,0,'',' ')+" руб.")
		}
	}

	function makeRepartion(obj,val,opt)
	{			
		if(obj.attr('razdel')==1 || obj.attr('razdel')==4)
		{
			if(opt=='+')
				repartion += parseInt(nonSpace(val))
			else if(opt=="-")
				repartion -= parseInt(nonSpace(val))
			$('[name="loyalty_repartion"]').val(number_format(repartion,0,'',' ')+" руб.")
		}
		if(obj.attr('razdel')==2)
		{
			if(opt=='+')
				doprepartion += parseInt(nonSpace(val))
			else if(opt=="-")
				doprepartion -= parseInt(nonSpace(val))
			$('[name="loyalty_dop_repartion"]').val(number_format(doprepartion,0,'',' ')+" руб.")
		}
	}

	function disabledOther(itemElem)
	{
		let all = $('.loyalty-block input[type="checkbox"]');
		log(all)
		if(itemElem.attr('main')==1)
			all.each(function(){
				var current = $(this)
				if(current.val() != itemElem.val() && current.attr('immortal')!=1){
					current.closest('.loyalty-block').find('.nomenal').attr('disabled','disabled')
					current.closest('.loyalty-block').find('.return').attr('disabled','disabled')
					current.prop('disabled',true)
					if(current.prop('checked')==true){
						makeSale(current,current.closest('.loyalty-block').find('.nomenal').val(),'-')
						makeRepartion(current,current.closest('.loyalty-block').find('.return').val(),'-')
					}
					current.prop('checked', false)					
					current.closest('label').removeClass('green-check')
					current.closest('label').addClass('red-check')
					modal.find('[data-id="'+current.val()+'"]').remove()	
					//log('disabled')				
				}
				//log(current.closest('.loyalty-block').find('a').attr('title'))
			})
	}

	function enabledOther(itemElem)
	{
		let all = $('.loyalty-block input[type="checkbox"]');
		if (itemElem.attr('main') == 1)
			all.each(function(){
				var current = $(this)
				if (current.val() != itemElem.val() && current.attr('immortal') != 1){
					current.closest('.loyalty-block').find('.nomenal').attr('disabled','disabled')
					current.prop('disabled',false)
					current.prop('checked', false);
					current.closest('label').removeClass('green-check')
					current.closest('label').removeClass('red-check')
				}
			})
	}

	function writeProgram(data) 
	{
		for (i in data) {
			if(i == 0)
			{
				var str = '<div class="input-group no-gutters pt-3"><div class="col-4">'
					str += '<label style="margin:0px;">Скидка на авто (итого):</label>'
					str += '<input type="text" name="loyalty_sale" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div>'


				str += '<div class="col-4">'
					str += '<label style="margin:0px;">Возмещение на авто (итого):</label>'
					str += '<input type="text" name="loyalty_repartion" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div></div>'
				loyalty.append(str)
			}

			if(i==1)
			{
				var str = '<div class="input-group no-gutters pt-3"><div class="col-4">'
					str += '<label style="margin:0px;">Скидка на допы (итого):</label>'
					str += '<input type="text" name="loyalty_dop_sale" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div>'


				str += '<div class="col-4">'
					str += '<label style="margin:0px;">Возмещение на допы (итого):</label>'
					str += '<input type="text" name="loyalty_dop_repartion" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div></div>'
				loyalty.append(str)
			}

			for(k in data[i]) {
				var str = ''
				if(k==0){
					str += '<div class="input-group no-gutters pt-3">'
		                str += '<label class="col-6 font-weight-bold">'+typeProgram[i]+':</label>'
		                str += '<label class="col-3">Номинал (руб.)</label>'
		                str += '<label class="col-3">Возмещение (руб.)</label>'
		            str += '</div>'
				}
				//str += '<div class="row">'
					str += '<div class="input-group no-gutters loyalty-block">'
						str += '<div class="col-6 d-flex align-items-center">'
							str += '<div class="input-group no-gutters">'

								str += '<div class="col-2">'
									str += '<div class="check">';
										str += '<label>'
											str += '<input '+
												'type="checkbox" '+
												'name="loyalty[company_id][]" '+
												'value="'+data[i][k].id+'" '+
												'immortal="'+data[i][k].immortal+'" '+
												'main="'+data[i][k].main+'" '+
												'type="'+data[i][k].razdel+'" '+
												'text="'+data[i][k].name+'" '+
												'razdel="'+data[i][k].razdel+'" '
											str += '>'										
										str += '</label>';
									str += '</div>';
								str += '</div>'

								str += '<div class="col-9 loyalty-name">'
									str += data[i][k].name
								str += '</div>'

								str += '<div class="col-1"><a href="#" title="'+data[i][k].text+'">'
			                        str += '<i class="icofont-info-circle" ></i>'
			                    str += '</a></div>'

		                	str += '</div>'
		                str += '</div>'

		                log(data[i][k].selected)
		            	str += '<div class="col-3"><input style="border-right:0px;" name="loyalty[sum]['+data[i][k].id+']" type="text" class="form-control nomenal" placeholder="" '		   
			            	if(data[i][k].selected!=null)
			            		str += " value = '"+number_format(data[i][k].selected.sum,0,'',' ')+"' "
			            	else
			            		str += 'disabled value="'+ number_format(data[i][k].sum,0,'',' ')+'" '
		            	str += '></div>'

		            	str += '<input type="hidden" value="'+data[i][k].razdel+'" name="loyalty[razdel]['+data[i][k].id+']" >'

		            	//если это не подарок
		            	if(data[i][k].razdel!=2)
		            	{
			                str += '<div class="col-3"><input name="loyalty[rep]['+data[i][k].id+']" type="text" class="form-control return" placeholder="" '
				            	if(data[i][k].selected!=null)
				            		str += " value = '"+number_format(data[i][k].selected.rep,0,'',' ')+"' "
				            	else
				            		str += 'disabled value="'+ number_format(data[i][k].repsum,0,'',' ')+'" '
				            str += '></div>'
				        }
				        //если это подарок
				        else
				        {
				        	if(data[i][k].selected!=null)
				        		str += '<div class="col-1"><input name="loyalty[percent]" type="text" class="form-control" value="'+data[i][k].selected.percent+'"></div>'
				        	else
				        		str += '<div class="col-1"><input name="loyalty[percent]" type="text" class="form-control" value="'+data[i][k].default_percent+'"></div>'

				        	str += '<div class="col-2"><input name="loyalty[rep]['+data[i][k].id+']" type="text" class="form-control return" placeholder="" '
				            	if(data[i][k].selected!=null)
				            		str += " value = '"+number_format(data[i][k].selected.rep,0,'',' ')+"' "
				            	else
				            		str += 'disabled value="'+ number_format(data[i][k].repsum,0,'',' ')+'" '
				            str += '></div>'
				        }

		            str += '</div>'
		        //str += '</div>'
		        loyalty.append(str)
		    }
		}

		for( i in data)
		{
			for(k in data[i])
			{
				if(data[i][k].selected!=null)
		        	$('.loyalty-block input[value="'+data[i][k].id+'"]').click()
			}
		}
	}
})();