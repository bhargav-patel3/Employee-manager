# Employee Manager — CodeIgniter 4 + MySQL CRUD Demo

A small, clean CRUD application built with **PHP (CodeIgniter 4)** and **MySQL**, designed to run against a **managed AWS RDS MySQL** database. Single-page UI with smooth animations and toast notifications for a polished demo.

**Fields:** `id`, `name`, `role` — Create, Read, Update, Delete, all on one page via AJAX (no page reloads).

## Why this project exists

This project is a simple yet powerful showcase of my DevOps and cloud engineering mindset. I built it as a basic single-page employee manager application to demonstrate a complete AWS deployment story — from networking and availability to load balancing, security, and scalability. The goal is to highlight how even a small app can be used to illustrate real-world AWS architecture patterns such as multi-AZ design, subnets, application load balancers, target groups, and security groups.

> 💡 AI highlight: This project was vibe-coded with just a single prompt, which also reflects my AI-assisted development skills. It showcases how I can quickly turn an idea into a working application while keeping the focus on architecture, automation, and cloud-native thinking.

---

## ✨ Features

- Single-page frontend (add / edit / delete employees, all inline)
- Full list of saved entries loaded from the DB right on the same page
- AJAX (fetch) calls — no full page reloads
- Toast notifications for success/error
- Smooth CSS animations (fade-ins, sliding toasts, hover states)
- Server-side validation with inline field errors
- Clean CodeIgniter 4 MVC structure (Controller / Model / View)
- Runs with **or without Composer** (framework is bundled)
- Works with local MySQL, Docker, or AWS RDS
- Dockerfile + docker-compose for both local and RDS setups

---

## 🧱 Tech Stack

| Layer     | Technology                          |
|-----------|--------------------------------------|
| Backend   | PHP 8.2+ / CodeIgniter 4              |
| Database  | MySQL 8 (AWS RDS managed instance)   |
| Frontend  | HTML5, CSS3 (custom, no framework), Vanilla JS (fetch API) |
| Hosting   | AWS EC2 (Apache/PHP) or Docker        |

---

## 📁 Project Structure (key files)

```
app/
 ├── Controllers/EmployeeController.php   # index / list / create / update / delete
 ├── Models/EmployeeModel.php             # validation rules + DB access
 ├── Views/employees/index.php            # single-page UI (HTML+CSS+JS, list + edit + delete)
 ├── Database/Migrations/..._CreateEmployeesTable.php
 └── Config/Database.php                  # reads DB creds from .env
public/index.php                          # front controller
.env.example                              # copy to .env and fill in your DB
Dockerfile
docker-compose.yml                        # points app container at AWS RDS
docker-compose.local.yml                  # app + local MySQL container (no RDS needed)
```

---

## 1️⃣ Conventional Setup (no Docker)

### Requirements
- PHP **8.2+** with extensions: `mysqli`, `intl`, `mbstring`, `curl`, `xml` (most are enabled by default)
- A MySQL-compatible database — either local MySQL or your AWS RDS instance
- (Optional) Composer — **not required to run this project**. The CodeIgniter framework is already bundled under `vendor/codeigniter4/framework`, so it runs out of the box.

### Steps

```bash
# 1. Unzip the project and enter the folder
cd employee-manager

# 2. Create your .env file from the template
cp .env.example .env

# 3. Edit .env and set your database details
database.default.hostname = YOUR-RDS-ENDPOINT.rds.amazonaws.com
database.default.database = employee_db
database.default.username = admin
database.default.password = YOUR_DB_PASSWORD
database.default.port     = 3306

# also set your base URL, e.g.:
app.baseURL = 'http://localhost:8080/'

# 4. Run the database migration (creates the `employees` table)
php spark migrate --all

# 5. Start the built-in PHP server
php spark serve --host 0.0.0.0 --port 8080

# 6. Open in your browser
http://localhost:8080
```

That's it — add, edit, and delete employees directly from the page; the table below the form always reflects what's in the database.

### Deploying on a real Apache/Nginx server (e.g. EC2)
Point your web server's **document root to the `public/` folder** (not the project root). Everything else works the same as above — just set `app.baseURL` in `.env` to your real domain/IP.

Example Apache vhost:
```apache
<VirtualHost *:80>
    ServerName your-domain-or-ip
    DocumentRoot /var/www/employee-manager/public

    <Directory /var/www/employee-manager/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Make sure `mod_rewrite` is enabled (`sudo a2enmod rewrite`) so CodeIgniter's routing works.

---

## 2️⃣ Docker Setup

Two ready-made compose files are included.

### Option A — App connects directly to your AWS RDS

```bash
# 1. Export your RDS credentials
export DB_HOST=your-rds-endpoint.rds.amazonaws.com
export DB_NAME=employee_db
export DB_USER=admin
export DB_PASS=your_db_password

