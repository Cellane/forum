# Forum

This is an open source forum that was built and maintained at Laracasts.com.

## Installation

### Step 1

> To run this project, you must hav PHP 7 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer
dependencies.

```bash
git clone https://github.com/Cellane/forum
cd forum && composer install
php artisan key:generate
cp .env.example .env
```

### Step 2

Next, create a new database and reference its name and username/password within
the project’s `.env` file. In the example below, we’ve named the database
`forum`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum
DB_USERNAME=root
DB_PASSWORD=
```

Then, migrate your database to create the required tables.

```bash
php artisan migrate
```

### Step 3

Until an administration portal is available, manually insert any number of
“channels” (think of these as forum categories) into the `channels` table in
your database.

Once finished, clear your server cache, and you’re all set to go!

```bash
php artisan cache:clear
```

### Step 4

Use your forum! Visit `http://forum.dev/threads` to create a new account and
publish your first thread.
