Taskforce-PRO-Edition-Programming-Challenge

## Installation Guide

### Prerequisites
- PHP >= 8.3.6
- Composer
- MySQL
- Node.js & npm

### Step 1: Clone the repository
```bash
git clone https://github.com/yobuir/Taskforce-PRO-Edition-Programming-Challenge.git
cd Taskforce-PRO-Edition-Programming-Challenge
```

### Step 2: Install dependencies
```bash
composer install
npm install
```

### Step 3: Set up environment variables
Copy the `.env.example` file to `.env` and configure your environment variables, especially the database settings .
```bash
cp .env.example .env
```

### Step 4: Generate application key
```bash
php artisan key:generate
```

### Step 5: Run migrations
```bash
php artisan migrate
```

### Step 6: Seed the database (optional)
```bash
php artisan db:seed
```

### Step 8: Compile assets
```bash
npm run build
```

### Step 7: Start the development server
```bash
composer run dev
```

### Access the application
Open your browser and go to `http://localhost:${port}`

### Deployment
This application is deployed on [https://task-force-coa-red-wind-5767.fly.dev](https://task-force-coa-red-wind-5767.fly.dev).

In case  the first provided link get expired due to trial limit use  this one 

[https://task-force-coa.global-oral-health.org](https://task-force-coa.global-oral-health.org) 


### System authentication
##### Testing user

*User Email*

```bash
 yobu@yopmail.com
 ```

*User Password*

```bash
password
```

### Email Testing
I have created temporary email to receive system email notification.
It can be accessed via this url [https://yopmail.com/en](https://yopmail.com/en)
And enter following email.

```bash
 yobu@yopmail.com
 ```
