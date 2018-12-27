var URI = document.location.pathname;
url = URI.split('/');




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


//ЗАГРУЖАЮТСЯ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ ВСЕГДА

//АКТИВИРУЕМ ТУЛТИПЫ
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})




//выдать установленое значение ренджей лабелю
$('input[type="range"]').each(function(){
	setRange($(this));
})

$('body').on('mousemove','input[type="range"]',function(){
	setRange($(this));
})
function setRange(range)
{
	var label = range.parent().find('label');
	var labtext = label.html().split(':');
	label.html(labtext[0]+":"+range.val());
}


//Подсветка активных ссылок в меню
$(".left-menu ul a").each(function(){
	var origin = url[1];
	current = origin.slice(-4);
	current = current.replace('list','');
	current = current.replace('add','');
	current = current.replace('edit','');
	current = current.replace('del','');
	origin = '/'+origin.slice(0,-4)+current+'list';
	if($(this).attr('href').indexOf(origin) + 1) {
		$(this).addClass('active-menu');
		//log(origin+' --- '+$(this).attr('href'))
	}
})

//Подсвечиваем выбранные пакеты
$('.pack tr').each(function () {
	var check = $(this).find('input');
	if(check.prop("checked"))
		$(this).addClass('green-tr');
})

$('.dop label').each(function(){
	var check = $(this).find('input');
	if(check.prop("checked"))
		$(this).addClass('green-tr');
})

$('.option label').each(function(){
	var check = $(this).find('input');
	if(check.prop("checked"))
		$(this).addClass('green-tr');
})

$(".model label").each(function(){
	var check = $(this).find('input');
	if(check.prop("checked"))
		$(this).find("img").addClass('red-bg');
})

$(".color label").each(function(){
	var check = $(this).find('input');
	if(check.prop("checked"))
		$(this).addClass('green-tr');
})























//КЛИКИ ПО ИНПУТАМ ЭЛЕМЕНТАМ 
$('body').on('click','.pack tr',function(){
	var check = $(this).find('input');
	if(check.prop("checked"))
	{
		check.attr("checked",false);
		$(this).removeClass('green-tr');
	}
	else
	{
		check.attr("checked",true);
		$(this).addClass('green-tr');
	}
});



$('body').on('click','.dop label', function(){
	var check = $(this).find('input');
	if(!check.prop("checked"))
	{
		check.removeAttr("checked");
		$(this).removeClass('green-tr');
	}
	else
	{
		check.attr("checked","checked");
		$(this).addClass('green-tr');
	}
})

$('body').on('click','.option label', function(){
	var check = $(this).find('input');
	if(!check.prop("checked"))
	{
		check.removeAttr("checked");
		$(this).removeClass('green-tr');
	}
	else
	{
		check.attr("checked","checked");
		$(this).addClass('green-tr');
	}
})

$('body').on('click','.model label', function(){
	var check = $(this).find('input');
	if(!check.prop("checked"))
	{
		check.removeAttr("checked");
		$(this).find("img").removeClass('red-bg');
	}
	else
	{
		check.attr("checked","checked");
		$(this).find("img").addClass('red-bg');
	}
})

$('body').on('click','.color label', function(){
	var check = $(this).find('input');
	if(!check.prop("checked"))
	{
		check.removeAttr("checked");
		$(this).removeClass('green-tr');
	}
	else
	{
		check.attr("checked","checked");
		$(this).addClass('green-tr');
	}
})
















//ВЫЗЫВАЕМЫЕ ФУНКЦИИ 

function log(param)
{
	console.log(param);
}

function getColorIcon(code)
{
	var mas = code.split(',');
	var style = 'border:1px solid #ccc; width: 30px; height: 30px;';
	if(mas.length>1)
		style += ' background: linear-gradient('+mas[0]+' 50%,  '+mas[1]+' 50%)';
	else
		style += ' background: '+mas[0]+'"';
	return '<div style="'+style+'"></div>';
}






