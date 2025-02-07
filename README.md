# Childcare Manager

The Childcare Manager software is designed to assist parents, couples, and caretakers in managing childcare tasks and tracking important developmental milestones of children.

## Table of Contents

- [Features](#features)
- [Demo](#demo)
- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)

## Features

- **Feeding Schedule Management**: Record and manage feeding schedules for children.
- **Sleep Schedule Management**: Record and manage sleep schedules for children.
- **Multimedia Resource Management**: Track developmental milestones through photos, videos, and audio recordings.
- **Medical History Recording**: Maintain medical history and emergency contact information.
- **Relationship Tracking**: Track relationships with other children and family members.
- **Chronological View**: View important moments in a timeline format.
- **Social Sharing**: Share significant moments via social applications or RSS news feeds.
- **REST API**: Expose system functionalities through a web API.

## Demo

https://github.com/user-attachments/assets/078ea2f2-2796-48f4-a96c-ccd0c40a36d7

## Installation

1. Install XAMPP.
2. Clone the repository to your XAMPP htdocs folder.
3. Start Apache and MySQL from XAMPP Control Panel.
4. Update the .htaccess file with the following configuration:
    ```apache
    RewriteEngine On

    # Serve existing files and directories directly
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]

    # Rewrite requests to map to the WEB directory
    RewriteRule ^(css|app|config|public|media)/(.*)$ WEB/$1/$2 [L]

    # Route all other requests to public/index.php
    RewriteRule ^(.*)$ WEB/public/index.php [L]
    ```

## Usage

1. Open your web browser and navigate to `http://localhost/`.
2. Register a new account or log in with your existing account.
3. Use the navigation menu to access different features such as feeding schedule, sleep schedule, and timeline.

## API Endpoints

- **GET /api/media/{id}**: Fetch media by ID.
- **GET /api/relationship**: Fetch relationship information.
- **POST /api/media**: Upload new media.
- **DELETE /api/media/{id}**: Delete media by ID.
- **...** : And more endpoints for various functionalities.
