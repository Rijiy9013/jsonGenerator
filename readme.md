
Этот проект предназначен для генерации дерева из CSV файла и сохранения его в формате JSON.

## Установка

1. Установите зависимости с помощью Composer:
    ```sh
    composer install
    ```

## Использование

2. Запустите скрипт для генерации дерева:
    ```sh
    php gentree.php input.csv output.json
    ```

    - `input.csv`: Входной файл в формате CSV.
    - `output.json`: Выходной файл в формате JSON.

## Тестирование

3. Запустите тесты с помощью PHPUnit:
    ```sh
    vendor/bin/phpunit --testdox tests/TreeGeneratorTest.php
    ```

   Внутри теста сравнивается `output.json` с `output-true.json` для проверки корректности работы скрипта.
