//ПРОВЕРКА НА СУЩЕСТВОВАНИЕ
function isset(variable)
{
	if(typeof(variable) != "undefined" && variable !== null)
		return true;
	else 
		return false;
}

//УДАЛИТ ХТМЛ ТЕГИ ИЗ СТРОКИ
function cutTegs(str) {
 	var regex = /( |<([^>]+)>)/ig,
    result = str.replace(regex, "");
	return result;
}

//РАЗДЕЛИТ НА РАЗРЯДЫ
function number_format(number,decimals,dec_point,thousands_sep)
{
	var znak = ''
	if(number<0){
		znak = '-'
		number*=(-1)
	}

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
	return znak+km+kw+kd;
}

//ВЕРНЁТ ТЕКУЩУЮ ДАТУ В ФОРМАТЕ Д.М.Г
function getCurrentDate()
{
	date = new Date()
	var day = date.getDate()
	var mon = date.getMonth()+1
	var year = date.getFullYear()

	if(day<10)
		day='0'+day
	if(mon<10)
		mon='0'+mon
	return day+'.'+mon+'.'+year
}

//ПЕРЕДЕЛАЕТ ЮНИКСДАТУ В ФОРМАТ УКАЗАНЫЙ ВТОРЫМ ПАРАМЕТРОМ
function timeConverter(UNIX_timestamp,format='d.m.y'){
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

//ВЕРНЁТ КУКИ ПО ПАРАМЕТРУ
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function getTitleContent(parent,array,stock="",str='')
//создаст заголовки, парент-это родительская вкладка в которой есть таблица, в которую нужно вставить данные
//аррай-массив заголовков которые передал аякс из функции гетКонтент
{
	if(activeTab().attr('aria-controls')=='stock')
		stock = 1

	str += '<tr class="table-title">';
	if(stock)
		str += '<td></td>'
	for (i in array){
		str += '<td td-id="'+i+'">'+array[i]+'</td>';		    		
	};
	str += '</tr>';
	parent.find('table').append(str);
}


//ЗАПИСЬ В КУКИ ВСЕХ ИД СТОЛБЦОВ КОТОРЫЕ ПОДХОДЯТ К ВЫБРАННОЙ НАСТРОЙКЕ
$(document).on('click','#currentSettingsList .setting-set-active',function(){
	var parameters = {'settings_id':$(this).attr("data-id")}
	var url = '/settings/fields/get'
	$.when(
		ajax(parameters,url).
			then(function(data){
				data = (JSON.parse(data))
				document.cookie = data.type + "=" + JSON.stringify(data.list);
				getContent($('#'+activeTab().attr('id')));

				$('#settingsModal').modal('hide');
			})
	)
});

//////////////////////////////////////////////////
//////////////////////////////////////////////////
//КЛИК ПО КНОПКАМ ВЛЕВО ВПРАВО ПЕРЕМОТКИ ТАБЛИЦЫ//
//////////////////////////////////////////////////
//////////////////////////////////////////////////
$(document).on('mousedown','.left-remote',function(){
	var obj = $(this)
	timeout = setInterval(function() {
		obj.attr('status',true)
		var elem = $('#crmTabPanels')
		var curentLeft = elem.scrollLeft()
        elem.scrollLeft(curentLeft-50)
    }, 100);
}).on('mouseup','.left-remote',function(){
	clearInterval(timeout)
    //return false;
}).on('click','.left-remote',function(e){
	if($(this).attr('status')!='true'){
		chick($(this))
		var elem = $('#crmTabPanels')
		var curentLeft = elem.scrollLeft()
		var width = $('body').width()
		var newScroll = curentLeft-(width/2)
		elem.scrollLeft(newScroll)
	}
	$(this).attr('status',false)
})

$(document).on('mousedown','.right-remote',function(){
	var obj = $(this)
	timeout = setInterval(function() {
		obj.attr('status',true)
		var elem = $('#crmTabPanels')
		var curentLeft = elem.scrollLeft()
        elem.scrollLeft(curentLeft+50)
    }, 100);
}).on('mouseup','.right-remote',function(){
	clearInterval(timeout)
    //return false;
}).on('click','.right-remote',function(e){
	if($(this).attr('status')!='true'){
		chick($(this))
		var elem = $('#crmTabPanels')
		var curentLeft = elem.scrollLeft()
		var width = $('body').width()
		var newScroll = curentLeft+(width/2)
		elem.scrollLeft(newScroll)
	}
	$(this).attr('status',false)
})

function chick(button){
	setTimeout(function(){
		button.css('background','#faa')
	},100)
	button.css('background','#944')
}




function getDataContent(parent,array,stock="",str='')
//создаст контент вкладки, парент-это родительская вкладка в которой есть таблица, в которую нужно вставить данные
//аррай-массив заголовков которые передал аякс из функции гетКонтент
{
	array.forEach(function(item,i){
		str += '<tr>';
			for(var index in item) { 
				if(index=='id') continue;
				if(item[index]==null){
					str += '<td></td>';
					continue;
				}
			    str += '<td>'+item[index]+'</td>'; 
			}
		str += '</tr>';
	});
	parent.find('table').append(str);
	if(selcarId)
	{
		$(document).find('table tr td .check-car[value="'+selcarId+'"]').closest('tr').addClass('save-tr')
	}
	//левая кнопка перемотки
	parent.find('table').parent().append('<div class="left-remote"><i class="fa fa-angle-left curs" aria-hidden="true"></i>')
	//правая кнопка перемотки
	parent.find('table').parent().append('<div class="right-remote"><i class="fa fa-angle-right curs" aria-hidden="true"></i>')
	//проверяю есть ли в куках набор данных совпадающих с атрибутом ария-контрол кнопки активной вкладки
	if(getCookie(activeTab().attr('aria-controls'))==undefined){
		alert('Настройка активных столбцов не выбрана, будут показаны все столбцы')
		return false
	}
	//вытаскиваю куку имя которой совпадает с атрибутом ария-контрол кнопки активной вкладки(клиеты, трафик, автосклад и тд)
	var seter = JSON.parse(getCookie(activeTab().attr('aria-controls')))
	//беру таблиуц с даными которые нужно показывать в зависимости от набора данных куки
	var table = parent.find('table')
	//массив столбцов которые надо будет показывать
	var indexes = []
	//прохожусь по всей куке
	for(i in seter){
		//записываю в массив активных столбцов данные из куки которые совпадают с атрибутом тд-ид заголовков столбцов таблицы
		indexes.push(table.find('[td-id="'+seter[i].field_id+'"]').index())
	}
	//прохожусь по всем строкам таблицы
	table.find('tr').each(function(){
		//прячу все тд в строке тр
		$(this).find('td').css('display','none')
		//беру значения из массива активных столбцов
		for(i in indexes)
		{
			//если это автосклад то показывать 0ой столбец тк там чекбокс
			if(activeTab().attr('aria-controls')=='stock')
				$(this).find('td:eq(0)').css('display','table-cell')
			//показать активный столбик
			$(this).find('td:eq('+indexes[i]+')').css('display','table-cell')
		}
	})
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



function activeTab()
{
	return $("#crmTabs").find("[aria-selected='true']")
}


function getContent(obj, get_param = '')//отдаёт контент вкладок crmTabs (клиенты, трафик, автосклад и тд)
{
	var formData = new FormData();
	if (obj.closest('#crmTabs').attr('id') === undefined)
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
	    	getTitleContent(parent,param.titles);		    
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
	getContent($("#stock-tab"))
}



function getAllComplect(model='',res = '')
{
	$.ajax({
		async: false,
		type: 'GET',
		url: "/getcomplects",
		dataType : "json", 
        data: {'model_id':model},
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data){
	    	res = data;
	    }
	})
	return res;
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