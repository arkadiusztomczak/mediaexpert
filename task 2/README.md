# Task 2

Implementacja zawiera zrealizowane klasy zawierające realizację metod CRUD (`ObjectOperations.php` i `StatusHistoryOperations.php`), klasę obsługującą bazę danych (`Database.php`), a także realizację przykładowych funkcjonalności w pliku `App.php`.   
Ponadto plik `mediaexpert.sql` zawiera zrzut pozwalający zbudować bazę danych. Zrzut wykonany został w środowisku PhpMyAdmin.  
W pliku `Api.php` znajduje się implementacja REST API.

## Krótki opis zrealizowanego REST'a

### Funkcjonalności

- Tworzenie nowych obiektów
- Pobieranie obiektów po id
- Aktualizacja istniejących obiektów
- Usuwanie obiektów
- Wyszukiwanie obiektów według numeru, daty, statusu lub jednego z poprzednich statusów

## Endpointy

### POST `/object`
Tworzenie nowego obiektu. 

#### Payload
```
{
"number": "1234",
"status": "active"
}
```

### GET `/object/{id}`

Zwraca informacje dotyczące wskazanego obiektu

### PUT `/object/{id}`
Aktualizacja danych konkretnego obiektu. W payloadzie należy zawrzeć aktualizowane dane, np.
```
{
"status": "inactive"
}
```
spowoduje zmianę statusu na `inactive`. Wszelkie zmiany statusów obsługiwane są triggerami i automatycznie umieszczane w bazie danych w tabeli `status_history`.


### DELETE `/object/{id}`

Powoduje usunięcie obiektu

### GET `/search`
Zwraca listę obiektów według kryterów. 
- `number`, 
- `date`, 
- `current_status`, 
- `past_status`
#### Poniższy payload zwróci tylko aktywne obiekty
```
{
"current_status": "active"
}
```