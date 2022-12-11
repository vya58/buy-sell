1) Для установки базы данных Выполните в SQL-менеджере код, в файле data/schema.sql

2) Для установки DbManager Выполните в консоли, в папке проекта, команду: yii migrate --migrationPath=@yii/rbac/migrations

3) Для настройки ролей RBAC в консоли, в папке проекта, команду: php yii my-rbac/init

4) Примените имеющиеся миграции: yii migrate
