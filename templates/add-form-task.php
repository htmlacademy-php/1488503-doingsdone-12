<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($categories as $category) : ?>
                    <li class="main-navigation__list-item">
                        <a class="main-navigation__list-item-link"
                           href="/?project_id=<?= $category['project_id'] ?>"><?= htmlspecialchars($category['name']); ?></a>
                        <span class="main-navigation__list-item-count"><?= htmlspecialchars($category['count']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="form-project.php">Добавить
            проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form" action="/add.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form__row">
                <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
                <label class="form__label" for="name">Название <sup>*</sup></label>
                <input class="form__input <?= $classname ?>" type="text" name="name" id="name" value=""
                       placeholder="Введите название">
                <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
            </div>
            <div class="form__row">
                <?php $classname = isset($errors['project']) ? "form__input--error" : ""; ?>
                <label class="form__label" for="project">Проект <sup>*</sup></label>
                <select class="form__input <?= $classname ?>" name="project" id="project">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?=$category['project_id']?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="form__message"><?= $errors['project'] ?? ""; ?></p>
            </div>
            <div class="form__row">
                <?php $classname = isset($errors['date']) ? "form__input--error" : ""; ?>
                <label class="form__label" for="date">Дата выполнения</label>
                <input class="form__input <?= $classname ?>" type="text" name="date" id="date" value=""
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <p class="form__message"><?= $errors['date'] ?? ""; ?></p>
            </div>
            <div class="form__row">
                <label class="form__label" for="file">Файл</label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="file" id="file" value="">
                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                </div>
            </div>
            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>

    </main>
</div>
