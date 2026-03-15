# McWills Consulting – Installation Guide (Shared Hosting / Joho)

This guide walks you through installing the **McWills Consulting** website and admin dashboard on **shared hosting** or a **Joho**-style platform. It is written so that even beginners can follow it step by step.

---

## Table of contents

1. [What you need before starting](#1-what-you-need-before-starting)
2. [Upload the project to your hosting](#2-upload-the-project-to-your-hosting)
3. [Point your domain to the right folder](#3-point-your-domain-to-the-right-folder)
4. [Create the database](#4-create-the-database)
5. [Configure the application (.env)](#5-configure-the-application-env)
6. [Install PHP dependencies (Composer)](#6-install-php-dependencies-composer)
7. [Generate app key and run migrations](#7-generate-app-key-and-run-migrations)
8. [Build the frontend (CSS/JS)](#8-build-the-frontend-cssjs)
9. [Storage link and permissions](#9-storage-link-and-permissions)
10. [Create the admin user](#10-create-the-admin-user)
11. [After installation](#11-after-installation)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. What you need before starting

### From your hosting provider

- **FTP/SFTP access** (e.g. FileZilla) or **file manager** in the control panel (cPanel, Plesk, Joho, etc.).
- **PHP 8.2 or higher** (8.3 is fine). Your host’s control panel usually shows the PHP version.
- **MySQL or MariaDB** – you must be able to create a database and a user with full rights to that database.
- **SSH access** (optional but helpful). If your plan includes SSH, you can run commands on the server; otherwise we’ll show how to build the site on your computer and upload the built files.

### On your computer (for building assets if you don’t have SSH)

- **Node.js** (LTS version) from [nodejs.org](https://nodejs.org).
- **Composer** from [getcomposer.org](https://getcomposer.org) (only needed if you build and upload from your PC).

### Required PHP extensions

Laravel needs these PHP extensions (most shared hosts have them):

- `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `json`, `bcmath`

You can check in your hosting control panel under “PHP extensions” or “Select PHP version”.

---

## 2. Upload the project to your hosting

### Option A: Upload with Git (if your host supports Git)

1. Connect via **SSH** (if available) and clone the repository into a folder, e.g. `mwills`:
   ```bash
   cd /home/yourusername
   git clone https://github.com/your-username/mwills.git
   cd mwills
   ```

2. If you don’t have Git on the server, upload the project as in **Option B**, but make sure the `.git` folder is not needed for the app to run (you can omit it when uploading).

### Option B: Upload with FTP / File Manager

1. On your computer, open the project folder (the one that contains `artisan`, `composer.json`, and the `app`, `public`, `config` folders).
2. **Upload the entire project** into a folder on the server, e.g. `mwills` or `htdocs/mwills`.
   - **Important:** Do **not** put the site inside `public`. Upload so that `public` is *inside* the project folder (e.g. `mwills/public`).
3. Do **not** upload:
   - `node_modules` (we will build assets separately)
   - `.env` (we will create it on the server)
   - `.git` (optional, saves space)

Correct structure on the server:

```
mwills/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← This folder will be the “document root” (see next section)
├── resources/
├── routes/
├── storage/
├── vendor/          ← Created later by Composer
├── .env             ← Created by you on the server
├── artisan
├── composer.json
└── ...
```

---

## 3. Point your domain to the right folder

Laravel must run with the **document root** set to the `public` folder, not the project root. Otherwise you risk exposing config and code.

### On cPanel

1. Go to **Domains** → **Domains** (or **Addon Domains** / **Subdomains**).
2. Click the domain (or add it).
3. Set **Document Root** to the `public` folder, e.g.:
   - `public_html/mwills/public`  
   or  
   - `/home/yourusername/mwills/public`

Save the change.

### On Plesk

1. Go to **Domains** → your domain → **Hosting & DNS** → **Hosting Settings**.
2. Set **Document root** to something like: `mwills/public` (relative to the home directory) or the full path to `public`.

### On Joho or similar

1. Find the option for **“Document root”**, **“Web root”**, or **“Website root”**.
2. Set it to the **`public`** folder inside your project, e.g. `mwills/public` or `/path/to/mwills/public`.

After this, `https://yourdomain.com` should point to `public`, and Laravel will load correctly.

---

## 4. Create the database

1. In your hosting control panel, open **MySQL® Databases** (or **Databases**).
2. **Create a new database**, e.g. `youruser_mwills`. Note the full name (often with a prefix like `youruser_`).
3. **Create a MySQL user** and set a strong password. Note username and password.
4. **Add the user to the database** with **All privileges**.
5. (Optional) If you use **phpMyAdmin**, you can create the database and user there instead.

You will need:

- **Database name** (e.g. `youruser_mwills`)
- **Database user**
- **Database password**
- **Host** (often `localhost`; your panel may show “MySQL host” or “Server”)

---

## 5. Configure the application (.env)

The app is configured via a `.env` file in the project root (same folder as `artisan`).

### Create .env on the server

1. In the project root (e.g. `mwills/`), create a file named **`.env`**.
2. If you have an **`.env.example`** in the project, you can copy it and rename the copy to `.env`. Otherwise create a new file and paste the content below, then adjust the values.

### Minimum .env configuration

Edit `.env` and set at least these (replace with your real values):

```env
APP_NAME="McWills Consulting"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=youruser_mwills
DB_USERNAME=youruser_dbuser
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

Notes:

- **APP_URL** – Must match your real site URL (e.g. `https://yourdomain.com`). No trailing slash.
- **APP_DEBUG** – Keep `false` on production.
- **DB_*** – Use the database name, user, and password from step 4. If your host uses a different DB host or port, change **DB_HOST** and **DB_PORT**.
- **MAIL_*** – Use your host’s SMTP settings (or a service like Mailtrap/Mailgun) so the contact form can send emails to the admin.

### Optional: Booking button (Calendly / TidyCal)

You can set the booking URL later in **Dashboard → Settings**. If you prefer to set it in `.env`:

```env
BOOKING_EMBED_URL=https://calendly.com/yourname/30min
```

Save the `.env` file. The **APP_KEY** will be generated in the next steps.

---

## 6. Install PHP dependencies (Composer)

Laravel needs Composer packages. You can do this **on the server** (if you have SSH) or **on your computer** and then upload `vendor`.

### Option A: On the server (SSH)

```bash
cd /path/to/mwills
composer install --no-dev --optimize-autoloader
```

Use the path where you uploaded the project. `--no-dev` is for production; omit it if you want dev tools.

### Option B: On your computer, then upload

1. On your PC, open the project folder in a terminal.
2. Run:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Upload the entire **`vendor`** folder to the same place on the server (project root). This can take a few minutes over FTP.

---

## 7. Generate app key and run migrations

These steps must run **on the server** (PHP has to see the real `.env` and database). Use either SSH or your host’s “Run PHP script” / “Cron” / “Terminal” feature.

### If you have SSH

```bash
cd /path/to/mwills

# Generate application key (required)
php artisan key:generate --force

# Create database tables
php artisan migrate --force

# Create symbolic link for uploads (blog images)
php artisan storage:link
```

### If you don’t have SSH

Many shared hosts offer a **“Terminal”** or **“SSH”** in the control panel – use that and run the same commands.

If there is no way to run commands:

1. **App key:** In the project root, create or edit `.env` and set:
   ```env
   APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX=
   ```
   You can generate a key locally with `php artisan key:generate --show` in a local copy of the project and paste it here.

2. **Migrations:** Use **phpMyAdmin** or the host’s “Import SQL” and run the SQL that creates the tables. Easiest is to run `php artisan migrate` once on your PC against a copy of the production database (or export the structure from a local DB and import it on the server). Alternatively, ask your host how to run `php artisan migrate` (e.g. via a “Run script” or “Cron job” that executes once).

3. **Storage link:** Some panels have “File manager” and “Create symbolic link”. If not, create a folder `public/storage` and in the panel set it as a symlink to `storage/app/public`. If that’s not possible, you can configure the app to use a different disk for uploads (advanced).

---

## 8. Build the frontend (CSS/JS)

The site uses **Vite** and **Tailwind CSS**. You need to build the assets and upload the built files.

### Option A: Build on the server (SSH + Node)

```bash
cd /path/to/mwills
npm ci
npm run build
```

This creates (or updates) the `public/build` folder. No need to upload if you already uploaded the project.

### Option B: Build on your computer, then upload

1. On your PC, in the project folder:
   ```bash
   npm ci
   npm run build
   ```
2. Upload the **`public/build`** folder to the server so it appears as `mwills/public/build` (with `manifest.json` and the JS/CSS files inside).

After this, the site should load with correct styling and scripts.

---

## 9. Storage link and permissions

### Storage link

If you didn’t run it in step 7:

```bash
php artisan storage:link
```

This makes `public/storage` point to `storage/app/public` so that images uploaded in the admin (e.g. blog post images) are reachable by the browser.

### Permissions (if you have SSH)

Laravel needs to write to `storage` and `bootstrap/cache`:

```bash
cd /path/to/mwills
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Replace `www-data` with the user your web server runs as (your host’s docs or support can tell you). If you don’t have SSH, many hosts set permissions automatically; if uploads or logs fail, ask support to make `storage` and `bootstrap/cache` writable by the web server.

---

## 10. Create the admin user

The project includes a seeder that creates one admin user.

Run on the server (SSH or panel terminal):

```bash
cd /path/to/mwills
php artisan db:seed --force
```

This creates:

- **Email:** `admin@mail.com`
- **Password:** `12345678`

**Important:** Log in and change this password immediately (e.g. via **Dashboard → profile/settings** if available, or by changing it in the database).

- **Admin URL:** `https://yourdomain.com/login`  
  (or `https://yourdomain.com/dashboard` after login)

---

## 11. After installation

1. **Change the default admin password** (see above).
2. **Test the site:** Visit the homepage, contact page, and blog (if you added posts).
3. **Configure mail:** Ensure `.env` mail settings are correct so the contact form sends the “new enquiry” email to the admin.
4. **Optional – Booking URL:** In the admin, go to **Settings** and set your Calendly (or TidyCal) booking URL so the “Book a Discovery Call” button works on the contact page.
5. **Logo / favicon:** Place your logo at `public/assets/images/logo.png` and favicon at `public/assets/images/favicon.ico` so the site uses them.

---

## 12. Troubleshooting

### “500 Internal Server Error”

- Set `APP_DEBUG=true` in `.env` temporarily and reload the page; check the error message (and the `storage/logs` file if you have access). Fix the issue, then set `APP_DEBUG=false` again.
- Confirm **document root** is the `public` folder (see section 3).
- Confirm **APP_KEY** is set (run `php artisan key:generate --force` if needed).
- Check that **storage** and **bootstrap/cache** are writable.

### Blank or unstyled page

- Ensure **`public/build`** exists and contains the built JS/CSS (and `manifest.json`). Re-run `npm run build` and re-upload `public/build` if needed.
- Confirm **APP_URL** in `.env` matches the URL you use to open the site (e.g. `https://yourdomain.com`).

### “No application encryption key has been specified”

- Run `php artisan key:generate --force` on the server, or add a valid **APP_KEY** to `.env` (see section 7).

### Database connection errors

- Check **DB_HOST**, **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD** (and **DB_PORT** if not 3306).
- Ensure the MySQL user has full rights on the database.
- Some hosts use a socket; try `DB_HOST=localhost` or the value given in the control panel (e.g. `localhost:/tmp/mysql.sock`).

### Contact form doesn’t send email

- Verify **MAIL_*** in `.env` (host SMTP or your email service).
- Check `storage/logs/laravel.log` for mail errors.
- Test with a simple “log” driver first: set `MAIL_MAILER=log`, submit the form, and see if an email is written to the log (then switch back to `smtp` with correct credentials).

### Images in blog posts don’t show

- Run `php artisan storage:link` so `public/storage` exists and points to `storage/app/public`.
- Ensure the web server can read files in `storage/app/public`.

### Can’t run artisan or composer on the server

- Use the host’s **Terminal** or **SSH** and run commands from the project root.
- If the host doesn’t allow that, you’ll need to run Composer and migrations locally (or on a staging server) and upload `vendor` and ensure the database structure is created (e.g. by importing SQL). For assets, always build locally and upload `public/build`.

---

## Quick reference (with SSH)

```bash
cd /path/to/mwills
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env: APP_URL, DB_*, MAIL_*, etc.
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan db:seed --force
npm ci
npm run build
# Set permissions: storage, bootstrap/cache
```

Then set the document root to `mwills/public` and open `https://yourdomain.com`. Log in at `https://yourdomain.com/login` with `admin@mail.com` / `12345678` and change the password.

---

*This guide is for **McWills Consulting** (Laravel + Livewire + MySQL). For host-specific steps (e.g. Joho panel), refer to your provider’s documentation for “document root”, “PHP version”, and “MySQL database” and match them to the steps above.*
