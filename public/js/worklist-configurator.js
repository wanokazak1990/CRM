// var cfg_base = 0;//база
// var cfg_pack = 0;//пакеты
// var cfg_dops = 0;//допы
// var cfg_total = 0;


function totalPriceCfg(obj)//подсчёт полной цены
{
	var price = obj.closest('.wl_cfg_cars').find("#price");
	//cfg_total = parseInt(cfg_base)+parseInt(cfg_pack)+parseInt(cfg_dops);
	cfg_total = parseInt(price.attr('base'))+parseInt(price.attr('pack'))+parseInt(price.attr('dops'));
	obj.closest('.wl_cfg_cars').find("#cfg-full-price").html(cfg_number_format(cfg_total,0,'',' '));
}

function cfg_number_format(number,decimals,dec_point,thousands_sep)
{
	var i,j,kw,kd,km;
	if(isNaN(decimals=Math.abs(decimals))){
		decimals=2;
	}

	if(dec_point==undefined){
		dec_point=",";
	}
	
	if(thousands_sep==undefined){
		thousands_sep=".";
	}

	i=parseInt(number=(+number||0).toFixed(decimals))+"";
	
	if((j=i.length)>3){
		j=j%3;
	}
	else{
		j=0;
	}

	km=(j?i.substr(0,j)+thousands_sep:"");
	kw=i.substr(j).replace(/(\d{3})(?=\d)/g,"$1"+thousands_sep);
	kd=(decimals?dec_point+Math.abs(number-i).toFixed(decimals).replace(/-/,0).slice(2):"");
	return km+kw+kd;
}

function getComplectsCfg(elem,pastle=0)
{	
	var model_id = elem.val();
	log(model_id);
	$.ajax({
		type: "POST",
		url: "/getcomplects",
		data: {'id':model_id},
		headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			var objs = JSON.parse(param);
			//if(pastle==0) $('[name="cfg_complect"]').html('');
			if(pastle==0) elem.closest('.wl_cfg_cars').find('.cfg_complect').html('');
			else pastle.html("");
			var str = '';
			str += '<option selected disabled>Укажите параметр</option>';
			objs.forEach(function(obj,i){
				str += '<option value="'+obj.id+'">';
					str += obj.fullname;
				str += '</option>';
			});
			//if(pastle==0) $('[name="cfg_complect"]').html(str);
			if(pastle==0) elem.closest('.wl_cfg_cars').find('.cfg_complect').html(str);
			else pastle.html(str);
		},
		error: function(param)
		{
			log('error123');
		}
	})
}

function modelObjCfg(obj)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',obj.val());
	$.ajax({
		async: false,
        type: "POST",
        url: '/getmodel',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			obj.closest('.wl_cfg_cars').find("#cfg-model").html(param.name);
			obj.closest('.wl_cfg_cars').find("#cfg-img").attr('src','/storage/images/'+param.link+'/'+param.alpha);
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = '+val);
		}
	});
	return result;
}

function getColorCfg(elem)//вернёт плитку цветов с инпутом 
{
	var formData = new FormData();
	formData.append('complect_id',elem.val());
    $.ajax({
        type: "POST",
        url: '/getcolor',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
        success: function(param)
        {	var mother = elem.closest('.wl_cfg_cars').find("#cfg-color");
        	var str = '';
        	mother.html("");
        	param.forEach(function(obj,i){
	        	
	        	str += '<button style="width:30px;height:30px;background:'+obj.web_code+'" class="btn" color-name="'+obj.name+'" type="button" color-id="'+obj.id+'" color-code="'+obj.web_code+'">&nbsp</button>';
	        	
        	})
            mother.html(str);
        },
        error: function(msg){
            console.log('error');
        }
    });
}

function getPackStringCfg(obj){
	var str = '<div class="input-group no-gutters">';

		if(obj.name!=null) str += '<div class="col-12">'+obj.name+'</div>';
		else str += '<div class="col-12"></div>';

		str += '<div class="pack-desc">'+obj.optionlist+'</div>';

		str += '<div class="col-12 d-flex no-gutters">';
			str += '<div class="col-2">';
				str += '<div class="check">';
					str += '<label><input type="checkbox" code="'+obj.code+'" price="'+obj.price+'" name="packs[]" value="'+obj.id+'"></label>';
				str += '</div>';
			str += '</div>';

			str += '<div class="col-2">';
				str += obj.code;
			str += "</div>";

			str += '<div class="col-6 h5 text-right">';
				str += cfg_number_format(obj.price,0,'',' ')+' руб.';
			str += '</div>';
		str += '</div>';
	str += '</div>';
	return str;
}

function getPacksCfg(elem)
{
	var formData = new FormData();
	formData.append('complect_id',elem.val());
    $.ajax({
        type: "POST",
        url: '/getpacks',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			elem.closest('.wl_cfg_cars').find('#cfg-pack-block').html('');
			var str = '';
			param.forEach(function(obj,i){
				str += getPackStringCfg(obj);
			});
			elem.closest('.wl_cfg_cars').find('#cfg-pack-block').html(str);
			/*изменение подсветки стилизованых чекбоксов*/
			$(document).on('click','#cfg-pack-block input',function(){
				if($(this).prop('checked')) {
					$(this).closest('label').addClass('green-check');
					//cfg_pack += parseInt($(this).attr('price'));
					price = elem.closest('.wl_cfg_cars').find('#price');
					sum = parseInt(price.attr('pack')) + parseInt($(this).attr('price'));
					price.attr('pack', sum); 
					totalPriceCfg(elem);
				} else {
					$(this).closest('label').removeClass('green-check');
					//cfg_pack -= parseInt($(this).attr('price'));
					price = elem.closest('.wl_cfg_cars').find('#price');
					diff = parseInt(price.attr('pack')) - parseInt($(this).attr('price'));
					price.attr('pack', diff); 
					totalPriceCfg(elem);
				}
			})

		},
		error: function(param)
		{
			log('error');
		}
	})
}

