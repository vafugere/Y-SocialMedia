# Y - Social Media

**Y** is a lightweight social media web application built with PHP and MySQL. It allows users to share posts, follow others, and interact with content.

## Features

- Create an account
- Login/logout
- Post
- Like posts
- Repost others
- Reply to posts
- Follow / unfollow users
- View profiles, followers, and following
- Update profile picture, display name, email, and password
- Basic search for users and tweets

## 🛠️ Setup Instructions

1. **Clone or download the project**
2. **Run with XAMPP (Apache + MySQL)**
3. **Import a database:**
   - `y_db_blank.sql`: creates an empty database structure
   - `y_sample_data.sql`: includes sample users and tweets

4. **Database login info** (update if needed in `connect.php`):
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $dbname = 'y';

5. **Sample data**
    - Username = any user's first name
    - Password = admin
    Example login:
    - emma
    - admin