Install composer dependencies

```
composer install
```

Failing test using `@Type("array<Hashid,string>")`

```
vendor/bin/phpunit test/SerializerTest.php
```

Working test with workaround using `@Type("KeyValueArray<Hashid,string>")`

```
vendor/bin/phpunit test/WorkaroundTest.php
```
