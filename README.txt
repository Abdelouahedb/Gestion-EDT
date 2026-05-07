<div align="center">

# 📅 GestionEDT

### A clean, conflict-aware school timetable manager

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Chart.js](https://img.shields.io/badge/Chart.js-4.4-FF6384?style=for-the-badge&logo=chart.js&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Live-success?style=for-the-badge)

**A web app to manage school timetables — built from scratch with PHP, MySQL & vanilla JS.**

[🌐 Live Demo](http://gestion-edt.page.gd) • [🐛 Report Bug](https://github.com/Abdelouahedb/Gestion-EDT/issues) • [💡 Request Feature](https://github.com/Abdelouahedb/Gestion-EDT/issues)

</div>

---

## ✨ About

**GestionEDT** is a full-stack web application that helps schools and universities organize their weekly schedules without headaches. Create classes, assign teachers, book rooms — and let the system catch every conflict before it becomes a problem.

Built as a 2-month academic project at **ENSIASD Taroudant** (MGSI program, 2025/2026), with a focus on clean code, real-world security practices, and a UI that doesn't look AI-generated.

---

## 🎯 What it does

> The system **automatically detects conflicts** before saving a session — no double-booked rooms, no overworked teachers, no overlapping classes for the same group.

### Three roles, three experiences

| 👑 Admin | 👨‍🏫 Teacher | 👨‍🎓 Student |
|---|---|---|
| Full CRUD on everything | Personal schedule view | Class schedule view |
| Statistics & charts | Print to PDF | Filter by filière + semester |
| Conflict-aware planner | | Print to PDF |

---

## 🚀 Features

<table>
<tr>
<td valign="top" width="50%">

### 🔐 Authentication
- Role-based login (admin / teacher / student)
- Self-registration for students
- Live password strength meter
- Real-time confirm-password check
- Bcrypt-hashed passwords

### 📊 Dashboard
- 4 live statistic cards
- Bar chart: sessions per weekday
- Doughnut chart: Cours / TD / TP split
- Powered by Chart.js

</td>
<td valign="top" width="50%">

### 🛠️ Management
- Filières, semesters, teachers
- Rooms, modules, time slots
- Schedule builder with **3-way conflict detection**
- Filterable session list

### 📅 Planning
- Per-student schedule (filtered by filière + semester)
- Per-teacher schedule
- One-click print / PDF export
- Mobile-friendly horizontal scroll on tables

</td>
</tr>
</table>

---

## 🖼️ Screenshots

| Landing Page | Dashboard |
|:---:|:---:|
| ![Landing](doc/captures/landing_page_hero.png) | ![Dashboard](doc/captures/page_dashboard_admin.png) |

| Login | Schedule Builder |
|:---:|:---:|
| ![Login](doc/captures/page_login.png) | ![Emploi](doc/captures/page_emploi.png) |

---

## 🛠️ Tech Stack

<div align="center">

| Frontend | Backend | Database | Tooling |
|:---:|:---:|:---:|:---:|
| HTML5 | PHP 8 | MySQL / MariaDB | XAMPP (local) |
| CSS3 (custom) | PDO | utf8mb4 | InfinityFree (prod) |
| Vanilla JS | Sessions | | Git + GitHub |
| Chart.js 4.4 | | | |

</div>

**No frameworks. No Bootstrap. No npm.** Pure handwritten code, because we wanted to actually understand what we're shipping.

---

## ⚡ Quick Start

```bash
# 1. Clone the repo
git clone https://github.com/Abdelouahedb/Gestion-EDT.git
cd Gestion-EDT

# 2. Move it to your local server
# XAMPP: C:\xampp\htdocs\
# WAMP:  C:\wamp64\www\
# LAMP:  /var/www/html/

# 3. Import the database
# Open phpMyAdmin → Import → choose projet.sql → Go

# 4. Check config.php (default works with XAMPP)

# 5. Visit the app
# http://localhost/Gestion-EDT/
```

### 🔑 Default login

```
Username:  ENSIASD
Password:  ENSIASD2026
```

> 📋 Full installation guide and additional test accounts are in [`README.txt`](README.txt).

---

## 🛡️ Security

We took security seriously even though it's a school project:

- ✅ **Bcrypt** password hashing (`password_hash`, `password_verify`)
- ✅ **PDO prepared statements** everywhere → no SQL injection
- ✅ **`htmlspecialchars()`** on all output → no XSS
- ✅ **Server-side validation** on every form
- ✅ **Role check** before every sensitive action
- ✅ **Session-based auth** with proper cleanup on logout

---

## 📂 Project Structure

```
emplois_manage_mgsi/
├── index.php              ← Landing page
├── config.php             ← DB connection
├── projet.sql             ← Database export
├── css/style.css          ← All styling (custom, ~1300 lines)
├── js/script.js           ← Client-side scripts
├── includes/              ← navbar.php, footer.php
├── pages/                 ← All app pages (12 files)
└── doc/                   ← Captures, diagrams, evaluation form
```

---

## 🌐 Live Demo

**Hosted on InfinityFree:** [gestion-edt.page.gd](http://gestion-edt.page.gd)

Free hosting tier — may be slow on cold start, but everything works.

---

## 👥 Team

<div align="center">

| <a href="https://github.com/Abdelouahedb"><img src="https://github.com/Abdelouahedb.png" width="80px" alt=""/></a> | 👩‍💻 |
|:---:|:---:|
| **Abdelouahed Boudrari** | **Nada Nassraoui** |
| [@Abdelouahedb](https://github.com/Abdelouahedb) | |

**Supervised by** *M. LAASSEM*
**Filière** *MGSI — ENSIASD Taroudant — 2025/2026*

</div>

---

## 📜 License

This project is open source under the [MIT License](LICENSE) — feel free to use it as a starting point for your own school project.

---

## ⭐ Like it?

If this helped you or you found it interesting, drop a star — it costs nothing and makes my day.

<div align="center">

**[⬆ Back to top](#-gestionedt)**

Made with ☕ in Morocco 🇲🇦

</div>
