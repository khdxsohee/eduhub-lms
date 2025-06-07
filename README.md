# EduHub LMS
## Modern Learning Management System
EduHub LMS is a robust and user-friendly Learning Management System designed to facilitate seamless online education. Built with a focus on ease of use for both instructors and students, EduHub LMS provides a comprehensive platform for creating, managing, and delivering educational content. Whether you're an independent educator, a small institution, or a large academy, EduHub LMS offers the tools you need to effectively share knowledge and empower learners.

## Features
EduHub LMS comes packed with features to enhance the online learning experience:

## For Instructors
- Course Creation & Management: Easily create new courses with detailed descriptions, pricing, categories, and captivating thumbnail images.
- Lesson Organization: Structure your courses with individual lessons, including video content, text, and other resources.
- Content Richness: Add descriptions, video URLs (e.g., YouTube embeds), and rich text content to your lessons.
- Course Status Control: Manage course visibility with draft, published, and archived statuses.
- Instructor Dashboard: A dedicated area for instructors to manage their courses, monitor enrollments, and track student progress.
- Secure Authentication: Robust user authentication system to protect your content and user data.
## For Students
- Course Discovery: Browse and discover courses across various categories.
- Easy Enrollment: Simple and straightforward enrollment process for desired courses.
- Progress Tracking: Keep track of your learning journey with enrollment status (in progress, completed).
- Interactive Learning: Access lessons with video and textual content.
- User Dashboard: A personalized dashboard to view enrolled courses and continue learning.
- Secure Authentication: Safe and secure login for accessing your learning materials.
## Core System Features
- Role-Based Access Control: Differentiate between students, instructors, and administrators with distinct permissions.
- Database Management: Organized and efficient storage of all course, user, and enrollment data using SQL.
- MVC Architecture: A clean and maintainable codebase structured using the Model-View-Controller pattern.
- Secure Password Hashing: User passwords are securely hashed using password_hash() for enhanced security.
- Technologies Used
- EduHub LMS is built using reliable and widely adopted web technologies:
## Languages
- PHP: The core server-side scripting language, powering the application logic.
- MySQL: A powerful relational database management system for data storage.
- HTML5: For structuring the web content.
- CSS3: For styling and visual presentation.
- JavaScript: For interactive elements and dynamic content (can be extended with frameworks like jQuery if needed).
- Composer: PHP dependency manager (if external libraries are used).
## Getting Started
Follow these steps to set up and run EduHub LMS on your local machine.

### Prerequisites
Before you begin, ensure you have the following installed:

- Web Server: Apache or Nginx (XAMPP or WAMP are recommended for an all-in-one solution on Windows).
- PHP: Version 7.4 or higher.
- MySQL: Version 5.7 or higher.
- Composer (Optional, but recommended if you plan to add PHP dependencies).
- Installation Steps
Clone the Repository:

```
git clone https://github.com/khdxsohee/eduhub-lms.git
```

```
cd eduhub-lms
```



## Configure Database:

- Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or command line).
- Import the database.sql file located in the project root. This will create the eduhub_db database and all necessary tables.
- Important: The database.sql file contains a placeholder for the admin user's password. You must generate a hashed password using PHP and replace 'YOUR_GENERATED_HASH_HERE' in the - - - INSERT statement within database.sql.
- Create a temporary PHP file (e.g., hash_gen.php):

```
<?php
echo password_hash('password', PASSWORD_DEFAULT); // Change 'password' to your desired admin password
?>
```


- Run this file in your browser or via the PHP CLI (php hash_gen.php).
- Copy the output (e.g., $2y$10$....................) and paste it into the database.sql file for the admin user.
- Configure Application Settings:

- Navigate to app/config/config.php.

Update the database credentials and BASE_URL to match your local setup:

```
<?php
// DB Params
define('DB_HOST', 'localhost'); // Your database host
define('DB_USER', 'root');     // Your database username
define('DB_PASS', '');         // Your database password (empty for XAMPP/WAMP default)
define('DB_NAME', 'eduhub_db');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root (e.g., 'http://localhost/eduhub-lms')
define('BASE_URL', 'http://localhost/eduhub-lms'); // IMPORTANT: Adjust this to your project's URL

// Site Name
define('SITE_NAME', 'EduHub LMS');

// App Version
define('APP_VERSION', '1.0.0');
Ensure BASE_URL is correctly set to the URL where you access your project (e.g., http://localhost/eduhub-lms if you placed the project in your web server's htdocs or www directory under eduhub-lms).

Set Up Virtual Host (Recommended)
For a cleaner URL structure (e.g., http://eduhublms.local instead of http://localhost/eduhub-lms), you can set up a virtual host.

Apache (httpd-vhosts.conf or similar):
Apache
```

