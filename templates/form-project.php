<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>
        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($categories as $item) : ?>
                    <li class="main-navigation__list-item <?php if ($item['project_id'] == $projectId): ?> main-navigation__list-item--active <?php endif ?>">
                        <a class="main-navigation__list-item-link" href="index.php"><?= $item['name'] ?></a>
                        <span class="main-navigation__list-item-count"><?= $item['count'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <a class="button button--transparent button--plus content__side-button" href="form-project.php">Добавить
            проект</a>
    </section>
    <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>
        <form class="form" action="form-project.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="project_name">Название <sup>*</sup></label>
                <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
                <input class="form__input <?= $classname ?>" type="text" name="name" id="project_name" value=""
                       placeholder="Введите название проекта">
            </div>
            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>
