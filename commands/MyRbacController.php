<?php

namespace app\commands;

use Yii;
use app\models\rbac\AuthorRule;
use app\models\User;
use yii\console\Controller;

/**
 * Инициализатор RBAC выполняется в консоли php yii my-rbac/init
 */
class MyRbacController extends Controller
{
  public function actionInit()
  {
    $auth = Yii::$app->authManager;

    $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

    // Создадим роли модератора, продавца и покупателя
    $moderator = $auth->createRole(User::ROLE_MODERATOR);
    $moderator->description = 'Модератор';
    $user = $auth->createRole(User::ROLE_USER);
    $user->description = 'Пользователь';

    // запишем их в БД
    $auth->add($moderator);
    $auth->add($user);

    // Создаем наше правило, которое позволит проверить автора новости
    $authorRule = new AuthorRule;

    // Запишем его в БД
    $auth->add($authorRule);

    // Создаем разрешение на редактирование контента (объявлений и комментариев) updateNews
    $updateContent = $auth->createPermission('updateContent');
    $updateContent->description = 'Редактирование контента';

    // Создаем разрешение на редактирование своего контента (объявлений и комментариев)
    $updateOwnContent = $auth->createPermission('updateOwnContent');
    $updateOwnContent->description = 'Редактирование своего контента';

    //Указываем правило AuthorRule для разрешения updateOwnContent.
    $updateOwnContent->ruleName = $authorRule->name;

    // Запишем все разрешения в БД
    $auth->add($updateContent);
    $auth->add($updateOwnContent);

    /**
     * После создания 'updateOwnContent' и 'updateContent' сделаем первого родителем второго
     * И затем в экшне просто делать проверку:
     *
     * if (!\Yii::$app->user->can('updateContent', ['autor_id' => $contentModel])) {
     *    throw new ForbiddenHttpException('Access denied');
     * }
     *
     * И проверка будет производиться в такой последовательности:
     *
     * 1. Проверяется разрешение 'updateContent' - если разрешено, выполняется код дальше; если запрещено - выполняется следующая проверка;
     *
     * 2. Проверяется разрешение 'updateOwnContent' c использованием правила 'isAuthor' - если разрешено ,выполняется код дальше; если запрещено - выбрасывается Exception.
     *
     */
    $auth->addChild($updateOwnContent, $updateContent);

    // Теперь добавим наследования. Для роли user мы добавим разрешение updateOwnContent,
    // а для админа добавим наследование от роли user

    // Роли «Пользователь» присваиваем разрешение «Редактирование своего контента»
    $auth->addChild($user, $updateOwnContent);

    // Модератор имеет собственное разрешение - «Редактирование контента»
    $auth->addChild($moderator, $updateContent);

    // Модератор наследует роль пользователя
    $auth->addChild($moderator, $user);
  }
}
