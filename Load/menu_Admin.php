<?php  
echo '
<head>
    <link rel="stylesheet" href="css/CssTableUl.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid p-0">
                    <div class="header_bottom_border">
                        <div class="row align-items-center no-gutters">

                            <div class="col-xl-3 col-lg-2">
                                <div class="logo">
                                    <a href="https://sut.ru/" target="_blank">
                                        <img style="margin:3px; margin-left:20px" src="'.$CD_path.'Res/Icons/Logo_SpbGut.png" height="60" alt="">
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-7">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">

                                            <li><a class="active" href="'.$CD_path.'index.php">Главная</a></li>

                                            <li><a href="#">Каталог <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
													<li><a href="'.$CD_path.'Load/USER_READ_Videocard.php">Видеокарты</a></li>
													<li><a href="'.$CD_path.'Load/USER_READ_Processor.php">Процессоры</a></li>
													<li><a href="'.$CD_path.'Load/USER_READ_Motherboard.php">Материнские платы</a></li>
                                                </ul>
                                            </li>
                                            
                                            <li><a href="#">Редактор таблиц <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
                                                    <li><a href="'.$CD_path.'CRUD/CRUD_Users.php">Пользователи</a></li>
                                                    <li><a href="'.$CD_path.'CRUD/CRUD_Videocard.php">Видеокарты</a></li>
                                                    <li><a href="'.$CD_path.'CRUD/CRUD_Processor.php">Процессоры</a></li>
                                                    <li><a href="'.$CD_path.'CRUD/CRUD_Motherboard.php">Материнские платы</a></li>
                                                </ul>
                                            </li>
                                            
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                                <div class="say_hello">
                                    <a href="'.$CD_path.'Forms/Login.php">Профиль</a>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
  <br><br>'
?>