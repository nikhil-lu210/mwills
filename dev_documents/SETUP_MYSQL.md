# MySQL setup

The project is configured to use **MySQL** (see `.env`: `DB_CONNECTION=mysql`).

1. **Create the database** (e.g. in MySQL or MariaDB):
   ```sql
   CREATE DATABASE mwills CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Set credentials in `.env`** if needed:
   - `DB_DATABASE=mwills`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`

3. **Run migrations**:
   ```bash
   php artisan migrate
   ```

4. **Logo (optional)**  
   Copy your logo to `public/images/logo.png` (e.g. from `dev_documents/website_logo.png`). The nav and footer will show it when present.

5. **Build frontend** (first time or after CSS/JS changes):
   ```bash
   npm install
   npm run build
   ```
