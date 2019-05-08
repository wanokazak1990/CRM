/**
 * Карточка автомобиля
 */

(function(){

//объект модали
let m_carModal 				= $("#autocardModal");
//объекты управления
let m_model 				= m_carModal.find('[name="model_id"]');
let m_complect 				= m_carModal.find('[name="complect_id"]');
let m_carmore				= m_carModal.find("#car-more");

let complect;

//динамические ячейки данных 
class carInfo{
	constructor(){
		this.base = 0;//база
		this.pack = 0;//пакеты
		this.dops = 0;//допы
		this.total = 0;//полная цена

		this.m_modelName 			= m_carModal.find("#car-model");
		this.m_modelImg 			= m_carModal.find("#car-img");
		this.m_complectCode 		= m_carModal.find("#car-complect-code");
		this.m_complectName 		= m_carModal.find("#car-complect-name");
		this.m_complectPrice 		= m_carModal.find("#car-base-price");
		this.m_motorType 			= m_carModal.find("#car-motor-type");
		this.m_motorSize 			= m_carModal.find("#car-motor-size");
		this.m_motorTransmission 	= m_carModal.find("#car-motor-transmission");
		this.m_motorWheel 			= m_carModal.find("#car-motor-wheel");
		this.m_fullPrice 			= m_carModal.find("#car-full-price");
		this.m_options				= m_carModal.find("#complect-option");
	}

	clear(){
		this.m_modelName.html('');
		this.m_modelImg.attr('src','');
		this.m_complectCode.html('');
		this.m_complectName.html('');
		this.m_complectPrice.html('');
		this.m_motorType.html('');
		this.m_motorSize.html('');
		this.m_motorTransmission.html('');
		this.m_motorWheel.html('');
		this.m_fullPrice.html('');
		this.m_options.html('');
		m_complect.html('');
		m_packblock.html('')
		m_colorsblock.html('')
	}

	totalPrice()//подсчёт полной цены
	{
		this.total = parseInt(this.base)+parseInt(this.pack)+parseInt(this.dops);
		this.m_fullPrice.html(number_format(this.total,0,'',' ')+' р.');
	}

	checkPack(){
		this.pack = 0;
		let link = this;
		m_carModal.find('.car-pack-block input').each(function(i,item){			
			if($(this).prop('checked')){
				link.pack += parseInt($(this).attr('price'));
			}
		})
		this.pack = link.pack;
		this.totalPrice();
	}
}

var info = new carInfo();

let m_packblock 			= m_carModal.find('.pack-block');//блок в котором находятся пакеты
let m_colorsblock			= m_carModal.find('#car-color'); //блок в котором находятся цвета


/***************************************************************************/


//изменение модели
m_model.change(function(){
	val = m_model.val();
	getComplects(val);
})
//изменение комплектации
m_complect.change(function(){
	val = m_complect.val();
	InfoCar(val);
	getMotor(val);
	getPacks(val);
	getColor(val);	
})
//кнопка подробнее
m_carmore.click(function(){
	if(!m_carmore.attr('status')){
		getComplectInfo();
		m_carmore.attr('status',1)
		m_carmore.html("Закрыть")
	}
	else{
		info.m_options.html("")
		m_carmore.removeAttr('status')
		m_carmore.html("Подробнее")
	}
})
//выбираем цвет
m_carModal.on('click','.color-btn',function(){
	info.m_modelImg.css('background',$(this).attr('color-code'));
})
//выбираем пакет
m_carModal.on('change','.car-pack-block input',function(){
	info.checkPack();
})




/***************************************************************************/


function getComplectInfo()
{
	var parameters = {'complect_id':m_complect.val()}
	var url = '/complect/option'
	$.when(
		ajax(parameters,url)
			.then(function(data){
				info.m_options.html("")
				var opts = JSON.parse(data)
				for(i in opts)
					info.m_options.append(opts[i].name+"<br>")
			}
		)
	)
}
function InfoCar(val)
{	
	var parameters = {'id':val};
	var url = '/complectprice';
	$.when(
		ajax(parameters,url)
			.then(function(data){
				complect = JSON.parse(data);
				m_carModal.find('.loc-city').val(complect.model.country.city)
				info.base = complect.price;
				info.m_fullPrice.html(number_format(complect.price,0,'',' ')+' р.');
				info.m_modelName.html(complect.model.name);
				info.m_complectCode.html(complect.code);
				info.m_complectName.html(complect.name);
				info.m_complectPrice.html(number_format(complect.price,0,'',' ')+' р.');
				info.m_modelImg.attr('src','/storage/images/'+complect.model.link+'/'+complect.model.alpha);
			})
	)
}
function InfoCarLoader(val)
{	
	var parameters = {'id':val};
	var url = '/complectprice';
	$.when(
		ajax(parameters,url)
			.then(function(data){
				complect = JSON.parse(data);
				//info.base = complect.price;
				//info.m_fullPrice.html(number_format(complect.price,0,'',' ')+' р.');
				info.m_modelName.html(complect.model.name);
				info.m_complectCode.html(complect.code);
				info.m_complectName.html(complect.name);
				info.m_complectPrice.html(number_format(complect.price,0,'',' ')+' р.');
				info.m_modelImg.attr('src','/storage/images/'+complect.model.link+'/'+complect.model.alpha);
			})
	)
}
//получение мотора
function getMotor(val)
{
	var parameters = {'complect_id':val};
	var url = '/getmotor';
	$.when(
		ajax(parameters,url)
			.then(function(data) {
				makeMotorInfo(JSON.parse(data))
			})
	)
}
//получение комплектаций
function getComplects(val)
{
	var parameters = {'id':val};
	var url = '/getcomplects';
	$.when(
		ajax(parameters,url)
			.then(function(data) {
				makeOption(JSON.parse(data),m_complect)
			})
	)
}
//получение пакетов
function getPacks(val)
{
	var parameters = {'complect_id':val};
	var url = '/getpacks';
	$.when(
		ajax(parameters,url)
			.then(function(data){
				makePacks(JSON.parse(data))
			})
	)
}
//получение цаетов
function getColor(val)
{
	var parameters = {'complect_id':val};
	var url = '/getcolor';
	$.when(
		ajax(parameters,url)
			.then(function(data){
				makeColors(JSON.parse(data))
			})
	)
}


/*********************************************************************************************/


//рисуем мотор
function makeMotorInfo(data)
{
	for(i in data)
	{
		info.m_motorType.html('Двигатель '+data[i].type.name+' '+data[i].valve+'-клапанный');
		info.m_motorSize.html('Рабочий объем '+data[i].size+'л. ('+data[i].power+'л.с.)');
		info.m_motorTransmission.html('КПП '+data[i].transmission.name);
		info.m_motorWheel.html('Привод '+data[i].wheel.name);
	}
}
//рисуем цвета
function makeColors(data)
{	
	m_colorsblock.html('');
	let str = '';
	for(i in data)
		str += getcolorString(data[i])
	m_colorsblock.append(str);
}
//возвращает html кнопку с цветов указаным в параметре
function getcolorString(obj)
{
	str = '<input '+ 
		'style="background:'+obj.web_code+'" '+
		'value="'+obj.id+'" '+
		'name="color_id" '+
		'class="color-btn"'+
		'color-name="'+obj.name+'" '+
		'type="radio" '+
		'color-id="'+obj.id+'" '+
		'color-code="'+obj.web_code+'">'
	return str;
}
//рисуем пакеты
function makePacks(data)
{	
	m_packblock.html('');
	var str = '';
	for(obj in data)
		str += getPackString(data[obj]);
	$('.pack-block').html(str);
}
//возвращает строку html с указанным пакетом
function getPackString(obj){
	var str = '<div class="input-group no-gutters pack-area">';

		if(obj.name!=null) str += '<div class="col-12 pack-name">'+obj.name+'</div>';
		else str += '<div class="col-12"></div>';

		str += '<div class="pack-desc">';
			for(i in obj.option)
				str += obj.option[i].option.name+', '
		str += '</div>';

		str += '<div class="col-12 d-flex no-gutters">';
			str += '<div class="col-2">';
				str += '<div class="check car-pack-block">';
					str += '<label><input type="checkbox" code="'+obj.code+'" price="'+obj.price+'" name="packs[]" value="'+obj.id+'"></label>';
				str += '</div>';
			str += '</div>';

			str += '<div class="col-4">';
				str += obj.code;
			str += "</div>";

			str += '<div class="col-6 h5 text-right">';
				str += number_format(obj.price,0,'',' ')+' руб.';
			str += '</div>';
		str += '</div>';
	str += '</div>';
	return str;
}
//рисует опшоны у селекта, объекта указанного в параметре обж
function makeOption(data,obj)
{	
	obj.html('');
	obj.append('<option selected disabled>'+'Комплектации');
	for(i in data)
		obj.append('<option value="'+data[i].id+'">'+data[i].name+' '+data[i].fullname);
}



function zeroVal()
{
	info = new carInfo();
}

m_model.on('change',function(){
	zeroVal();
})

m_complect.on('change',function(){
	zeroVal();
})




//сохранение машины
$(document).on('click','#savecar',function(){
	var data = $("#autocardModal form").serializeArray();
	$.ajax({
		type: 'POST',
		url: '/create/car',
        data: data,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data){
	    	getContent($('#stock-tab'));
	    },
	    error: function(){

	    }
	})
})

m_carModal.on('shown.bs.modal',function(){
	m_carModal.find('input').val('')
	info.clear();
	m_carModal.find('form').trigger('reset');
})

//добавить детализацию скидки
m_carModal.on('click','#discount-adder',function(){
	var newBlock = $(this).closest('.discount-block').find('.item').clone();
	newBlock.removeClass('item')
	newBlock.find('[name="dc_type[]"]').val('')
	newBlock.find('[name="dc_sale[]"]').val('')
	$(this).closest('.discount-block').find('.discount-content').append(newBlock)
})

//добавить отсрочку обеспечения
m_carModal.on('click','#provision-adder',function(){
	var newBlock = $(this).closest('.provision-block').find('.item').clone();
	newBlock.removeClass('item')
	newBlock.find('input').val('')
	newBlock.find('.calendar').removeClass('hasDatepicker')
	newBlock.find('.calendar').attr('id','')
	newBlock.find('input').css('border-top','0px')
	newBlock.find('.checkdate').remove()
	newBlock.find('svg').remove()
	newBlock.append('<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>')
	$(this).closest('.provision-block').find('.provision-content').append(newBlock)
	$(this).closest('.provision-block').find('.provision-content').find('.calendar').datepicker()
})

//Добавить дату планирования
m_carModal.on('click','#add-plan-date',function(){
	//var newInput = '<input type="text" name="date_planned[]" class="form-control">'
	var parent = $(this).closest('.input-group').find('.item').parent()
	var newInput = parent.find('.item').clone()
	newInput.removeClass('item')
	newInput.find('input').removeClass('hasDatepicker')
	newInput.find('input').attr('id','')
	newInput.find('input').css('border-top','0px')
	newInput.find('input').val('')
	newInput.find('.checkdate').remove()
	newInput.find('svg').remove()
	newInput.append('<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>')
	parent.append(newInput)
	parent.find('input').datepicker()
})

//добавить дату отгрузки
m_carModal.on('click','#add-ship-date',function(){
	//var newInput = '<input type="text" name="date_ship[]" class="form-control">'
	var parent = $(this).closest('.input-group').find('.item').parent()
	var newInput = parent.find('.item').clone()
	newInput.removeClass('item')
	newInput.find('input').removeClass('hasDatepicker')
	newInput.find('input').attr('id','')
	newInput.find('input').css('border-top','0px')
	newInput.find('input').val('')
	newInput.find('.checkdate').remove()
	newInput.find('svg').remove()
	newInput.append('<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>')
	parent.append(newInput)
	parent.find('input').datepicker()
})

m_carModal.on('change','[name="logist_marker"]',function(){
	$('.marker_date').val(getCurrentDate())
})
m_carModal.on('click','.clearer-marker',function(){
	$('[name="logist_marker"]').val(0)
	$('.marker_date').val('')
})

m_carModal.on('keyup','[name="st_delay[]"]',function(e){
	let text = 0
	if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57) )
		text = text
	else
		$(this).val($(this).val().substring(0,$(this).val().length-1))
	if($(this).val().length>0)
		text = $(this).val()
	var date
	if($("[name='receipt_date']").val().length==0)
		date = new Date()
	else
	{
		var str = $("[name='receipt_date']").val()
		str = str.split('.')
		date = new Date(str[2],(str[1]-1),str[0])
	}
	
	date.setDate(date.getDate() + parseInt(text));
	var day = date.getDate()
	var mon = date.getMonth()+1
	var year = date.getFullYear()
	if(day<10)
		day='0'+day
	if(mon<10)
		mon='0'+mon
	var strDate = [day,mon,year].join('.')
	$(this).closest('.row').find('[name="st_date[]"]').val(strDate)
})



