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

var base = 0;
var pack = 0;
var dops = 0;

$('select[name="model_id"]').change(function(){//получаем комплектации от модели
	getComplects($(this));
	modelObj($(this).val());
});

$(document).on('change','select[name="complect_id"]',function(){
	base = complectPrice($(this).val());
	//totalPrice();
	getPacks($(this));
	getColor($(this),'radio');			
});