function getColor(elem,type='check')//вернёт плитку цветов с инпутом 
{
	var formData = new FormData();
	var brand_id = elem.val();
	if(elem.attr('name')=='brand_id')
	{
		formData.append('brand_id',brand_id);
	}
	if(elem.attr('name')=='model_id')
	{
		formData.append('model_id',brand_id);
	}
	if(elem.attr('name')=='complect_id')
	{
		formData.append('complect_id',brand_id);
	}
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
        {	
        	var str = '';
        	$(".color").html("");
        	//var objs = JSON.parse(param);
        	param.forEach(function(obj,i){
        		if(type=='check')
        		{
        			str += '<div class="col-sm-4">';
						str += '<label>';
							str += '<div class="">';
								str += '<div class="col-sm-1 pad-0">'
									str += '<input type="checkbox" name="color_id[]" value="'+obj.id+'"> ';
								str += '</div>';
								str += '<div class="col-sm-2">';
									str += getColorIcon(obj.web_code);
								str += '</div>';
								str += '<div class="col-sm-8 size-10" style="height: 30px;">';
									str += obj.name+' ('+obj.rn_code+')';
								str += '</div>';
							str += '</div>';
						str += '</label>';
					str += '</div>';
	        	}
	        	if(type=='radio')
        		{
	        		str += '<div class="col-sm-2">';
	        			str += '<div>'+obj.name+' ('+obj.rn_code+')'+'</div>';
	        			str += '<div style="border:1px solid #ccc;height:20px;background:'+obj.web_code+'"></div>';
	        			str += '<label><input type="radio" name="color_id[]" value="'+obj.id+'"> Использовать</label>';
	        		str += '</div>';
	        	}
        	})
            $(".color").html(str);
        },
        error: function(msg){
            console.log('error');
        }
    });
}












function getOption(elem)
{
	var brand_id = elem.val();
	$.ajax({
		type: "GET",
		url: "/getoption",
		data: {'brand_id':brand_id},
		success: function(param)
		{
			$('.option').html(param);
			$('[data-toggle="tooltip"]').tooltip();
		},
		error: function(msg){
            log('error');
        }
	})
}

function getModels(elem,type)
{
	var brand_id = elem.val();
	$.ajax({
		type: "GET",
		url: "/getmodels",
		data: {'brand_id':brand_id},
		success: function(param)
		{
			var objs = JSON.parse(param);
			$('.model').html("");
			var str = '';
			if(type=='string')
			{
				objs.forEach(function(obj,i){
					str += '<div class="col-sm-1">';
						str += '<label class="text-center">';
							str += '<img src="/storage/images/'+obj.link+'/'+obj.alpha+'">'
							str += "<input type='checkbox' name='pack_model[]' value='"+obj.id+"'>";
							str += obj.name;
						str += '</label>';
					str += '</div>';
				});
				$('.model').html(str);
			}
			if(type=='list')
			{
				str += '<option selected disabled>Укажите параметр</option>';
				objs.forEach(function(obj,i){
					str += '<option value="'+obj.id+'">';
						str += obj.name;
					str += '</option>';
				});
				$('.model').html(str);
			}
		},
		error: function(msg){
            log('error');
        }
	})
}








function getMotors(elem)
{
	var brand_id = elem.val();
	$.ajax({
		type: "GET",
		url: "/getmotors",
		data: {'brand_id':brand_id},
		success: function(param){
			var objs = JSON.parse(param);
			$('.motor').html('');
			var str = '';
			str += '<option selected disabled>Укажите параметр</option>';
			objs.forEach(function(obj,i){
				str += '<option value="'+obj.id+'">';
					str += obj.name;
				str += '</option>';
			});
			$('.motor').html(str);
		},
		error: function(param)
		{
			log('error');
		}
	})
}







