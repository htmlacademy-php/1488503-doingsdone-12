<?php
session_start();
?>
<?php if (!isset($_SESSION['user'])):?>
<header class="main-header">
    <a href="#">
        <img src="../img/logo.png" width="153" height="42" alt="Логитип Дела в порядке">
    </a>

    <div class="main-header__side">
        <a class="main-header__side-item button button--transparent" href="form-authorization.html">Войти</a>
    </div>
</header>
<div class="content">
    <section class="welcome">
        <h2 class="welcome__heading">«Дела в порядке»</h2>

        <div class="welcome__text">
            <p>«Дела в порядке» — это веб приложение для удобного ведения списка дел. Сервис помогает пользователям не забывать о предстоящих важных событиях и задачах.</p>

            <p>После создания аккаунта, пользователь может начать вносить свои дела, деля их по проектам и указывая сроки.</p>
        </div>

        <a class="welcome__button button" href="../templates/register.php">Зарегистрироваться</a>
    </section>
</div>
<?php endif;?>