```
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/eduhub-lms/public" # Adjust path for your system
    ServerName eduhublms.local
    <Directory "C:/xampp/htdocs/eduhub-lms/public"> # Adjust path
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```


```
Hosts File (C:\Windows\System32\drivers\etc\hosts on Windows, /etc/hosts on Linux/macOS):
127.0.0.1   eduhublms.local
```


After making changes, restart your Apache server. Update BASE_URL in config.php to http://eduhublms.local.
### Access the Application:
Open your web browser and navigate to the BASE_URL you configured (e.g., http://localhost/eduhub-lms or http://eduhublms.local).

### Usage
  ### Admin Access
- Email: admin@example.com
- Password: The password you chose and hashed in database.sql (default: password).
- As an admin, you can manage users, courses, and other system settings.

### Instructor Access
You can create a new instructor account via the registration page, or change an existing user's role to 'instructor' directly in the database.
Instructors can create and manage their courses and lessons.
### Student Access
Students can register for an account and enroll in available courses.
## Project Structure
eduhub-lms/
- ├── app/
- │   ├── bootstrap.php             # Core application setup
- │   ├── config/
- │   │   └── config.php            # Database and application configurations
- │   ├── controllers/              # Handles application logic and user input
- │   │   ├── Pages.php
- │   │   ├── AuthController.php
- │   │   └── InstructorController.php
- │   │   └── StudentController.php
- │   │   └── AdminController.php (if implemented)
- │   ├── helpers/
- │   │   └── SessionHelper.php     # Session management utilities
- │   │   └── UrlHelper.php         # URL redirection helper
- │   ├── libraries/                # Core framework components
- │   │   ├── Core.php              # Handles URL parsing and controller loading
- │   │   ├── Controller.php        # Base controller class
- │   │   ├── Database.php          # Database abstraction layer
- │   │   └── (Other custom libraries)
- │   ├── models/                   # Interacts with the database
- │   │   ├── User.php
- │   │   ├── Course.php
- │   │   ├── Category.php
- │   │   ├── Lesson.php
- │   │   └── Enrollment.php
- │   │   └── (Other models)
- │   └── views/                    # Presentation layer (HTML, PHP templates)
- │       ├── layouts/              # Common headers/footers
- │       │   ├── header.php
- │       │   └── footer.php
- │       ├── pages/                # Public facing pages
- │       │   ├── index.php
- │       │   └── about.php
- │       ├── auth/                 # Authentication views
- │       │   ├── register.php
- │       │   └── login.php
- │       ├── instructor/           # Instructor-specific views
- │       │   ├── dashboard.php
- │       │   ├── mycourses.php
- │       │   ├── add_course.php
- │       │   ├── edit_course.php
- │       │   ├── manage_lessons.php
- │       │   ├── add_lesson.php
- │       │   └── edit_lesson.php
- │       └── student/              # Student-specific views
- │           ├── dashboard.php
- │           ├── enrolled_courses.php
- │           ├── course_details.php
- │           └── lesson_view.php
- ├── public/                       # Web server root directory
- │   ├── .htaccess                 # URL rewriting rules
- │   ├── css/
- │   │   └── style.css
- │   ├── js/
- │   │   └── main.js
- │   ├── storage/                  # User uploaded files (e.g., course images)
- │   │   └── uploads/
- │   │       └── courses/
- │   └── index.php                 # Front controller
- ├── database.sql                  # Database schema and initial data
- └── README.md                     # This file


### Contributing
We welcome contributions to the EduHub LMS project! If you'd like to contribute, please follow these steps:


```
Fork the repository.
Create a new branch for your feature or bug fix: git checkout -b feature/your-feature-name.
Make your changes and ensure tests (if any) pass.
Commit your changes: git commit -m 'Add new feature'.
Push to your fork: git push origin feature/your-feature-name.
Open a Pull Request on the main repository.
```


### License
This project is open-source and available under the MIT License.

### Support
If you encounter any issues or have questions, please open an issue on the GitHub Issues page.

Made with ❤️ by khdxsohee
