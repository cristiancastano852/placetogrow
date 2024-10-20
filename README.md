<p align="center"><a href="https://soluciones.evertecinc.com/escuela-de-desarrolladores-php-evertec" target="_blank"><img src="https://soluciones.evertecinc.com/hs-fs/hubfs/logoEvertec.png?width=250&height=50&name=logoEvertec.png" width="400" alt="Laravel Logo"></a></p>

<p align="center"></p>
</p>

## Microsites | PlaceToGrow
Author:
  Cristian Alexander Castaño Montoya
  
  [<img src="https://img.shields.io/badge/LinkedIn-Connect-blue?style=flat&logo=linkedin">](https://www.linkedin.com/in/cristiancastano852/)

### Technology Stack

- **Separación por Capas**: Added layers beyond MVC, using [single action classes](https://medium.com/@remi_collin/keeping-your-laravel-applications-dry-with-single-action-classes-6a950ec54d1d).
- **Language**: PHP Version 8.2.12
- **Backend Framework**: Laravel V11.9.
- **Database**: MySQL V8.0.37.
- **Frontend Framework**: Inertia.js con Vue.js 3.
- **NodeJs**: node V20.14.1 and npm V10.7.0
- **Styles/Libraries**: Spartan and Tailwind CSS.
- **Charts**: Chart.js
- **Containerization**: Docker V26.1.1 and Docker Compose.
- **Version Control**: Git and GitFlow.
- **Continuous Integration**: GitHub Actions.
- **Role and Permission Managemen**: Spatie
- **Code Quality**: Use of PSR standards for code formatting and static analysis tools (Sonar Cloud)
- **Typing**: Strict typing in function and method declarations
- **Cache**: Implemented caching for performance improvement
- **Log Management**: Configured to log and manage application logs
- **Composer** V2.7.6

### Entity-Relationship Diagram
![DIAGRAM ER MICROSITES EVERTEC - Diagrama ER de base de datos (pata de gallo)](https://github.com/cristiancastano852/placetogrow/assets/44209773/dac31313-51cf-4834-b008-58e380f58f08)

---
## Local Installation
1. **Clone the Repository:**
   
    Clone this repository to your local machine.

   ```bash
   git clone https://github.com/cristiancastano852/placetogrow
   cd placetogrow
   ```
2. **Copy .env**
    ```bash
   cp .env.example .env
   ```
3. **Install Dependencies**

    To install the necessary dependencies for the project, run the following commands:
    #### PHP Dependencies
    ```bash
    composer install
    ```
    #### Node.js Dependencies
    ```bash
    npm install
    ```

4. **Generate API Key**

   To generate an API key, run the following command:
   ```bash
    php artisan key:generate
   ```
5. **Link Storage Directory**

   To create a symbolic link from `public/storage` to `storage/app/public`, run the following command:
    ```bash
    php artisan storage:link
    ```
6. **Environment Configuration**
 
  Before running the application, make sure to configure the following environment variables in your `.env` file.
   - Steps in: [Required Variables](#configurate-environment-variables).
7. **Run Migrations and Seed the Database**

    To run the database migrations and seed the database with initial data, use the following command:
    ```bash
    php artisan migrate --seed
    ```

8. **Run the Application**

    To serve the Laravel application and start the development environment for Vue.js, follow these steps:
   
    **8.1** Use the following command to start the Laravel development server:
   ```bash
   php artisan serve
    ```

   **8.2** Build the Frontend for Production:
       If you want to compile the frontend assets for production, run:
   ```bash
   npm run build
    ```
   **8.3** Start the Frontend Development Server:
       To start the Vue.js development server with hot-reloading, use:
    ```bash
    npm run dev
    ```
---

## Docker Installation

Follow these steps to set up and run your Laravel application in Docker.

### Installation Steps

1. **Clone the Repository:**
   
   Clone this repository to your local machine.

   ```bash
   git clone https://github.com/cristiancastano852/placetogrow
   cd placetogrow
   ```

2. **Build and Start the Containers:**
   
   From the project's root folder, run:

   ```bash
   docker-compose up -d --build
   ```

3. **Install Composer Dependencies:**
   
   Once the containers are running, install the Composer dependencies:

   ```bash
   docker-compose exec app composer install
   ```

4. **Install Node.js Dependencies:**
   

   ```bash
   docker-compose exec app npm install
   ```
5. **Build Node.js:**

   ```bash
   docker-compose exec app npm run build 
   ```

6. **Run Migrations and Seeders:**
   
   Execute the migrations and seeders to set up the database:

   ```bash
   docker-compose exec app php artisan migrate:refresh --seed
   ```
7. **Create Environment for Jobs:**
   
   Run the command to execute jobs:
   ```bash
   docker-compose exec app php artisan queue:work
   ```
8. **Create Environment for Scheduled Tasks:**
   
  Run the command to execute scheduled tasks:

   ```bash
   docker-compose exec app php artisan schedule:work
   ```
9. **Access the Application**

   Open your web browser and go to `http://localhost:9005/` (or the port you configured).
   - **Verificar el estado de los contenedores:**
   
   Puedes verificar el estado de los contenedores en cualquier momento usando:

   ```bash
   docker-compose ps
   ```
### Configurate Environment Variables

   Ensure that the environment variables in your .env file are correctly set. Here’s an example of the database configuration:

- Database Enviroments
   ```env
    DB_CONNECTION=mysql # Or your database driver
    DB_HOST=127.0.0.1   # Database host
    DB_PORT=3306        # Database port
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
   ```
- Email Configuration
  ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io # Example service, replace with your actual email host
    MAIL_PORT=2525
    MAIL_USERNAME=your_mail_username
    MAIL_PASSWORD=your_mail_password
    MAIL_FROM_ADDRESS="noreply@yourapp.com"
    MAIL_FROM_NAME="${APP_NAME}"
   ```
- Payment Gateway (PlaceToPay) Configuration
  ```env
    PLACETOPAY_LOGIN=your_placetopay_login
    PLACETOPAY_SECRET_KEY=your_placetopay_secret_key
    PLACETOPAY_URL=https://placetopay.com/re
   ```
- Alert Configuration
  ```env
    INVOICE_DUE_ALERT_DAYS=  # Number of days before due alert is triggered
    SUBSCRIPTION_EXPIRY_ALERT_DAYS=  # Number of days before subscription expiry alert is triggered
    SUBSCRIPTION_NEXT_BILLING_ALERT_DAYS=  # Number of days before next billing (collect) alert is triggered
   ```

### User data to test

  The following test users have been created to facilitate the exploration of the application's functionality. You can use these credentials to log in and test the system with different roles and permissions

- **Super Admin**: Has full access to all functionalities, including user management, microsites, roles/permissions, etc.
   ```env
    Username: superadmin@microsites.com
    Password: password
   ```
- **Customer**: Can manage microsites they own, access payments, subscriptions, invoices import and metrics.
  ```env
    Username: customeradmin@microsites.com
    Password: password
   ```
- **Guest**: Has limited access to viewing public microsites and minimal actions.
  ```env
    Username: customeradmin@microsites.com
    Password: password
   ```



