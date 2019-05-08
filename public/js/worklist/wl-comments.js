/**
 * Рабочий лист
 * Комментарии
 */


/**
 * Создать запись
 */
$(document).on('click', '#wl_create_comment', function(){
	$(this).blur();
	var text = $('#wl_new_comment').val();
	if (text != '')
	{
		$('#wl_comments_list').html($.trim("<p>01.01.2019 в 00:00 Пупкин<br>" + text + "</p>" + $('#wl_comments_list').html()));
		$('#wl_new_comment').val('');
	}
});