var base = 0;//база
var pack = 0;//пакеты
var dops = 0;//допы
var total = 0;


function totalPrice()//подсчёт полной цены
{
	total = parseInt(base)+parseInt(pack)+parseInt(dops);
	$("#car-full-price").html(number_format(total,0,'',' '));
}






function number_format(number,decimals,dec_point,thousands_sep)
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









function getComplects(elem,pastle=0)
{	
	var model_id = elem.val();
	$.ajax({
		type: "GET",
		url: "/getcomplects",
		data: {'model_id':model_id},
		success: function(param){
			var objs = JSON.parse(param);
			if(pastle==0) $('[name="complect_id"]').html('');
			else pastle.html("");
			var str = '';
			str += '<option selected disabled>Укажите параметр</option>';
			objs.forEach(function(obj,i){
				str += '<option value="'+obj.id+'">';
					str += obj.fullname;
				str += '</option>';
			});
			if(pastle==0) $('[name="complect_id"]').html(str);
			else pastle.html(str);
		},
		error: function(param)
		{
			log('error');
		}
	})
}

function modelObj(val)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',val);
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
			$("#car-model").html(param.name);
			$("#car-img").attr('src','/storage/images/'+param.link+'/'+param.alpha);
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = '+val);
		}
	});
	return result;
}



function getColor(id)//вернёт плитку цветов с инпутом 
{
	var formData = new FormData();
	formData.append('complect_id',id)
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
        {	var mother = $("#car-color");
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



function getPackString(obj){
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
				str += number_format(obj.price,0,'',' ')+' руб.';
			str += '</div>';
		str += '</div>';
	str += '</div>';
	return str;
}



function getPacks(elem)
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
			$('.pack-block').html('');
			var str = '';
			param.forEach(function(obj,i){
				str += getPackString(obj);
			});
			$('.pack-block').html(str);
			/*изменение подсветки стилизованых чекбоксов*/
			$(document).on('click','.pack-block input',function(){
				if($(this).prop('checked')){
					$(this).closest('label').addClass('green-check');
					pack += parseInt($(this).attr('price'));
					totalPrice();
				}
				else{
					$(this).closest('label').removeClass('green-check');
					pack -= parseInt($(this).attr('price'));
					totalPrice();
				}
			})

		},
		error: function(param)
		{
			log('error');
		}
	})
}



function getMotor(id)
{
	var formData = new FormData();
	formData.append('id',id);
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
	    	$("#car-motor-type").html('Двигатель '+data.type.name+' '+data.valve+'-клапанный');
	    	$("#car-motor-size").html('Рабочий объем '+data.size+'л. ('+data.power+'л.с.)');
	    	$("#car-motor-transmission").html('КПП '+data.transmission.name);
	    	$("#car-motor-wheel").html('Привод '+data.wheel.name);
	    },
	    error: function(){
	    	log('Не смог получить стоимость комплектации id = '+id);
	    }
	})
}





function complectPrice(id)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',id);
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
			getMotor(param.motor_id);
			$("#car-complect-code").html('Исполнение '+param.code);
			$("#car-complect-name").html('Комплектация '+param.name);
			$("#car-base-price").html(number_format(param.price,0,'',' '));
			$("#car-option").html(0);
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
$(document).on('click','#car-color button',function(){
	$('#car-img').css('background',$(this).attr('color-code'));
	$('#color_id').val($(this).attr('color-id'));
	$("#car-color button").each(function(){$(this).removeClass('active')})
	$(this).addClass('active');
})







/*клик по кнопке подробнее*/
$(document).on('click','#car-more',function(){
	if(!$(this).attr('action')){
		var current_complect = $(this).closest('form').find('[name="complect_id"]').val();
		var formData = new FormData();
		$(this).attr('action','1');
		$(this).html('Закрыть');
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
				$("#complect-option").html("");
				param.forEach(function(item,i){
					$("#complect-option").append("<div>"+item.name+"</div>")
				})
			},
			error: function(){

			}
		})
	}
	else{
		$(this).html('Открыть');
		$("#complect-option").html("");
		$(this).removeAttr('action');
	}
})














/*получаем комплектации от модели*/
$('select[name="model_id"]').change(function(){//
	getComplects($(this));
	modelObj($(this).val());
});





/*получаем пакеты цвета и цену стартовую*/
$(document).on('change','select[name="complect_id"]',function(){
	pack = 0;
	dops = 0;
	$("#pack-block input").each(function(){
		$(this).prop('checked',false);
	})
	base = complectPrice($(this).val());
	totalPrice();
	getPacks($(this));
	getColor($(this).val());			
});



$(document).on('click','#savecar',function(){
	var data = $("#autocardModal form").serialize();
	$.ajax({
		type: 'POST',
		url: '/create/car',
        data: data,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data){
	    	log(data);
	    },
	    error: function(){

	    }
	})
})