function getPacks(elem)
{
	var formData = new FormData();
	var brand_id = elem.val();
	if(elem.attr('name')=='model_id')
	{
		formData.append('model_id',brand_id);
	}
	if(elem.attr('name')=='complect_id')
	{
		formData.append('complect_id',brand_id);
	}
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
			//log(param);
			//var objs = JSON.parse(param);
			$('.pack').html('');
			var str = '';
			str += '<tbody>';
			param.forEach(function(obj,i){
				str += '<tr>';
					str += '<td class="width-200 checkbox-td">';
						str += '<input type="checkbox" name="packs[]" value="'+obj.id+'">'
					
						str += (obj.name)?obj.name:'';
					str += '</td>';

					str += '<td class="width-150">';
						str += obj.code;
					str += '</td>';

					str += '<td class="width-200">';
						str += obj.price+' руб.';
					str += '</td>';

					str += '<td class="font-12">';
						str += obj.optionlist;
					str += '</td>';
				str += '</tr>';
			});
			str += '</tbody>';
			$('.pack').html(str);
		},
		error: function(param)
		{
			log('error');
		}
	})
}










function getComplects(elem,pastle=0)
{	log(pastle)
	var model_id = elem.val();
	$.ajax({
		type: "GET",
		url: "/getcomplects",
		data: {'model_id':model_id},
		success: function(param){
			var objs = JSON.parse(param);
			if(pastle==0) $('.complect').html('');
			else pastle.html("");
			var str = '';
			str += '<option selected disabled>Укажите параметр</option>';
			objs.forEach(function(obj,i){
				str += '<option value="'+obj.id+'">';
					str += obj.fullname;
				str += '</option>';
			});
			if(pastle==0) $('.complect').html(str);
			else pastle.html(str);
		},
		error: function(param)
		{
			log('error');
		}
	})
}









function changeSort(obj_select)
{
	var formData = new FormData();
	formData.append('sort',obj_select.val());
	formData.append('data_id',obj_select.attr('data-id'));
	formData.append('data_type',obj_select.attr('data-type'));
	 $.ajax({
        type: "POST",
        url: '/changesort',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			alert('Установлен новый порядок сортировки');
			location.reload(true);
		},
		error: function(param)
		{
			log('Не смог передать данные, объекта '+obj_select.name);
		}
	});
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
			$("#car-complect").html(param.name+' ('+param.code+')');
			$("#car-base").html(number_format(param.price,0,'',' '));
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



function packprice(id)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',id);
	$.ajax({
		async: false,
        type: "POST",
        url: '/packprice',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			$("#car-option").html(number_format(param,0,'',' '));
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = '+id);
		}
	});
	return result;
}


function dopPrice(val)
{
	$("#car-dop").html(number_format(val,0,'',' '));
	return val;
}

function brandObj(val)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',val);
	$.ajax({
		async: false,
        type: "POST",
        url: '/getbrand',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			$("#car-brand").html(param.name);
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = '+val);
		}
	});
	return result;
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

function colorObj(val)
{
	var result = '';
	var formData = new FormData();
	formData.append('color_id',val);
	$.ajax({
		async: false,
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
		success: function(param){
			$("#car-img").css('background',param.web_code);
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = '+val);
		}
	});
	return result;
}









/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
///////////////////////////РОУТЕР ДЛЯ JS/////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////

