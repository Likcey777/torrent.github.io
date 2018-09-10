                    <div id="addcform" title="Добавить отзыв">
                        <!--noindex-->
                        <div class="add-com-form clearfix">
                            <div class="add-com-textarea clearfix">
                                <div class="add-comm">

                                    [not-logged]
                                    <div class="login-social comm-social clearfix">
                                        <a href="{vk_url}" target="_blank"><img src="{THEME}/images/social/vkontakte.png" />
                                        </a>
                                    </div>
                                    <div class="ac-inputs clearfix">
                                        <input type="hidden" maxlength="35" name="name" id="name" value="Гость" />
                                        <input type="hidden" maxlength="35" name="mail" id="mail" placeholder="Ваш e-mail (необязательно)" />
                                    </div>
                                    [/not-logged]

                                    <div class="ac-textarea">
                                        {editor}
                                    </div>


                                    <div class="ac-protect">



                                        [recaptcha]
                                        <div class="sep-input clearfix">
                                            <div class="label"><span>Введите два слова, показанных на изображении:</span><span class="impot">*</span>
                                            </div>
                                            <div class="input">
                                                {recaptcha}
                                            </div>
                                        </div>
                                        [/recaptcha]

                                    </div>



                                    <div class="ac-submit">
                                        <button name="submit" class="fbutton" type="submit">Отправить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/noindex-->
                        
                        </div>