function isset(variable)
{
	if(typeof(variable) != "undefined" && variable !== null)
		return true;
	else 
		return false;
}


function timeConverter(UNIX_timestamp,format){
	if(UNIX_timestamp==0)
		return '';
	var a = new Date(UNIX_timestamp * 1000);

	var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];

	var year = a.getFullYear();
	var month = months[a.getMonth()];
	var date = a.getDate();
	var hour = a.getHours()-3;
	var min = a.getMinutes();
	var sec = a.getSeconds();

	var str = format;

	if(date<10)
		day = '0'+date;
	else 
		day = date;

	if(hour<10)
		hour = '0'+hour;

	if(min<10)
		min = '0'+min;

	if(sec<10)
		sec = '0'+sec;

	str = str.replace('d',day);
	str = str.replace('m',month);
	str = str.replace('y',year);
	str = str.replace('h',hour);
	str = str.replace('i',min);
	str = str.replace('s',sec);

  return str;
}


function getTitleContent(parent,array,str='')
//создаст заголовки, парент-это родительская вкладка в которой есть таблица, в которую нужно вставить данные
//аррай-массив заголовков которые передал аякс из функции гетКонтент
{
	str += '<tr>';
	array.forEach(function(item,i){
		str += '<th>'+item.name+'</th>';		    		
	});
	str += '</tr>';
	parent.find('table').append(str);
}







function getDataContent(parent,array,str='')
//создаст контент вкладки, парент-это родительская вкладка в которой есть таблица, в которую нужно вставить данные
//аррай-массив заголовков которые передал аякс из функции гетКонтент
{
	array.data.forEach(function(item,i){
		str += '<tr>';
			for(var index in item) { 
				if(index=='id') continue;
			    str += '<td>'+item[index]+'</td>'; 
			}
		str += '</tr>';
	});
	parent.find('table').append(str);
}





function getPaginationContent(parent,str='')
//создаст пагинацию, парент-это родительская вкладка, в которую нужно вставить данные пагинации
{
	if(str!==undefined && str!=='')
	{
		parent.find('.pagination').remove();
		parent.append(str);
	}
}






function getContent(obj,get_param='')//отдаёт контент вкладок crmTabs (клиенты, трафик, автосклад и тд)
{
	var formData = new FormData();
	if(obj.closest('#crmTabs').attr('id')===undefined)
	{
		var parent = obj.closest('.tab-pane');
		var mas = obj.attr('href').split('/');
		var mas = mas[mas.length-1].split('?');
		get_param = '?'+mas[mas.length-1];
	}
	else
	{
		var parent = $("#crmTabPanels").find("div[aria-labelledby='"+obj.attr('id')+"']");
		formData.append('model',obj.attr('model-name'));
	}
	$.ajax({
		type: 'POST',
		url: '/crmgetcontent'+get_param,
		dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success:function(param){
	    	parent.find('table').html("");
	    	getTitleContent(parent,param['titles']);		    
	    	getDataContent(parent,param['list']);		    	
	    	getPaginationContent(parent,param['links']);	    	
	    },
	    error:function(xhr, ajaxOptions, thrownError){
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions)
	    }
	});
}


function refreshContent()
{
	getContent($("#clients-tab"));//переписываю данные во вкладке клиенты
	getContent($("#traffic-tab"));//переписываю данные во вкладке трафик
}




//Принимает объект формы и массив названий полей формы
// form - это $('form')
// params - это массив например [name,lastname,phone, age]
// в случае если все элементы формы с названиями из массива params не пусти или нажаты, то вернёт истину
// иначе если хотя бы один из элементов формы с названием из массива params пуст, то вернёт ошибку в виде массива
// где элемент массива это сообщение об ошибке для конкретного поля формы
function validateForm(form, params)
{
	var error = [];
	var k = 1;
	if(!Array.isArray(params))
		return false;

	params.forEach(function(item,i){
		var schet = 0;
		form.find('[name="'+item+'"]').each(function(){
			
			if($(this).attr('type')=='text')
			{
				if($(this).val()=="")
					schet = 0;
				else
					schet = 1;

			}

			else if ($(this).attr('type')=='radio')
			{
				var count = form.find('[name="'+item+'"]').length;
				if($(this).prop("checked"))
					schet = 1;
			}
		})

		if(schet == 0)
			error.push('\n'+(k++)+' - Поле '+item+' не заполнено');
	})

	if(error.length == 0)
		return true;
	else 
		return error;
}



function getJournal()
{
	var area = $("#log");
	var tbody = area.find('tbody');
	var str = "";
	tbody.html("");
	$.ajax({
		type: 'POST',
		url: '/crm/get/journal',
		dataType : "json", 
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data, xhr,ajaxOptions, thrownError)
	    {
	    	data.forEach(function(item,i){
	    		str += '<tr>';
	    			str += '<td>'+timeConverter(item.action_date,'d.m.y')+'</td>';
	    			str += '<td>'+timeConverter(item.action_time,'h:i')+'</td>';

	    			if(isset(item.traffic_type))
	    				str += '<td>'+item.traffic_type.name+'</td>';
	    			else 
	    				str += '<td></td>';

	    			if(isset(item.assigned_action))
	    				str += '<td>'+item.assigned_action.name+'</td>';
	    			else 
	    				str += '<td></td>';

	    			if(isset(item.client))
	    				str += '<td>'+item.client.name+'</td>';
	    			else 
	    				str += '<td></td>';

	    			if(isset(item.model))
	    				str += '<td>'+item.model.name+'</td>';
	    			else 
	    				str += '<td></td>';

	    			if(isset(item.manager))
	    				str += '<td>'+item.manager.name+'</td>';
	    			else 
	    				str += '<td></td>';
	    		str += '</tr>';
	    		tbody.append(str);
	    		str="";
	    	})		
	    },
	    error:function(xhr, ajaxOptions, thrownError){
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions)
	    }
	});
}