function getMotorCfg(obj, id)
{
	var formData = new FormData();
	formData.append('complect_id',id);
	$.ajax({
        type: "POST",
        url: '/getmotor',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data){
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-type").html('Двигатель '+data[0].type.name+' '+data[0].valve+'-клапанный');
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-size").html('Рабочий объем '+data[0].size+'л. ('+data[0].power+'л.с.)');
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-transmission").html('КПП '+data[0].transmission.name);
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-wheel").html('Привод '+data[0].wheel.name);
	    },
	    error: function(){
	    	log('Не смог получить стоимость комплектации id = '+id);
	    }
	})
}

function complectPriceCfg(obj)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',obj.val());
	$.ajax({
		async: false,
        type: "POST",
        url: '/complectprice',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			getMotorCfg(obj, param.id);
			obj.closest('.wl_cfg_cars').find("#cfg-complect-code").html('Исполнение '+param.code);
			obj.closest('.wl_cfg_cars').find("#cfg-complect-name").html('Комплектация '+param.name);
			obj.closest('.wl_cfg_cars').find("#cfg-base-price").html(cfg_number_format(param.price,0,'',' '));
			result = param.price;
		},
		error: function(param)
		{
			log('Не смог получить стоимость комплектации id = '+id);
		}
	});
	return result;
}



//клик по цветам
$(document).on('click','#cfg-color button',function(){
	$(this).closest('.wl_cfg_cars').find('#cfg-img').css('background',$(this).attr('color-code'));
	$(this).closest('.wl_cfg_cars').find('#cfg_color_id').val($(this).attr('color-id'));
	$(this).closest('.wl_cfg_cars').find("#cfg-color button").each(function(){$(this).removeClass('active')})
	$(this).addClass('active');
})


/*клик по кнопке подробнее*/
$(document).on('click','.cfg-more',function(){
	var obj = $(this);
	if(!obj.attr('action')){
		var current_complect = obj.closest('.wl_cfg_cars').find('.cfg_complect').val();
		var formData = new FormData();
		obj.attr('action','1');
		obj.html('Закрыть');
		formData.append('complect_id',current_complect);
		$.ajax({
			type: "POST",
	        url: '/complect/option',
	        dataType : "json", 
	        cache: false,
	        contentType: false,
	        processData: false, 
	        data: formData,
	        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			success: function(param){
				obj.closest('.wl_cfg_cars').find("#cfg-complect-option").html("");
				param.forEach(function(item,i){
					obj.closest('.wl_cfg_cars').find("#cfg-complect-option").append("<div>"+item.name+"</div>")
				})
			},
			error: function(){

			}
		})
	}
	else{
		obj.html('Подробнее');
		obj.closest('.wl_cfg_cars').find("#cfg-complect-option").html("");
		obj.removeAttr('action');
	}
})


/*получаем комплектации от модели*/
$(document).on('change', '.cfg_model', function(){
	getComplectsCfg($(this));
	modelObjCfg($(this));
});

/*получаем пакеты цвета и цену стартовую*/
$(document).on('change','.cfg_complect',function(){
	$(this).closest('.wl_cfg_cars').find("#price").attr('pack', 0);
	$(this).closest('.wl_cfg_cars').find("#price").attr('dops', 0);

	$(this).closest('.wl_cfg_cars').find("#cfg-pack-block input").each(function(){
		$(this).prop('checked',false);
	});
	$(this).closest('.wl_cfg_cars').find('#price').attr('base', complectPriceCfg($(this)));
	totalPriceCfg($(this));
	getPacksCfg($(this));
	getColorCfg($(this));

	$(this).closest('.wl_cfg_cars').find('.display').removeAttr('style');

	$('#wl_cfg_count').html($('.wl_cfg_cars').length);
});

// Добавление нового блока
$(document).on('click', '#wl_cfg_add', function() {
	clone = $('#wl_cfg_car_blocks').find('.wl_cfg_cars').first().clone();
	clone.find('.display').css('display', 'none');
	clone.find('#cfg-img').attr('src', '');
	clone.find('#cfg-img').css('background-color', '');
	clone.find('.clear-html').html('');
	clone.find('.cfg_model option:first').prop('selected', true);
	var option = clone.find('.cfg_complect option:first').clone();
	clone.find('.cfg_complect option').remove();
	clone.find('.cfg_complect').append(option);
	clone.appendTo('#wl_cfg_car_blocks');
});

// Удаление блока
$(document).on('click', '.wl_cfg_del', function() {
	if ($('.wl_cfg_cars').length > 1)
	{
		$(this).closest('.wl_cfg_cars').remove();
		$('#wl_cfg_count').html($('.wl_cfg_cars').length);
	}
	else
	{
		$(this).closest('.wl_cfg_cars').find('.display').css('display', 'none');
		$(this).closest('.wl_cfg_cars').find('#cfg-img').attr('src', '');
		$(this).closest('.wl_cfg_cars').find('#cfg-img').css('background-color', '');
		$(this).closest('.wl_cfg_cars').find('.clear-html').html('');
		$(this).closest('.wl_cfg_cars').find('.cfg_model option:first').prop('selected', true);
		var option = $(this).closest('.wl_cfg_cars').find('.cfg_complect option:first').clone();
		$(this).closest('.wl_cfg_cars').find('.cfg_complect option').remove();
		$(this).closest('.wl_cfg_cars').find('.cfg_complect').append(option);
		$('#wl_cfg_count').html('0');
	}
});