Dane logowania do testowego usera: <br>
email: user@example.com <br>
password: 123456789

## 🚀 Start projektu od zera

### 1. Sklonuj repozytorium

```bash
git clone https://github.com/Dawid-Czyzewski/Grupa-RBR-zadanie-rekrutacyjne.git
cd Grupa-RBR-zadanie-rekrutacyjne


```

### 2. Skopiuj plik `.env`

```bash
cp .env.example .env
```

### 3. Uruchom kontenery Dockera

```bash
docker-compose up -d --build

```

### 4. Wejdź do kontenera aplikacji

```bash
docker exec -it laravel-app bash
```

### 5. Zainstaluj zależności PHP

```bash
composer install

```

### 6. Wygeneruj klucz aplikacji

```bash
php artisan key:generate
```

### 7. Wykonaj migracje do bazy danych

```bash
php artisan migrate

```

### 8. Zainstaluj zależności JavaScript na hoście

Upewnij się, że masz zainstalowane Node.js i npm:

```bash
npm install
npm run build

```

### 9. Otwórz przeglądarkę:

```text
http://localhost:8000/tasks
```
