Zrealizowane funkcjonalności
1. CRUD
Zaimplementowano pełne operacje CRUD dla zadań, z następującymi polami:

Nazwa zadania (wymagane, max. 255 znaków)

Opis (opcjonalny)

Priorytet (low / medium / high)

Status (to-do / in progress / done)

Termin wykonania (wymagany, data)

2. Przeglądanie zadań
Umożliwiono filtrowanie zadań według:

priorytetu

statusu

terminu wykonania

3. Powiadomienia e-mail
Zadania wysyłają automatyczne powiadomienia e-mail na 1 dzień przed terminem wykonania.
Zastosowano mechanizmy Laravel Queues i Scheduler (komendy Artisan w cronie).

4. Walidacja
Wszystkie dane wejściowe są walidowane:

sprawdzanie obecności wymaganych pól

poprawność formatu daty

limity długości dla pól tekstowych

5. Obsługa wielu użytkowników
Wdrożono system uwierzytelniania Laravel Breeze.
Każdy użytkownik ma dostęp tylko do swoich zadań.

6. Udostępnianie zadań przez link
Umożliwiono generowanie publicznych linków z tokenem do zadań.
Linki mają ograniczony czas ważności – po jego upływie dostęp zostaje automatycznie zablokowany.

7. Historia edycji zadań (funkcja dodatkowa)
Zaimplementowano zapisywanie historii zmian dla każdego zadania – nazwa, opis, priorytet, status i termin.
Użytkownik może przeglądać poprzednie wersje zadania.

Moje przemyślenia:
W zadaniu skupiłem się głwonie na backendzie, na co dzień robię frontend w react js ale przez kilka miesięcy pracowałenm z vue komercyjnie.
Zastosowałem osobne tabele w dla statusów i piorytetów żeby zwiększyć skalowalność apliakcji. Frontend podzieliłem na komponenty.
