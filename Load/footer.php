<?php  
echo '

<footer class="footer" style="text-align: left;">
        <div class="footer_top">
            <div class="container">
                <div class="row">

                    <div class="col-xl-3 col-md-6 col-lg-3 ">
                        <div class="footer_widget">
                            <h3 class="footer_title">
                                О проекте
                            </h3>
                                <p>Студент: <br> Лепов Алексей <br>Группа:<br>ПКС-581 <br>
                                </p>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-lg-3 ">
                        <div class="footer_widget">
                            <h3 class="footer_title">
                                Каталог
                            </h3>
                            <ul>
                                <li><a href="'.$CD_path.'Load/READ_VIDEO.php">Видеокарты</a></li>
                                <li><a href="'.$CD_path.'Load/READ_PROC.php">Процессоры</a></li>
                                <li><a href="'.$CD_path.'Load/READ_MOTH.php">Материнские платы</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-lg-3 ">
                        <div class="footer_widget">
                            <h3 class="footer_title">
                                Помощь
                            </h3>
                            <ul>
                                <li><a href="'.$CD_path.'Forms/Login.php">Войти</a></li>
                                <li><a href="'.$CD_path.'Forms/Register.php">Зарегистрироваться</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="copy-right_text">
            <div class="container">
                <div class="footer_border"></div>
                <div class="row">
                    <div class="col-xl-12">
                        <!-- <p class="copy_right text-center">
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script></i><a href=""
                                target="_blank"></a>
                        </p>-->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
<h6 style="text-align:center; color:#777;" class="footer_title"> <?php echo "&copy; 2020-".date("Y"); ?> </h6>
'
?>