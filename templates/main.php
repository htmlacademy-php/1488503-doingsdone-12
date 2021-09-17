<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($categories as $item): ?>
                <li class="main-navigation__list-item  <?php if ($item['project_id'] == $projectId): ?> main-navigation__list-item--active <?php endif ?>">
                    <a class="main-navigation__list-item-link" href="/?project_id=<?= $item['project_id'] ?>">
                        <?= htmlspecialchars($item['name']); ?>
                    </a>
                    <span class="main-navigation__list-item-count">
                        <?= htmlspecialchars($item['count']) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <a class="button button--transparent button--plus content__side-button"
       href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>
<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>
    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?= $_GET['search'] ?? ""; ?>"
               placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>
    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>
        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed"
                   type="checkbox" <?php if ($show_complete_tasks == 1) : ?> checked <?php endif; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>
    <?php if (!isset($_GET['search']) || (isset($_GET['search']) && count($tasks) > 0)) : ?>
        <table class="tasks">
            <?php foreach ($tasks as $item) : ?>
                <?php
                $data = strtotime($item['date_of_completion']);
                $resultData = ($data - time()) / 3600;
                ?>
                <tr class="tasks__item task
                <?php if ($resultData <= 24): ?>task--important <?php endif; ?>
                <?php if ($item['completed'] == 1): ?> task--completed <?php endif; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox"
                                   type="checkbox" <?php if ($item['completed'] == 1): ?> checked <?php endif; ?>>
                            <span class="checkbox__text"><?= htmlspecialchars($item['task']); ?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <?php if (!empty($item['file'])) : ?>
                            <a class="download-link" href="<?= $item['file'] ?>">
                                <?= $item['fileName']; ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="task__date"><?= htmlspecialchars($item['date_of_completion']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <? else: ?>
        <p><?= $errors['search'] ?? '' ?></p>
    <? endif; ?>
</main>
