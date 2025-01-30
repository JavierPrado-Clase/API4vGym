# API4vGym

This API manages activities, monitors, and activity types for a gym. It allows retrieving, creating, updating, and deleting activities and their associated monitors.

## Table of Contents

- [Installation](#installation)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/gym-api.git
   cd gym-api
   ```
2. Install Composer:
   ```bash
   composer require symfony/orm-pack
   composer require --dev symfony/maker-bundle
   ```
3. Configure .env file (DATABASE_URL)

4. Create a DDBB:
   ```bash
   php bin/console doctrine:database:create
   ```
5. Migrate entities:
   ```bash
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```

## Running it

   ```bash
   symfony server:start
   ```
