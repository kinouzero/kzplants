# kzPlants

kzPlants is a project aiming to provide a tracking tool for cannabis cultivation, thus facilitating the management and monitoring of your plants.

This repository contains a web application for tracking cannabis plantations.
The application allows users to keep track of their different cannabis crops by recording information such as variety name, planting date, growth characteristics, fertilizer doses used, etc.
It allows managing a to-do list for each of your plants to know your progress and how much time is left.

## Features

- Plantation tracking: Record and track the different varieties of cannabis you grow.
- Dashboards: Organize your plants
- Data management: Store detailed information about each variety, including planting dates, growth rates, treatments, etc.
- History: Display complete history for each plant.
- Photos: Add photos to your plants to track their growth.
- Analysis: Get different analyses on your plantations, currently: Status and watering, more to come.

## Installation

To install and use this project, follow these steps:

1. Clone this repository to your local machine using the following command:
   ```bash
   git clone https://github.com/kinouzero/kzPlants.git
2. Navigate to the project directory:
    ```bash
    cd kzPlants
3. Install PHP dependencies using Composer:
    ```bash
    composer install
4. Install javascript dependencies using npm :
    ```bash
    npm install
    ln -s ../node_modules public/npm
5. Copy the `.env.example` file and rename it to `.env`:
    ```bash
    cp .env.example .env
6. Generate a Laravel application key:
    ```bash
    php artisan key:generate
7. Configure your database in the `.env` file.
8. Run migrations to create database tables:
    ```bash
    php artisan migrate
9. Start the development server:
    ```bash
    php artisan serve
10. Access the application in your browser at `http://localhost:8000`.

## Docker

You can also run this project using Docker. Make sure you have Docker installed on your machine.

1. Clone this repository to your local machine using the following command:
   ```bash
   git clone https://github.com/kinouzero/kzPlants.git
2. Navigate to the project directory:
    ```bash
    cd kzPlants
3. Create a `.env` file and configure your database as mentioned earlier.
4. Run the Docker Compose command to create and start the containers:
    ```bash
    docker-compose up -d
5. Access the application in your browser at `http://localhost`.

## How to Contribute

If you wish to contribute to this project, you can follow these steps:

1. **Fork** this project and **clone** your fork locally.
2. Create a new branch for your feature (`git checkout -b feature/your-feature-name`).
3. Make your changes and commit them (`git commit -am \'Add a new feature: FeatureName\'`).
4. Push your changes to GitHub (`git push origin feature/your-feature-name`).
5. Submit a **pull request**.

Please make sure to adhere to the code of conduct and contribution guidelines when participating.

## License

This project is licensed under the [MIT License](LICENSE).

## Contact

For any questions or suggestions, you can contact us at the following address: [zero@kinou.xyz](mailto:zero@kinou.xyz).

**Note:** This project is still in development! We appreciate any contributions from the community to make it better!