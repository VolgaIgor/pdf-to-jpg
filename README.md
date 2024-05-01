# PDF to JPG

Учебный проект. Построен на базе [Yii2](https://github.com/yiisoft/yii2-app-basic).

## Запуск
Запустить команду `docker compose up -d --build`

## Консольные команды
### `yii clear/all`
Удаляет все архивы и очищает временную директорию.

### `yii clear/old`
Удаляет все архивы которые были созданы более `MAX_LIVE_ZIP_MIN` в `commands/ClearController` минут назад.