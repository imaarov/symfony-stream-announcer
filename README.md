# Livestream Announcement Symfony Web App

This Symfony web application helps you announce your broadcast status across multiple platforms.

## Features

- **Multi-platform Integration:** Easily integrate with various broadcasting platforms to automatically announce your status.
- **Customization:** Customize the announcement messages and timing according to your preferences.
- **Real-time Updates:** Provides real-time updates for your audience across different platforms.

## Installation

To get started with this Symfony web app, follow these steps:

1. Clone the repository:

```bash
git clone https://github.com/yourusername/broadcast-status-symfony.git
```
2. Navigate to the project directory:
```
cd broadcast-status-symfony
```

3. Install dependencies:
```
composer install
```
4. Configure your environment variables by copying the .env.sample file:
```
cp .env.sample .env
```

5. Customize your environment variables in the .env file according to your setup.

6. Set up your database and run migrations:
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
7. Start the Symfony server:
```
symfony server:start
```

8. Access the application in your browser at http://localhost:8000.

## Usage:
1. Sign up for an account on the application.
2. Connect your broadcasting platforms (e.g., Twitch, YouTube, etc.)(rn just twitch is working).
3. Customize your announcement messages and timing preferences.
4. Start broadcasting, and the application will automatically announce your status.