m_carModal.on('click','[name="st_date[]"]',function(){
	let obj = $(this)
	var countDays = obj.parent().find('[name="st_delay[]"]')

	var start_date = $("[name='receipt_date']").val()
	start_date = start_date.split('.')
	let SDate = new Date(start_date[2],start_date[1]-1,start_date[0])

	var mutationObserver = new MutationObserver(function(mutations) {
	  mutations.forEach(function(mutation) {
	    var check_date = obj.val()
	    check_date = check_date.split('.')
	    let CDate = new Date(check_date[2],check_date[1]-1,check_date[0])

	    countDays.val((CDate-SDate)/1000/60/60/24)

	  });
	});

	mutationObserver.observe(document.documentElement, {
	  attributes: true,
	  characterData: true,
	  childList: true,
	  subtree: true,
	  attributeOldValue: true,
	  characterDataOldValue: true
	});
})

m_carModal.on('keyup','[name="actual_purchase"]',function(){
	var text = nonSpace($(this).val())
	var format = number_format(text,0,'',' ')
	var estimated = nonSpace($('[name="estimated_purchase"]').val())
	$(this).val(format)
	$('[name="shipping_discount"]').val(number_format(((-1)*(estimated-text)),0,'',' '))
})


//ВВОД СКИДОК И ИХ КОЭФФИЦИЕНТОВ
m_carModal.on('keyup','[name="dc_sale[]"], [name="dc_pack_percent[]"]',function(e){
	if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57) || e.which == 8 || e.which==46 || (e.which>=37 || e.which<=40) || e.which==110)
	{
		makeDiscountDetails()
	}
	else{
		$(this).val($(this).val().substring(0,$(this).val().length-1))
		makeDiscountDetails()
	}
})

