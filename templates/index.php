
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="GET" autocomplete="off">
    <input class="search-form__input" type="text" name="search" value="<?=($_GET["search"]) ?? ""; ?>" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
<?php
$params = $_GET;
$page = pathinfo(__FILE__, PATHINFO_BASENAME);
$query = http_build_query($params);
$url = "/" . $page . "?" . $query;
?>
    <nav class="tasks-switch">
        <a href="index.php" class="tasks-switch__item <?=!($_GET["tab"]) ? "tasks-switch__item--active" : "" ; ?>">Все задачи</a>
        <a href="<?=$url . "&tab=today"; ?>" class="tasks-switch__item <?=($_GET["tab"] === "today") ? "tasks-switch__item--active" : "" ; ?>">Повестка дня</a>
        <a href="<?=$url . "&tab=tomorrow"; ?>" class="tasks-switch__item <?=($_GET["tab"] === "tomorrow") ? "tasks-switch__item--active" : "" ; ?>">Завтра</a>
        <a href="<?=$url . "&tab=expired"; ?>" class="tasks-switch__item <?=($_GET["tab"] === "expired") ? "tasks-switch__item--active" : "" ; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= ($show_complete_tasks === 1) ? "checked" : ""; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>

</div>

<table class="tasks">
<?php if ($tasks): ?>
<?php foreach ($tasks as $key => $value): ?>
    <?php if (!(!$show_complete_tasks && $value["task_status"])): ?>

    <tr class="tasks__item task <?= ($value["task_status"]) ? "task--completed" : ""; ?> <?= (time_is_up($value)) ? "task--important" : ""; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$value["task_id"]; ?>" <?=$value["task_status"] ? "checked" : ""; ?>>
                <span class="checkbox__text"><?=strip_tags($value["task_name"]); ?></span>
            </label>
        </td>

        <td class="task__file"><a href="uploads/<?=$value["task_file"]; ?>"><?=$value["task_file"]; ?></a></td>

        <td class="task__date"><?=htmlspecialchars($value["task_timeout"]); ?></td>
    </tr>
    <?php endif; ?>

<?php endforeach; ?>
<?php else: ?>
    <div>
        <p><?=$search_message; ?></p>
    </div>
<?php endif; ?>

</table>