switch (url[1])
{	
	////////////////////
	//СОЗДАНИЕ МОДЕЛЕЙ//
	////////////////////
	case 'modellist' :
		$('.sort').change(function(){
			changeSort($(this));
		})
	break;

	case 'modeladd':
		$('select[name="brand_id"]').change(function(){
			getColor($(this));
		});
	break;

	case 'modeledit':
		var last_brand = $('select[name="brand_id"]').val();
		$('select[name="brand_id"]').change(function(){
			var res = confirm('При смене бренда удалятся все цвета, которые уже установлены на модели, продолжить?');
			if(res==true)
			{	
				getColor($(this));
				last_brand = $(this).val();
			}
			else
			{
				$(this).val(last_brand);
			}
		});
	break;





	//////////////////////////
	//СОЗДАНИЕ ПАКЕТОВ ОПЦИЙ//
	//////////////////////////
	case 'packadd':
		$('select[name="brand_id"]').change(function(){
			getOption($(this));
			getModels($(this),'string');
		});
	break;

	case 'packedit':
		var last_brand = $('select[name="brand_id"]').val();
		$('select[name="brand_id"]').change(function(){
			var res = confirm('При смене бренда удалятся все модели и оборудование бренда, которые уже включены в состав опции, продолжить?');
			if(res)
			{
				getOption($(this));
				getModels($(this),'string');
			}
			else
			{
				$(this).val(last_brand);
			}
		});
	break;







	/////////////////////////
	//СОЗДАНИЕ КОМПЛЕКТАЦИЙ//
	/////////////////////////
	case 'complectlist' :
		$(".sort").keypress(function(e) {
		    if(e.which == 13) {
		        changeSort($(this));		        
		    }
		});
	break;

	case 'complectadd':
		$('select[name="brand_id"]').change(function(){
			getModels($(this),'list');
			getMotors($(this));
			getOption($(this));
		});
		$('select[name="model_id"]').change(function(){
			getPacks($(this));
			getColor($(this));
		});
	break;

	case 'complectedit':
		$('select[name="brand_id"]').change(function(){
			getModels($(this),'list');
			getMotors($(this));
			getOption($(this));
			$(".pack").html("");
			$(".color").html("");
		});
		$('select[name="model_id"]').change(function(){
			getPacks($(this));
			getColor($(this));
		});
	break;




	///////////////////////
	//СОЗДАНИЕ АВТОМОБИЛЯ//
	///////////////////////
	case 'carlist':
		$('body').on('click',' #option_check',function(){
			$(".company-dop").css('display','block');
		});
		$('body').on('click','.close',function(){
			$(".company-dop").css('display','none');
		});
	break;

	case 'caradd':
		var base = 0;
		var pack = 0;
		var dops = 0;

		function totalPrice()
		{
			total = parseInt(base)+parseInt(pack)+parseInt(dops);
			$("#car-total").html(number_format(total,0,'',' '));
		}

		$('select[name="brand_id"]').change(function(){//получаем модели от бренда
			getModels($(this),'list');
			brandObj($(this).val());
		});
		$('select[name="model_id"]').change(function(){//получаем комплектации от модели
			getComplects($(this));
			modelObj($(this).val());
		});
		$(document).on('change','select[name="complect_id"]',function(){//получаем пакеты и цвета			
			base = complectPrice($(this).val());
			totalPrice();
			getPacks($(this));
			getColor($(this),'radio');			
		});

		
		$(document).on('click','.color input',function()
		{
			colorObj($(this).val());
		})


		$(document).on('click','.pack tr',function(){
			var id = '';
			$(".pack tr").each(function(){
				if($(this).find('input').prop("checked"))
					id += $(this).find('input').val()+",";
			})
			pack = packprice(id);
			totalPrice();
		});

		$(document).on('keyup','input[name="dopprice"]',function(){
			dops = $(this).val();
			totalPrice();
			$("#car-dop").html(number_format(dops,0,'',' '));
		})


	break;

	case 'caredit':
		function totalPrice()
		{
			total = parseInt(base)+parseInt(pack)+parseInt(dops);
			$("#car-total").html(number_format(total,0,'',' '));
		}

		var base = complectPrice($('select[name="complect_id"]').val());
		
		var id = '';
		$(".pack tr").each(function(){
			if($(this).find('input').prop("checked"))
				id += $(this).find('input').val()+",";
		})
		var	pack = packprice(id);
		
		var dops = dopPrice($('input[name="dopprice"]').val());

		totalPrice();

		brandObj($('select[name="brand_id"]').val());
		modelObj($('select[name="model_id"]').val());
		colorObj($('.color input:checked').val());


		$('select[name="brand_id"]').change(function(){
			getModels($(this),'list');
			brandObj($(this).val());
		});
		$('select[name="model_id"]').change(function(){
			getComplects($(this));
			modelObj($(this).val());
		});
		$(document).on('change','select[name="complect_id"]',function(){
			base = complectPrice($(this).val());
			totalPrice();
			getPacks($(this));
			getColor($(this),'radio');			
		});

		$(document).on('click','.color input',function()
		{
			colorObj($(this).val());
		})
		
		$(document).on('click','.pack tr',function(){
			var id = '';
			$(".pack tr").each(function(){
				if($(this).find('input').prop("checked"))
					id += $(this).find('input').val()+",";
			})
			pack = packprice(id);
			totalPrice();
		});

		$(document).on('keyup','input[name="dopprice"]',function(){
			dops = $(this).val();
			totalPrice();
			$("#car-dop").html(number_format(dops,0,'',' '));
		})
	break;

	///////////////////////////////
	//СОЗДАНИЕ КРЕДИТНЫХ ПРОГРАММ//
	///////////////////////////////
	case 'kreditadd':
		$('select[name="brand_id"]').change(function(){
			getModels($(this),'string');
		});
	break;

	case 'kreditedit':
		var last_brand = $('select[name="brand_id"]').val();
		$('select[name="brand_id"]').change(function(){
			var res = confirm('При смене бренда удалятся все модели бренда, которые уже включены в состав кредита, продолжить?');
			if(res)
			{
				getModels($(this),'string');
			}
			else
			{
				$(this).val(last_brand);
			}			
		});
	break;

	//////////////////////////////////////
	//ДОБАВЛЕНИЕ ФАИЛОВ (PDF) ДЛЯ МОДЕЛИ//
	//////////////////////////////////////
	case 'filesadd':
		$('select[name="brand_id"]').change(function(){
			getModels($(this),'list'); 
		});
	break;

	case 'filesedit':
		var last_brand = $('select[name="brand_id"]').val();
		$('select[name="brand_id"]').change(function(){
			var res = confirm('При смене бренда удалится модель, которая выбрана для данного фаила, продолжить?');
			if(res)
				getModels($(this),'list'); 
			else
				$(this).val(last_brand);
		});
	break;

	//////////////////////////////////
	//СОЗДАНИЕ КОММЕРЧЕСКОЙ КОМПАНИИ//
	//////////////////////////////////
	case 'companyadd':
		//изменение сценария очистить блок data и добавит поля для выбранного сценария
		$('select[name="scenario"]').change(function(){
			$('.data').html("");
			$(".company-dop input").prop('checked', false);
			if($(this).val()==1)
			{
				str = '<div class="col-sm-12"><h4>Параметры расчёта</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>';
						str += '<input type="checkbox" value="1" name="base">';
						str += 'Включить на базу';
					str += '</label>';

					str += '<label>';
						str += '<input type="checkbox" value="1" name="option">';
						str += 'Включить на опции';
					str += '</label>';

					str += '<label>';
						str += '<input type="checkbox" value="1" name="dop">';
						str += 'Включить на допы';
					str += '</label>';
				str += '</div>';

				str += '<div class="col-sm-2">';
					str += '<label>Значение(%):</label>';
					str += '<input type="range" name="value" min="0" max="100" step="0.5" class="form-control">';
				str += '</div>';

				str += '<div class="col-sm-2">';
					str += '<label>Ограничение скидки:</label>';
					str += '<input type="text" name="max" class="form-control">';
				str += '</div>';

				$('.data').html(str);
				var list = $('.data').find('input[name="value"]');
				setRange(list);
			}
			if($(this).val()==2)
			{
				str = '<div class="col-sm-12"><h4>Параметры бюджета</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>Сумма:</label>';
					str += '<input type="text" name="bydget" class="form-control">';
				str += '</div>';
				$('.data').html(str);
			}
			if($(this).val()==3)
			{
				str = '<div class="col-sm-12"><h4>Параметры номенклатуры</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>ДО:</label>';
					str += '<button type="button" class="open-dop form-control">Выбрать ДО</button>';
				str += '</div>';
				$('.data').html(str);
			}
		});
		//выбор модели подгрузит комплектации для этой модели
		$('body').on('change','.model',function(){
			getComplects($(this),$(this).closest('.exep').find('.complect'));
		})
		$('body').on('click','.data button',function(){
			$(".company-dop").css('display','block');
		});
		$('body').on('click','.close',function(){
			$(".company-dop").css('display','none');
		});
		//клонировать родителя кнопки включения/исключения
		var i = 100;
		$('body').on('click','.clone',function(){
			var parent = $(this).closest('.pos_exeptions');
			var clonest = $(this).closest('.exep').clone();
			clonest.find('label').html("");
			clonest.find('input[type="text"]').val("");
			clonest.find('.complect').html("");
			clonest.find('.clone').css('display','none');
			clonest.find('input, select').each(function(){
				var name = $(this).attr('name');
				name = name.replace(/\[(.*?)\]/g, '['+i+']');
				$(this).attr('name',name)
			});
			i++
			parent.append(clonest);
		})
		//удаление родителя кнопки включения/исключения
		$('body').on('click','.delete',function(){
			var count = $(this).closest('.pos_exeptions').find('.exep').length;
			if(count>=2)
				$(this).closest('.exep').remove();
		})
	break;

	case 'companyedit':
		//изменение сценария очистить блок data и добавит поля для выбранного сценария
		$('select[name="scenario"]').change(function(){
			$('.data').html("");
			$(".company-dop input").prop('checked', false);
			if($(this).val()==1)
			{
				str = '<div class="col-sm-12"><h4>Параметры расчёта</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>';
						str += '<input type="checkbox" value="1" name="base">';
						str += 'Включить на базу';
					str += '</label>';

					str += '<label>';
						str += '<input type="checkbox" value="1" name="option">';
						str += 'Включить на опции';
					str += '</label>';

					str += '<label>';
						str += '<input type="checkbox" value="1" name="dop">';
						str += 'Включить на допы';
					str += '</label>';
				str += '</div>';

				str += '<div class="col-sm-2">';
					str += '<label>Значение(%):</label>';
					str += '<input type="range" name="value" min="0" max="100" step="0.5" class="form-control">';
				str += '</div>';

				str += '<div class="col-sm-2">';
					str += '<label>Ограничение скидки:</label>';
					str += '<input type="text" name="max" class="form-control">';
				str += '</div>';

				$('.data').html(str);
				var list = $('.data').find('input[name="value"]');
				setRange(list);
			}
			if($(this).val()==2)
			{
				str = '<div class="col-sm-12"><h4>Параметры бюджета</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>Сумма:</label>';
					str += '<input type="text" name="bydget" class="form-control">';
				str += '</div>';
				$('.data').html(str);
			}
			if($(this).val()==3)
			{
				str = '<div class="col-sm-12"><h4>Параметры номенклатуры</h4></div>';
				str += '<div class="col-sm-2">';
					str += '<label>ДО:</label>';
					str += '<button type="button" class="open-dop form-control">Выбрать ДО</button>';
				str += '</div>';
				$('.data').html(str);
			}
		});
		//выбор модели подгрузит комплектации для этой модели
		$('body').on('change','.model',function(){
			getComplects($(this),$(this).closest('.exep').find('.complect'));
		})
		$('body').on('click','.data button',function(){
			$(".company-dop").css('display','block');
		});
		$('body').on('click','.close',function(){
			$(".company-dop").css('display','none');
		});
		//клонировать родителя кнопки включения/исключения
		var i = 100;
		$('body').on('click','.clone',function(){
			var parent = $(this).closest('.pos_exeptions');
			var clonest = $(this).closest('.exep').clone();
			clonest.find('label').html("");
			clonest.find('input[type="text"]').val("");
			clonest.find('.complect').html("");
			clonest.find('.clone').css('display','none');
			clonest.find('input, select').each(function(){
				var name = $(this).attr('name');
				name = name.replace(/\[(.*?)\]/g, '['+i+']');
				$(this).attr('name',name)
			});
			i++
			parent.append(clonest);
		})
		//удаление родителя кнопки включения/исключения
		$('body').on('click','.delete',function(){
			var count = $(this).closest('.pos_exeptions').find('.exep').length;
			if(count>=2)
				$(this).closest('.exep').remove();
		})
	break;

	default:
	break;
}
