jQuery(document).ready(function($) {
    //пронумеровали список комментариев
    $('.commentlist li').each(function(i) {

        $(this).find('div.commentNumber').text('#' + (i + 1))
    });

    $('#commentform').on('click','#submit',function(e) {
        //отмена действия при клике на кнопку отправки формы
        e.preventDefault();
        //кнопка, по которой кликнули
        var comParent = $(this);
        //fadeIn - показывает данный блок на экран плавно, функция выполнится после завершения анимации
        $('.wrap_result').
                        css('color','green').
                        text('Сохранение комментария').
                        fadeIn(500,function() {

                            var data = $('#commentform').serializeArray();
                            //отправка post запроса
                            $.ajax({

                                url: $('#commentform').attr('action'),
                                data: data,
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type:'POST',
                                datatype: 'JSON',
                                success: function (html) {

                                },
                                error: function () {

                                }

                            });

                        });

    });



});