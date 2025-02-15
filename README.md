## Installation

### Create DB
use `database.sql` to create the DB

```bash
mysql -u root -p < database.sql
```

### Configure GMAIL to send emails from code

https://kinsta.com/es/blog/gmail-smtp-servidor/

https://support.google.com/mail/answer/7126229?visit_id=638254173760291571-1000859542&hl=es&rd=1#zippy=%2Cpaso-cambia-la-configuraci%C3%B3n-de-smtp-y-otros-ajustes-en-tu-cliente-de-correo

https://aruljohn.com/blog/php-send-email/

### Create Credentials File

- Create a file named `config.ini` in this folder, with the following content:

```ini
[database]
server = "localhost";
database_name = "db_esp32";
// replace with database username
database_username = "root";
// replace with database user password
database_password = "secret";

[mail]
mail_host = "smtp.gmail.com"
mail_username = "user@gmail.com"
mail_password = "secret"
mail_port = 465
```

### Install dependencies

```bash
composer install
```

## Usage

- Run

```bash
php -S localhost:8080
```

- Send an HTTP request to store data into DB

```Bash
curl -X POST "http://localhost:8080/" \
-H "Content-Type: application/x-www-form-urlencoded" \ 
-d "api_key=tPmAT5Ab3j7F9&sensor_names=MAX3010&temperature=14.75&heart_rate=49.54&oxygen_saturation=1005.14"
```

- To test this project, you can also reuse the postman collection in [docs/ESP32.postman_collection.json](docs/ESP32.postman_collection.json)
