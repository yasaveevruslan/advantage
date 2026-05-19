<?php
    session_start();
    include('php/connect.php');

    $page = isset($_GET['page']) ? $_GET['page'] : 'main';

    function includePage($page)
    {
        $filePath = 'pages/' . $page . '.php';
        if(file_exists($filePath)){
            include($filePath);
        }
        else{
            echo "<h1>Страница не найдена</h1>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Польза</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="shortcut icon" href="image/fav.png" type="image/x-icon">
</head>

<body>
    <!-- шапка -->
    <?php include('includes/header.php') ?>

    <?php
    switch($page) {
        
        //  ГЛАВНАЯ СТРАНИЦА 
        case 'main':
            includePage('main');
            break;

        //  КАТАЛОГ 
        case 'catalog':
            includePage('catalog');
            break;
        
        //  ПОЛЬЗОВАТЕЛЬ 
        case 'reg':
            includePage('reg');
            break;
        case 'auth':
            includePage('auth');
            break;
        case 'profile':
            includePage('profile');
            break;
        case 'lk':
            includePage('lk');
            break;
        case 'lk_izb':
            includePage('lk_izb');
            break;
        case 'upd_profile':
            includePage('upd_profile');
            break;
        case 'upd_password':
            includePage('upd_password');
            break;
            
        //  КАТАЛОГ 
        case 'catalog_blud':
            includePage('catalog_blud');
            break;
        case 'catalog_nabor':
            includePage('catalog_nabor');
            break;
        case 'blud':
            includePage('blud');
            break;
        case 'nabor':
            includePage('nabor');
            break;
            
        //  КОРЗИНА И ЗАКАЗЫ 
        case 'korzina':
            includePage('korzina');
            break;
        case 'zakaz_dost':
            includePage('zakaz_dost');
            break;
            
        //  ОТЗЫВЫ 
        case 'otz':
            includePage('otz');
            break;
        case 'dob_otz':
            includePage('dob_otz');
            break;
        case 'otz_promocod':
            includePage('otz_promocod');
            break;
            
        //  АДРЕСА И ДОКУМЕНТЫ 
        case 'dob_adres':
            includePage('dob_adres');
            break;
        case 'doc':
            includePage('doc');
            break;
            
        //  АДМИНКА 
        case 'admin_lk':
            includePage('admin_lk');
            break;
        case 'admin_lk_otz':
            includePage('admin_lk_otz');
            break;
        case 'admin_lk_promocod':
            includePage('admin_lk_promocod');
            break;
            
        // Админка - блюда
        case 'admin_add_bl':
            includePage('admin_add_bl');
            break;
        case 'admin_edit_bl':
            includePage('admin_edit_bl');
            break;
        case 'admin_upd_bl':
            includePage('admin_upd_bl');
            break;
        case 'admin_kat_bl':
            includePage('admin_kat_bl');
            break;
        case 'admin_addkat_bl':
            includePage('admin_addkat_bl');
            break;
        case 'admin_editkat_bl':
            includePage('admin_editkat_bl');
            break;
            
        // Админка - наборы
        case 'admin_add_na':
            includePage('admin_add_na');
            break;
        case 'admin_edit_na':
            includePage('admin_edit_na');
            break;
        case 'admin_upd_na':
            includePage('admin_upd_na');
            break;
        case 'admin_kat_na':
            includePage('admin_kat_na');
            break;
        case 'admin_addkat_na':
            includePage('admin_addkat_na');
            break;
        case 'admin_editkat_na':
            includePage('admin_editkat_na');
            break;
            
        // Админка - промокоды
        case 'admin_addpromocod':
            includePage('admin_addpromocod');
            break;
        case 'admin_editpromocod':
            includePage('admin_editpromocod');
            break;
            
        // Страница не найдена
        default:
            echo "<div class='container'><h1>404 - Страница не найдена</h1></div>";
            break;
    }
    ?>

    <!-- футер -->
    <?php include('includes/footer.php') ?>
</body>

</html>