//открыть машину
$(document).on('click','.opencar',function(){
	selcarId = $(this).attr('car-id')

	info = new carInfo();
	
	m_carModal.find('[name="date_planned[]"]').each(function(){
		if(!$(this).parent().hasClass('item'))
			$(this).parent().remove()
	})
	m_carModal.find('[name="date_ship[]"]').each(function(){
		if(!$(this).parent().hasClass('item'))
			$(this).parent().remove()
	})
	m_carModal.find('[name="st_date[]"]').each(function(){
		if(!$(this).parent().hasClass('item'))
			$(this).parent().remove()
	})
	m_carModal.find('[name="dc_sale[]"]').each(function(){
		if(!$(this).closest('.item-block').hasClass('item'))
			$(this).closest('.item-block').remove()
	})


	m_carModal.modal('show');
	var parameters = {'id':$(this).attr('car-id')};
	var url = '/car/open';
	$.when(
		ajax(parameters,url)
			.then(function(data){
				data = JSON.parse(data)
				
				makeOption(data.complects,m_complect)
				InfoCarLoader(data.car.complect_id);
				info.base = parseInt(data.car.complect.price);
				info.pack = parseInt(data.car.packprice);
				info.dops = (data.car.dopprice!=null)?parseInt(data.car.dopprice):0;
				info.totalPrice();
				getMotor(data.car.complect_id);
				makePacks(data.packs);
				makeColors(data.colors);

				m_carModal.find('.loc-city').val(data.car.model.country.city)

				for (i in data.car)
				{	
					var current = data.car[i]
					var elem = m_carModal.find('[name="'+i+'"]')
					var tag = '';

					if(i=='provision')
						m_carModal.find('[name="st_provision"]').val(current)

					if(elem[0]) 
						tag = elem[0].tagName.toLowerCase();


					if (typeof(current)=='string' || typeof(current)=='number')
					{
						if(tag=='input'){//если инпут
							if(elem.attr('type')=='text' || elem.attr('type')=='hidden')
								if(~i.indexOf('date'))
									elem.val(timeConverter(current,'d.m.y'))
								else if(~i.indexOf('purchase') || (i=='shipping_discount'))
									elem.val(number_format(current,0,'',' '));
								else
									elem.val(current);//записываю в него

							if(elem.attr('type')=='time')
								elem.val(current);//записываю в него

							if(elem.attr('type') == 'radio')
								$('[name="'+i+'"]').each(function(){
									if($(this).val()==current)
										$(this).click()
								})
						}

						if(tag=='select')
						{
							elem.val(current);
						}
					}
					if(typeof(current)=='object' || typeof(current)=='array')
					{	
						if(~i.indexOf('get_'))
						{	
							elem = m_carModal.find('[name="'+i.substring(4)+'"]')
							if(elem[0]) 
								tag = elem[0].tagName.toLowerCase();

							if(tag == 'input' && elem.attr('type') == 'text')
							{
								if(~i.indexOf('date') && current != undefined)
								{
									elem.val(timeConverter(current.date,'d.m.y'))
								}
							}
							if(i=="get_date_planned_all")//получаю и клонирую блоки планированой даты
							{	
								var parent = m_carModal.find('[name="date_planned[]"]:first').parent()
								var container = parent.parent()
								container.html('')
								for(z in current)
								{	
									if(z==0)
									{	
										parent.find('input').val(timeConverter(current[z].date))
										parent.addClass('item')
										container.append(parent)
									}
									else
									{	
										var clone = container.find('.item').clone()
										clone.removeClass('item')
										clone.find('input').val(timeConverter(current[z].date))
										clone.find('input').css('border-top','0px')
										container.append(clone)
									}
								}
								container.find('input').removeClass('hasDatepicker').attr('id','').datepicker()
							}

							if(i=="get_date_ship_all")//получаю и клонирую блоки даты отгрузки
							{
								var parent = m_carModal.find('[name="date_ship[]"]:first').parent()
								var container = parent.parent()
								container.html('')
								for(z in current)
								{	
									if(z==0)
									{	
										parent.find('input').val(timeConverter(current[z].date))
										parent.addClass('item')
										container.append(parent)
									}
									else
									{	
										var clone = container.find('.item').clone()
										clone.removeClass('item')
										clone.find('input').val(timeConverter(current[z].date))
										clone.find('input').css('border-top','0px')
										container.append(clone)
									}
								}
							}

							if(i=='get_date_provision')//получаю и клонирую блоки даты отгрузки
							{	
								if(current.length)
								{
									var newBlock = m_carModal.find('.provision-content').find('.item-block:first').clone();
									var container = m_carModal.find('.provision-content')
									container.html('')
									
									for(z in current)
									{
										if(z==0)
										{
											newBlock.find('[name="st_delay[]"]').val(current[z].count_days)
											newBlock.find('[name="st_date[]"]').val(timeConverter(current[z].date_provision,'d.m.y'))
											newBlock.addClass('.item')
											container.append(newBlock)
										}
										else{
											var clone = container.find('.item').clone()
											clone.removeClass('item')
											clone.find('[name="st_delay[]"]').val(current[z].count_days)
											clone.find('[name="st_date[]"]').val(timeConverter(current[z].date_provision,'d.m.y'))
											container.append(clone)
										}
									}
								}
							}
							if(i=='get_discount')//получаю и клонирую блоки детализации скидки
							{	
								if(current.length)
								{
									var newBlock = m_carModal.find('.discount-block').find('.item-block:first').clone();
									var container = m_carModal.find('.discount-content')
									container.html('')
									for(z in current)
									{
										if(z==0)
										{	
											if(current[z].pack_percent)
												newBlock.find('[name="dc_pack_percent[]"]').val(current[z].pack_percent)
											else
												newBlock.find('[name="dc_pack_percent[]"]').val(1)

											newBlock.find('[name="dc_type[]"]').val(current[z].type)
											newBlock.find('[name="dc_sale[]"]').val(number_format(current[z].sale,0,'',' '))
											newBlock.addClass('item')
											container.append(newBlock)
											
										}
										else
										{	
											var clone = m_carModal.find('.discount-block').find('.item').clone();
											clone.removeClass('item')
											if(current[z].pack_percent)
												clone.find('[name="dc_pack_percent[]"]').val(current[z].pack_percent)
											else
												clone.find('[name="dc_pack_percent[]"]').val(1)
											clone.find('[name="dc_type[]"]').val(current[z].type)
											clone.find('[name="dc_sale[]"]').val(number_format(current[z].sale,0,'',' '))
											container.append(clone)
										}
									}
								}
							}
						}
						if(i=='packs')
						{
							$('[name="packs[]"]').each(function(){
								for (k in current)
								{
									if($(this).val()==current[k].pack_id)
									{
										$(this).trigger('click');
									}
								}
							})
							info.totalPrice()
						}
						if(i=='dops')
						{
							$('[name="dops[]"]').each(function(){
								for (k in current)
									if($(this).val()==current[k].dop_id)
										$(this).prop('checked',true);	
							})
						}
					}
				}
				/*РАСЧЁТ РАСЧЁТНОГО ЗАКУПА*/
				var totalSum = data.car.complect.price+data.car.packprice
				var percentCom = data.car.complect.price/100*data.car.complect.percent
				var percentPack = data.car.complect.pack_percent/100*data.car.complect.pack_percent
				var estimated_purchase = (totalSum - percentCom - percentPack)

				var masPurch = String(estimated_purchase).split('.')
				var resPurch = ''
				if(masPurch[0])
					resPurch = number_format(masPurch[0],0,'', ' ')
				$('[name="estimated_purchase"]').val(resPurch)

				//ДЕТАЛИЗИРУМЕ СКИДКИ
				if(!data.car.detail_discount)
					makeDiscountDetails()

				//ВСЕМ ДАТАМ ИЗМЕНЯЕМ ИКОНКУ
				$('.checkdate').each(function(){
					var input = $(this).parent().find('.calendar')
					input.removeClass('hasDatepicker')
					input.attr('id','')
					input.datepicker()
					var parent = $(this).parent()
					if(input.val()!=''){					
						parent.find('.checkdate').remove()
						parent.find('svg').remove()
						parent.append('<a class="input-icon checkdate checkdate-block fa fa-times" status="2" aria-hidden="true"></a>')
					}
					else{
						parent.find('.checkdate').remove()
						parent.find('svg').remove()
						parent.append('<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>')
					}
				})
			}
		)
	)
})

//СЧИТАЕТ ДЕТАЛИЗАЦИЮ СКИДКИ
function makeDiscountDetails(){
	var discount = 0;
	m_carModal.find('[name="dc_sale[]"]').each(function(){
		
		var val = parseFloat(nonSpace($(this).val()))
		if($(this).val()=='')
			val = 0

		var percent = parseFloat($(this).parent().find('[name="dc_pack_percent[]"]').val())
		if($(this).parent().find('[name="dc_pack_percent[]"]').val()=='')
			percent = 0

		discount += parseFloat(val * percent)
		m_carModal.find('[name="detail_discount"]').val(discount)
	})
}

})();