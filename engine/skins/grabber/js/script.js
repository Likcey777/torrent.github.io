$(document).ready(function() {

            var button = $('#uploadButton'), interval;


            $.ajax_upload(button, {
                        action : '?mod=rss&action=upload',
                        name : 'uploadfile',
                        onSubmit : function(file, ext) {
                            // показываем картинку загрузки файла
                            $("#uploadButton").text('Загрузка');

                            /*
                             * Выключаем кнопку на время загрузки файла
                             */
                            this.disable();

                        },
                        onComplete : function(file, response) {
                            // убираем картинку загрузки файла
                            $("#uploadButton").text('Загрузить');

                            // снова включаем кнопку
                            this.enable();
                            // показываем что файл загружен
							$("#find_upload").text('');
                            $(response).appendTo("#find_upload");

                        }



                    });
        });
