# Setup Guide for Running Locally

## Prerequisites

- PHP
- Composer
- Node.js & npm
- PostgreSQL

See the [prerequisites](prerequisites.md) page for detailed instructions.

## Steps
#### Clone the repository

```bash
git clone https://github.com/jonathank1906/Web-Technologies-Project.git
```
```bash
cd Web-Technologies-Project
```

#### Install PHP dependencies

```bash
composer install
```

#### Copy and configure environment file

```bash
cp .env.example .env
```
!!! note "Note:"
    Copy and paste from [here](env.md).

#### Install Node.js dependencies

```bash
npm install
```

#### Build frontend assets

```bash
npm run build
```

#### Create the PostgreSQL database

```bash
psql -U postgres
```
!!! note "Note:"
    Username and Password may vary depending on your installation.

Then, in the PostgreSQL prompt, run:

```sql
CREATE DATABASE laravel;
```

Type `\q` to exit.

#### Run database migrations

```bash
php artisan migrate
```

#### Running the project

Start the Vite development server (in one terminal):

```bash
npm run dev
```

Start the Laravel server (in another terminal):

```bash
php artisan serve
```

!!! info "Information:"
    Access the app by visiting [http://localhost:8000](http://localhost:8000) in your browser.
