# TimeStats
A simple API to get simple Time Statistics

## Testing

```
git clone git@github.com:andreribas/timestats.git
composer install
vendor/bin/phpunit
php artisan serve
```

In another terminal test with:
```
curl -H "Content-Type: application/json" \
-X POST localhost:8000/api/getStats \
-d '{"time_list": ["11:23", "12:14", "15:23", "09:15", "12:44", "15:11"]}'
```

You should see something like:
```
{
    "occurrencesPerHour": [
        {
            "hour": "11:00",
            "count": 1
        },
        {
            "hour": "12:00",
            "count": 2
        },
        {
            "hour": "15:00",
            "count": 2
        },
        {
            "hour": "09:00",
            "count": 1
        }
    ],
    "topOccurrencesHour": {
        "hour": "15:00",
        "count": 2
    },
    "averageOccurrencesPerHour": 1,
    "biggestInterval": {
        "intervalInMinutes": 368,
        "times": [
            "15:23",
            "09:15"
        ]
    },
    "smallestInterval": {
        "intervalInMinutes": 12,
        "times": [
            "15:23",
            "15:11"
        ]
    }
}
```
