jQuery(document).ready(function ($) {
    //пронумеровали список комментариев
    $('.commentlist li').each(function (i) {

        $(this).find('div.commentNumber').text('#' + (i + 1))
    });

    $('#commentform').on('click', '#submit', function (e) {
        //отмена действия при клике на кнопку отправки формы
        e.preventDefault();
        //кнопка, по которой кликнули - отправка комментариев
        var comParent = $(this);
        //fadeIn - показывает данный блок на экран плавно, функция выполнится после завершения анимации
        $('.wrap_result').css('color', 'green').text('Сохранение комментария').fadeIn(500, function () {

            var data = $('#commentform').serializeArray();
            //отправка post запроса
            $.ajax({

                url: $('#commentform').attr('action'),
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                datatype: 'JSON',
                success: function (html) {
                    if (html.error) {

                    }
                    else if (html.success) {
                        $('.wrap_result')
                            .append('<br /><strong>Сохранено!</strong>')
                            .delay(2000)
                            .fadeOut(500, function () {
                                //мы пытаемся отобразить на экран комментарий, который только что написали
                                if (html.data.parent_id > 0) {
                                    comParent.parents('div#respond').prev().after('<ul class="children">' + html.comment + '</ul>');
                                }

                                $('#cancel-comment-reply-link').click();

                            })

                    }},
                error: function () {

                }

            });

        });

    });


});