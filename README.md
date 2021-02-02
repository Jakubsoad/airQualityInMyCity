# airQualityInMyCity

Aplikacja do sprawdzania jakości powietrza w różnych miastach i lokalizacjach. Korzysta ona z API Głównego Inspektoratu Ochrony Środowiska - https://powietrze.gios.gov.pl/pjp/content/api.

Instrukcja uruchomienia:
- 
Wymagania:
* Composer
* Yarn
* Możliwość uruchomienia serwera PHP w wersji 7.3: 
może być za pomocą pakietu PHP:
```console
php -S localhost:8000 -t public/
```
* Albo z pomocą wbudowanego serwera w Symfony CLI (https://symfony.com/download)
```console
symfony serve
```