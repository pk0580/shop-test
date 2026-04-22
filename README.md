# Shop Test API

Тестовое приложение на Laravel 11 для поиска товаров с использованием фильтрации, сортировки и пагинации.

## Технологический стек
- **PHP 8.3**
- **Laravel 11**
- **MySQL 8.0**
- **Docker & Docker Compose**

## Архитектура
Проект реализован с использованием модульного подхода (Domain-Driven Design lite):
- **Domain**: Интерфейсы репозиториев, сущности (Eloquent модели используются как анемичные модели в слое Infrastructure).
- **Application**: Use Cases (Actions) и DTO.
- **Infrastructure**: Реализация репозиториев (Eloquent), миграции.
- **Presentation**: Контроллеры, Form Requests (валидация).

Путь к модулю товаров: `app/Modules/Product`.

## Установка и запуск

1. **Клонируйте репозиторий**

2. **Настройте окружение**
   ```bash
   cp src/.env.example src/.env
   ```
   *(Убедитесь, что параметры подключения к БД соответствуют настройкам в docker-compose.yml)*

3. **Запустите контейнеры**
   ```bash
   docker-compose up -d
   ```

4. **Установите зависимости (если необходимо)**
   ```bash
   docker-compose exec php composer install
   ```

5. **Выполните миграции**
   ```bash
   docker-compose exec php php artisan migrate
   ```

## API Документация

### Поиск товаров
`GET /api/products`

**Параметры запроса (Query Params):**
- `q` — поиск по подстроке в названии товара (`name`).
- `price_from` — минимальная цена.
- `price_to` — максимальная цена.
- `category_id` — ID категории.
- `in_stock` — наличие на складе (`1` или `true` / `0` или `false`).
- `rating_from` — минимальный рейтинг (0–5).
- `sort` — сортировка:
  - `price_asc` — дешевле.
  - `price_desc` — дороже.
  - `rating_desc` — высокий рейтинг.
  - `newest` — сначала новые.
- `page` — номер страницы.
- `per_page` — количество элементов на странице (по умолчанию 15, макс 100).

**Пример запроса:**
`GET /api/products?q=iPhone&price_from=50000&sort=price_asc`

## Тестирование
Для запуска тестов используйте команду:
```bash
docker-compose exec php php artisan test
```
Покрыты сценарии:
- Поиск по названию.
- Фильтрация по всем параметрам.
- Валидация входных данных.
- Сортировка.
- Пагинация.
