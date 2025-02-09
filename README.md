Overview
--------

This project implements a simple task management system with user authentication. The frontend is built using Vue.js and styled with TailwindCSS. The application provides features for logging in and managing tasks (CRUD operations) where users can create, read, update, and delete tasks. Authentication is handled via JWT tokens.

![screencapture-localhost-8080-2025-02-09-17_01_23](https://github.com/user-attachments/assets/c51de05d-3aa9-4827-827a-63403e0e8a2f)

Features
--------

*   **User Login**: Users can log in using their email and password.
    
*   **Task Management**: Users can create, read, update, and delete tasks.
    
*   **UI**: The application interface is styled using TailwindCSS for a modern, responsive design.
    

Technologies
------------

*   **Frontend**: Vue.js, TailwindCSS
    
*   **Backend**: Laravel (API for authentication and task management)
    
*   **Authentication**: JWT (JSON Web Tokens)
    

Installation
------------

### Prerequisites

Before running the application, ensure you have the following installed:

*   Docker (For containerized setup)
    
*   Docker Compose (For managing multi-container Docker applications)
    
*   Node.js (LTS version recommended)
    
*   NPM (Node Package Manager)
    
*   Laravel (Backend API)
    

### Docker Compose Setup

You can use Docker Compose to easily set up the entire application, including both the frontend and backend. The Docker Compose file will configure the containers for the frontend, backend, and database.

1.  git clone cd
    
2.  cp .env.example .env
    
3.  Set up the .env file for both backend and frontend with necessary credentials, API URLs, etc.
    
4.  docker-compose up --build
    
5.  Once the containers are running, navigate to the frontend in your browser:
    
    *   Frontend: http://localhost:3000
        
    *   Backend: http://localhost:8000
        

Docker will handle setting up the database, Laravel API, and frontend Vue.js application for you.

### Backend Setup (Manual Option)

If you prefer to run the backend manually without Docker:

1.  git clone
    
2.  cd backendcomposer install
    
3.  cp .env.example .envphp artisan 

4.  key:generatephp artisan migrate
    
5.  php artisan serve
    

### Frontend Setup

If you're running the frontend manually (without Docker):

1.  git clone
    
2.  cd frontendnpm install
    
3.  npm run dev
    

The frontend will now be available at http://localhost:3000.

## with docker

- Docker
- Docker Compose

## Getting Started

To run the application using Docker, follow these steps:

### 1. Clone the Repository

Clone the repository to your local machine:


git clone <repository-url>
cd <repository-folder>

### 2\. Set Up the .env File

Ensure the environment variables are set up for the backend (Laravel)

`cp .env.example .env   `

Make sure to update the .env file for the correct database, mail, and JWT settings for the backend.

### 3\. Docker Compose Setup

The project uses Docker Compose to manage the services (backend, frontend, and database). Ensure that you have a docker-compose.yml file in the root of the project.

### 4\. Build and Run with Docker Compose

To set up the containers and start the services, follow these steps:

#### Build the containers

Run the following command to build the services:

`docker-compose build  `

#### Start the containers

Start the containers and services with:

`docker-compose up -d `

This will run the backend, frontend, and database services in the background.

#### Access the Application

*   **Frontend:** http://localhost:3000
    
*   **Backend API:** http://localhost:8000
    

#### Run Database Migrations

To run the database migrations for the backend, run the following command:

`docker-compose exec backend php artisan migrate   `

### 5\. Stopping the Containers

To stop and remove the containers, use the following command:
`docker-compose down `

This will stop all the containers and remove them. To stop the containers without removing them, use:
`docker-compose stop`
