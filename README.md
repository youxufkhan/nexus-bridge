# NexusBridge | Enterprise Commerce Orchestration

NexusBridge is a high-end, multi-tenant SaaS platform designed for modern agencies to manage diverse commerce portfolios with precision. Built on Laravel and styled with a premium "Midnight" aesthetic, it bridges the gap between enterprise data and actionable marketplace insights.

## 🚀 Key Features

-   **Multi-Tenancy Architecture**: Strict data isolation at the Agency and Portfolio (Client) levels using a shared-schema model.
-   **Commerce Ingestion Engine**: Integrated bridges for Walmart, Amazon Vendor Central, and TikTok Shop.
-   **Premium SaaS UI**: Fully responsive, high-end design featuring localized "Midnight" (Dark) and "Daylight" (Light) modes with `localStorage` persistence.
-   **Enterprise Catalog**: Centralized SKU management with platform-to-node mapping and real-time inventory visibility.
-   **Granular RBAC**: Role-based access control for Superadmins, Agency Admins, and Agents.

## 🛠 Tech Stack

-   **Backend**: Laravel 11.x (PHP 8.2+)
-   **Frontend**: Tailwind CSS (JIT), Vanilla JS, Outfit Typography
-   **Database**: PostgreSQL / MySQL
-   **Icons**: Custom SVG set with Glassmorphism effects

## 📖 Documentation

Detailed technical and operational documentation is available in the `/docs` directory:
-   [Technical Architecture](docs/technical_architecture.md)
-   [User Wiki & Guide](docs/wiki/user_guide.md)
-   [Project Changelog](docs/changelog.md)

## 🚦 Getting Started

1.  **Clone & Install**:
    ```bash
    git clone git@github.com:youxufkhan/nexus-bridge.git
    composer install
    npm install
    ```
2.  **Environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3.  **Database & Demo Data**:
    ```bash
    php artisan migrate --seed
    ```
4.  **Launch**:
    ```bash
    php artisan serve
    ```

---
*Developed for elite agency orchestration.*
