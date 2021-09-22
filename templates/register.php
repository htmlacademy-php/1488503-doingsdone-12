<section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>
    <a class="button button--transparent content__side-button"
       href="../pages/form-authorization.html">Войти</a>
</section>
<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>
    <form class="form" action="/register.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <?php $classname = isset($errors['email']) ? "form__input--error" : ""; ?>
            <input class="form__input  <?= $classname ?>" type="text" name="email" id="email" value=""
                   placeholder="Введите e-mail">
            <p class="form__message"><?= $errors['email'] ?? ""; ?></p>
        </div>
        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <?php $classname = isset($errors['password']) ? "form__input--error" : ""; ?>
            <input class="form__input  <?= $classname ?>" type="password" name="password" id="password" value=""
                   placeholder="Введите пароль">
            <p class="form__message"><?= $errors['password'] ?? ""; ?></p>
        </div>
        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>
            <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
            <input class="form__input  <?= $classname ?>" type="text" name="name" id="name" value=""
                   placeholder="Введите имя">
            <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
        </div>
        <div class="form__row form__row--controls">
            <?php $classname = !empty($errors) ? "Пожалуйста, исправьте ошибки в форме" : ""; ?>
            <p class="error-message"><?= $classname ?></p>
            <input class="button" type="submit" name="" value="Зарегистрироваться">
        </div>
    </form>
</main>