# 💰 Personal Expense Tracker

A comprehensive, full-stack web application designed to manage personal finances. It allows users to track expenses and revenues, visualize financial habits through dynamic charts, and filter data by timeframes and categories.

Built with a **Mobile-First** approach using **TailwindCSS** and **DaisyUI**, featuring a responsive Single Page Application (SPA) feel thanks to **AJAX** integration.

## ✨ Features

### 🖥️ User Interface & UX
*   **Dashboard Overview:** Immediate snapshot of the last 30 days with a Doughnut Chart and key statistics (Income, Outcome, Bills, Food).
*   **Dark/Light Mode:** Integrated theme switcher using DaisyUI (Emerald & Night themes) with session persistence.
*   **Responsive Design:** Optimized for mobile, tablet, and desktop views with a collapsible sidebar.

### 📊 Data Visualization & Management
*   **Dynamic Charts:** Integrated **Chart.js** to render interactive graphs for monthly breakdown of expenses vs. revenues.
*   **CRUD Operations:**
    *   Add new expenses or revenues with specific categories, locations, and descriptions.
    *   Delete records by ID.
    *   "Soft" SPA navigation: switching tabs updates data without reloading the page.
*   **Detailed View:** Filter transactions by custom date ranges and specific categories.

### 🔐 Security & Backend
*   **User Authentication:** Secure Login and Signup system.
*   **Password Hashing:** Uses PHP `password_hash()` (bcrypt) for security.
*   **Account Management:** Option to permanently delete the user account and all associated data.
*   **Database Normalization:** Structured Relational Database (SQL) to minimize redundancy (separate tables for Categories, Locations, Users).

---

## 🛠️ Tech Stack

### Frontend
*   **HTML5 / CSS3**
*   **TailwindCSS** (Utility-first CSS framework)
*   **DaisyUI** (Component library for Tailwind)
*   **JavaScript (Vanilla)**
*   **AJAX (XMLHttpRequest)** - For asynchronous data fetching and updates.
*   **Chart.js** - For data visualization.

### Backend
*   **PHP 8.x** - Server-side logic.
*   **MySQL / MariaDB** - Relational database management.

---

## 📂 File Structure

```text
/project-root
├── index.php              # Main Dashboard (SPA structure)
├── access.php             # Login/Signup Landing page
├── CSS/
│   └── full.min.css       # DaisyUI/Tailwind compiled styles
├── JS/
│   ├── chartjs.min.js     # Charting library
│   └── tailwind3.4.1.js        # Tailwind configuration
├── PHP/
│   ├── connect.php        # Database connection configuration
│   ├── homepage.php       # API: Fetches dashboard data
│   ├── addexpense.php     # API: Handles expense insertion
│   ├── addrevenue.php     # API: Handles revenue insertion
│   ├── statisticspage.php # API: Fetches data for charts
│   └── ... (other CRUD handlers)
└── expensesdb.sql         # Database structure and seed data
```

---

## 🚀 Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/luca-workspace/expensesmanager
    ```

2.  **Database Setup:**
    *   Open your database management tool (e.g., phpMyAdmin, Workbench).
    *   Create a new database named `expensesdb`.
    *   Import the `expensesdb.sql` file provided in the root directory.
    *   *Note: The dump includes sample categories and locations.*

3.  **Configure Connection:**
    *   Open `PHP/connect.php`.
    *   Update the database credentials if necessary:
        ```php
        $servername = "localhost";
        $username = "root"; // your username
        $password = ""; // your password
        $dbname = "expensesdb";
        ```

4.  **Run the Application:**
    *   Place the project folder in your server's root directory (e.g., `htdocs` for XAMPP).
    *   Navigate to `http://localhost/expensesmanager/access.php`.
    *   Create a new account or log in.

## 📸 Database Schema Overview

The database is designed with data integrity in mind, utilizing Foreign Keys for all relational data.

*   **`users`**: Stores user credentials (hashed passwords).
*   **`expenses` / `revenues`**: Main transaction tables linked to users, categories, and locations.
*   **`expensescategories` / `locations`**: Lookup tables to ensure standardized data entry and allow for specific color-coding in charts.

## 🔮 Roadmap & Refactoring (To-Do)

I am currently working on refining the codebase to transition from an academic project to a production-ready application.

*   **🔐 Authentication UX:**
    *   [ ] **Relax Password Policy:** The current regex validation is intentionally rigid to meet specific assignment criteria. Planned refactoring to adopt more user-friendly standards (e.g., NIST guidelines) while maintaining security.

*   **📱 Responsive Design Polishing:**
    *   [ ] **Table Breakpoints:** Fine-tune the CSS media queries (DaisyUI/Tailwind breakpoints) for the main expense table. Currently, the logic to hide/show `Description` and `Location` columns needs adjustment to prevent layout shifts on intermediate screen widths.

*   **🎨 UI/UX Overhaul:**
    *   [ ] **Year Selector Redesign:** The current "incremental button" interface (`-10`, `+10`, etc.) was implemented as a logic challenge. I plan to replace this with a standard **Dropdown** or **Datepicker** component to improve usability and adhere to modern design patterns.

### 📝 License
This project is for educational purposes and portfolio demonstration.

[🔗 View Live Demo](https://expensesmanager.infinityfree.me/)