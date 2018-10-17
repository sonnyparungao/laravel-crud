Laravel Book Crud

Steps To Run the Project

1. Clone the Project Repository
2. Go to the Project Directory and open command line
3. Run composer install
4. Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Linux
5. Run php artisan key:generate
6. Create Database,  update the database configuration on the .env file
7. Run php artisan migrate
8. Run php -S localhost:8000 -t public

