# Запуск сервисов
- Чтобы запустить сервисы выполните команду `docker compose -f docker-compose.yml -f docker-compose.test.yml up -d`
- Приложение доступно по адресу localhost:8080

Данные из ТЗ уже загружены в базу.

# Endpoints
Расчет цены - `/get-price` c GET параметрами `'productId*', 'taxNumber*', 'couponCode'`
Оплата - `/pay` c POST json 

```
{
    "product": "1",
    "taxNumber": "DE123456789",
    "couponCode": "D15",
    "paymentProcessor": "paypal"
}
```

# Тестирование
Для запуска тестов выполните команду `docker compose exec backend make tests`