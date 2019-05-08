/**
 * Рабочий лист
 * Параметры - Автомобиль клиента
 */

(function(){
	let wl_id = ''; //ид рабочего листа
	let modal = ''; //объект модали
	let photoContainer = ''; //блок где хранятся загруженные фото

	$(document).on('click','.oldcar-photo',function(){ //клик по вкладке авто клиента
		wl_id = $('span[name=wl_id]').html() //беру ид раблиста
		modal = $(this).closest('form').find('.photo-load') //нахжу меню модаль
		photoContainer = modal.find('.old-photo') //в модали нахожу блок фото
		modal.css('display','block') //делаю модаль видимой
		log(modal)
	});

	$(document).on('click','.close',function() { //клик по крестику модали
		$(this).parent().css('display','none') //прячу модаль
	});

	$(document).on('click','.search-photo',function() {//клик по кнопке фотографии
		$(this).parent().find('input').click(); //прогрпммно кликаю по инпутфайл и вызываю его окно выбора
	});

	$(document).on('click','.load',function() {		//загрузка фоток
		var formData = new FormData(); //пустой объект формдата, тк передаю фаилы
		var input = modal.find('[name="photo"]'); //нахожу объект который хранит фаилы
		files = input[0].files; //записываю выбранные фаилы  переменную
		
		$.each( files, function( key, value ){ //из переменой записываю фаилы в объект формдата перебором
			formData.append( key, value );
		});

		formData.append('wl_id',wl_id); //записываю в объект ид раб листа
		var parameters = formData; //готовые данные для отправки
		var url = '/worklist/client/oldcar/photo'; //адрес отправки
		$.when(ajaxWithFiles(parameters,url)).then(function(data){ //аякс функция для отправки фаилов (хранится в мейн.жс)
			for (i in data) //обхожу полученный ответ
				photoContainer.append('<img src="'+data[i]+'">'); //и дозаписываю в блок фотографий
		});
	});
})();

/**
 * Автомобиль клиента
 * Открытие блока
 */
 
$(document).on('shown.bs.collapse', '#wsparam5', function() {
	var wl_id = $('span[name=wl_id]').html();

	if (wl_id != '-')
	{
		var parameters = {'wl_id':wl_id};
		var url = '/worklist/client/oldcar';
		$.when(ajax(parameters,url)).then(function(data) {
			$(".old-car").html('');
			$(".old-car").append('<div class="input-group h4">Автомобиль клиента</div>');
			$(".old-car").append(data);
		});
	}
});