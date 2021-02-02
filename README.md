# airQualityInMyCity

Aplikacja do sprawdzania jakości powietrza w różnych miastach i lokalizacjach. Korzysta ona z API Głównego Inspektoratu Ochrony Środowiska - https://powietrze.gios.gov.pl/pjp/content/api.

Instrukcja uruchomienia:
- 
Wymagania:
* Composer
* Yarn
* Możliwość uruchomienia serwera PHP w wersji 7.3: 
może być za pomocą pakietu PHP, albo z pomocą wbudowanego serwera w Symfony CLI (https://symfony.com/download)
1. Zainstaluj zależności composera:
    ```console
    composer install
    ```
2. Zainstaluj zależności yarna:
    ```console
    yarn install
    ```
3. Uruchom serwer PHP:
    
    pakiet PHP
    ```console
    php -S localhost:8000 -t public/
    ```
   symfony CLI
    ```console
    symfony serve
    ```
...albo w jakikolwiek inny sposób :)