# 2. Build and start the app container
docker-compose -f docker-compose.yml up -d --build

# 3. Run the migration inside the running container
docker-compose -f docker-compose.yml exec app php spark migrate --all

# 4. Open
http://localhost:8080
```

> Make sure your RDS **security group** allows inbound MySQL (port 3306) traffic from wherever this container runs (your EC2 instance's security group, or your IP if testing locally).

### Option B — Fully local (app + MySQL container, no RDS needed)

Useful for testing the whole stack before pointing it at AWS.

```bash
docker-compose -f docker-compose.local.yml up -d --build

# Run the migration once the db container is healthy
docker-compose -f docker-compose.local.yml exec app php spark migrate --all

# Open
http://localhost:8080
```

To stop and remove containers:
```bash
docker-compose -f docker-compose.local.yml down       # keeps DB volume
docker-compose -f docker-compose.local.yml down -v    # also wipes DB data
```

---

## 🗄️ Setting up AWS RDS (MySQL) — quick reference

1. AWS Console → **RDS** → **Create database**
2. Engine: **MySQL** (8.0)
3. Templates: **Free tier** (fine for this demo) or **Dev/Test**
4. Set DB instance identifier, master username (`admin`) and password
5. Instance class: `db.t3.micro` is plenty for this small app
6. Under **Connectivity**:
   - Choose the VPC your EC2 instance will be in
   - Public access: **Yes** only if connecting from outside AWS (e.g. your laptop); otherwise **No**
   - Create/select a **security group** that allows inbound **port 3306** from your EC2 instance's security group (or your IP for local testing)
7. Create the database, wait for status **Available**
8. Copy the **Endpoint** (hostname) shown in the RDS console — this goes into `database.default.hostname` in `.env`
9. Create the actual schema/database name (e.g. `employee_db`) by connecting once with a MySQL client:
   ```bash
   mysql -h YOUR-RDS-ENDPOINT.rds.amazonaws.com -u admin -p -e "CREATE DATABASE employee_db;"
   ```
10. Run `php spark migrate --all` (conventional) or the Docker migrate command above — this creates the `employees` table for you.

---

## ☁️ Deploying on AWS EC2 — quick reference

1. Launch an EC2 instance (Ubuntu 22.04/24.04, `t2.micro` is enough)
2. Security group: allow inbound **22** (SSH) and **80**/**8080** (HTTP) from your IP / anywhere
3. SSH in and install PHP + Apache:
   ```bash
   sudo apt update
   sudo apt install -y apache2 php php-mysqli php-mbstring php-intl php-curl php-xml unzip
   sudo a2enmod rewrite
   ```
4. Upload/clone your project into `/var/www/employee-manager` (e.g. via `scp` or `git clone` your GitHub repo)
5. Set the Apache vhost's `DocumentRoot` to `/var/www/employee-manager/public` (see vhost example above)
6. `cp .env.example .env` and fill in your RDS details
7. `php spark migrate --all`
8. `sudo systemctl restart apache2`
9. Make sure the EC2 instance's security group / RDS security group allow traffic to RDS on port 3306
10. Visit `http://<your-ec2-public-ip>` 🎉

*(If you'd rather run the app in Docker on EC2: install Docker + docker-compose on the instance, then follow "Docker Setup — Option A" above.)*

---

## 🔌 API Endpoints (used internally by the page's JS)

| Method | Endpoint             | Description              |
|--------|-----------------------|---------------------------|
| GET    | `/`                    | Loads the page            |
| GET    | `/employees`           | List all employees (JSON) |
| POST   | `/employees`           | Create a new employee     |
| PUT    | `/employees/{id}`      | Update an employee        |
| DELETE | `/employees/{id}`      | Delete an employee        |

All responses are JSON: `{ "status": "success" | "error", "message": "...", "data": ... }`

---

## 🛠 Troubleshooting

- **"Unable to connect to the database"** → double-check `.env` hostname/username/password/port, and that the RDS security group allows inbound 3306 from where the app is running.
- **Blank page / 404 on routes** → make sure your web server's document root points to the `public/` folder, and `mod_rewrite` is enabled with `AllowOverride All`.
- **Styling/JS not loading** → this is a single self-contained view file (`app/Views/employees/index.php`), there are no separate static assets to misconfigure.

---

## 📄 License

Free to use for personal projects, portfolios, and demos.
