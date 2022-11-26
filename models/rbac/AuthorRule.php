<?php

namespace app\models\rbac;

use yii\rbac\Rule;

/**
 * @param string|integer $user_id ID пользователя.
 * @param Item $item роль или разрешение с которым это правило ассоциировано
 * @param array $params параметры, переданные в ManagerInterface::checkAccess(), например при вызове проверки
 * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
 */
class AuthorRule extends Rule
{
  public $name = 'isAuthor';

  public function execute($user_id, $item, $params)
  {
    return isset($params['autor_id']) ? $params['autor_id']->createdBy === $user_id : false;
  }
}
