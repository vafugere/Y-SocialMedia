<?php
class User {
    private $userId;
    private $firstName;
    private $lastName;
    private $displayName;
    private $username;
    private $password;
    private $email;
    private $date;
    private $profilePic;

    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function __construct($userId, $firstName, $lastName, $displayName, $username, $password, $email, $date, $profilePic) {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->displayName = $displayName;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->date = $date;
        $this->profilePic = $profilePic;
    }
    public static function createUser($con, $user) {
        $secure_password = password_hash($user->password, PASSWORD_DEFAULT);
        $defaultPic = 'default_picture.jpg';
        $displayName = $user->firstName . ' ' . $user->lastName;
        $userId = null;
        $stmt = $con->prepare('INSERT INTO `users` (`first_name`, `last_name`, `display_name`, `username`, `password`, `email`, `profile_pic`)
            VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('sssssss', $user->firstName, $user->lastName, $displayName, $user->username, $secure_password, $user->email, $defaultPic);
        if ($stmt->execute()) {
            $userId = $con->insert_id;
        }
        $stmt->close();
        if ($userId) {
            $_SESSION['userId'] = $userId;
            header('Location: ../index.php');
            exit;
        } else {
            $msg = 'An unexpected error has occured, please try again';
            header('Location: ../signup.php?message=' . urlencode($msg));
            exit;
        }
    }
    public static function getLoginCredentials($con, $username) {
        $stmt = $con->prepare('SELECT user_id, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($userId, $password);
        if ($stmt->fetch()) {
            $user = new User($userId, null, null, null, null, $password, null, null, null);
        } else {
            $user = null;
        }
        $stmt->close();
        return $user;
    }
    public static function loginUser($con, $username, $password) {
        $user = User::getLoginCredentials($con, $username);
        if ($user) {
            if (password_verify($password, $user->password)) {
                $_SESSION['userId'] = $user->userId;
                header('Location: ../index.php');
                exit;
            } else {
                $msg = 'Incorrect password, please try again';
                header('location: ../login.php?message=' . urlencode($msg));
                exit;
            }
        } else {
            $msg = 'Username does not exist, please try again';
            header('Location: ../login.php?message=' . urlencode($msg));
            exit;
        }
    }
    public static function availableUsername($con, $username) {
        $response = false;
        $stmt = $con->prepare('SELECT user_id FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $response = true;
        }
        $stmt->close();
        return $response;
    }
    public static function availableEmail($con, $email) {
        $response = false;
        $stmt = $con->prepare('SELECT user_id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $response = true;
        }
        $stmt->close();
        return $response;
    }
    public static function getUserById($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, display_name, username, email, date_created, profile_pic FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $display_name, $username, $email, $date, $profilePic);
        if ($stmt->fetch()) {
            $user = new User($user_id, $fname, $lname, $display_name, $username, null, $email, $date, $profilePic);
        } else {
            $user = null;
            $msg = 'An unexpected error has occured, please sign in again';
            header('Location: login.php?message=' . urlencode($msg));
            exit;
        }
        $stmt->close();
        return $user;
    }
    public static function countPosts($con, $userId) {
        $stmt = $con->prepare('SELECT COUNT(*) FROM posts WHERE user_id = ? AND reply_to_post_id = 0');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
    public static function countFollowing($con, $userId) {
        $stmt = $con->prepare('SELECT COUNT(*) FROM follows WHERE from_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
    public static function countFollowers($con, $userId) {
        $stmt = $con->prepare('SELECT COUNT(*) FROM follows WHERE to_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
    public static function isFollowing($con, $fromId, $toId) {
        $stmt = $con->prepare('SELECT follow_id FROM follows WHERE from_id = ? AND to_id = ?');
        $stmt->bind_param('ii', $fromId, $toId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isFollowing = $result->num_rows > 0;
        $stmt->close();
        return $isFollowing;
    }
    public static function toggleFollow($con, $fromId, $toId) {
        $isFollowing = User::isFollowing($con, $fromId, $toId);
        if ($isFollowing) {
            $stmt = $con->prepare('DELETE FROM follows WHERE from_id = ? AND to_id = ?');
            $stmt->bind_param('ii', $fromId, $toId);
            if (!$stmt->execute()) return false;
            $stmt->close();
        } else {
            $stmt = $con->prepare('INSERT INTO `follows` (`from_id`, `to_id`) VALUES (?,?)');
            $stmt->bind_param('ii', $fromId, $toId);
            if (!$stmt->execute()) return false;
            $stmt->close();
        }
        return true;
    }
    public static function displayTextUser($con, $user) {
        $loggedUser = $_SESSION['userId'];
        $isFollowing = self::isFollowing($con, $loggedUser, $user->userId);
        $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
        echo    '
            <div class="content-padding">
                <div class="flex-row">
                    <img src="images/profilepics/' . $user->profilePic . '" class="profile-pic">
                    <div class="flex-column">
                        <a href="userpage.php?user_id=' . $user->userId . '" class="link-user">@' . $user->username . '</a>
                        <form method="POST" action="process/follow_proc.php">
                            <input type="hidden" name="user_id" value="' . $user->userId . '">
                            <button type="submit" class="link-follow">' . $followText . '</button>
                        </form>
                    </div>
                </div>
            </div>';
    }
    public static function displayUser($con, $user) {
        $loggedUser = $_SESSION['userId'];
        $isFollowing = self::isFollowing($con, $loggedUser, $user->userId);
        $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
        echo    '
            <div class="content-padding">
                <div class="flex-row">
                    <img src="images/profilepics/' . $user->profilePic . '" class="profile-pic">
                    <div class="space-between">
                        <div class="flex-column">
                            <span class="semi-bold">' . $user->displayName . '</span>
                            <a href="userpage.php?user_id=' . $user->userId . '" class="link-user">@' . $user->username . '</a>
                        </div>
                        <form method="POST" action="process/follow_proc.php">
                            <input type="hidden" name="user_id" value="' . $user->userId . '">
                            <input type="submit" value="' . $followText . '" class="btn-follow">
                        </form>
                    </div>
                </div>
            </div>';
    }
    public static function userInfo($con, $userId) {
        $user = self::getUserById($con, $userId);
        $loggedUser = $_SESSION['userId'];
        $postLink = ($loggedUser != $userId) ? "userpage.php?user_id=$userId" : 'index.php';
        $countPosts = self::countPosts($con, $userId);
        $countFollowing = self::countFollowing($con, $userId);
        $countFollowers = self::countFollowers($con, $userId);
        $date = date('F Y', strtotime($user->date));   

        echo    '
            <div class="content-padding">
                <div class="flex-row">
                    <img src="images/profilepics/' . $user->profilePic . '" class="profile-pic">
                    <div class="flex-column">
                        <div class="bold">' . $user->displayName . '</div>
                        <a href="userpage.php?user_id=' . $user->userId . '" class="link-user">@' . $user->username . '</a>
                    </div>
                </div>
                <div class="profile-info">
                    <a href="' . $postLink . '" class="info-box">
                        <span class="info-number">' . $countPosts . '</span>
                        <span class="info-label">Posts</span>
                    </a>
                    <a href="following.php?user_id=' . $user->userId . '" class="info-box">
                        <span class="info-number">' . $countFollowing . '</span>
                        <span class="info-label">Following</span>
                    </a>
                    <a href="followers.php?user_id=' . $user->userId . '" class="info-box">
                        <span class="info-number">' . $countFollowers . '</span>
                        <span class="info-label">Followers</span>
                    </a>
                </div>
                <div class="info-date">
                    <img src="images/icons/calendar.png"> Joined ' . $date . '
                </div>
            </div>';
        if ($loggedUser == $userId) {
            echo    '
                <a href="profile.php" class="nav"><img src="images/icons/edit.png">&nbsp; Edit Profile</a>
                <a href="logout.php" class="nav nav-last"><img src="images/icons/logout.png">&nbsp; Logout</a>';
        } else {
            $isFollowing = self::isFollowing($con, $loggedUser, $userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';

            echo    '
                <div class="flex-center">
                    <form method="POST" action="process/follow_proc.php">
                        <input type="hidden" name="user_id" value="' . $user->userId . '">
                        <input type="submit" value="' . $followText . '" class="btn-follow-profile">
                    </form>
                </div>';
        }
    }
    public static function editProfilePreview($con, $userId) {
        $user = self::getUserById($con, $userId);
        echo    '
            <div class="flex-row content-padding">
                <label for="profile_pic">
                    <img src="images/profilePics/' . $user->profilePic . '" id="preview" class="pic-preview">
                </label>
                <div class="flex-column">
                    <div class="space-between">
                        <input type="text" id="name" name="name" class="input-name" placeholder="' . $user->displayName . '" disabled>
                        <img src="images/icons/edit.png" id="edit_icon" class="edit-icon" alt="edit">
                    </div>
                    <span class="username">@' . $user->username . '</span>
                </div>
            </div>';
    }
    public static function emailForm($con, $userId) {
        $user = self::getUserById($con, $userId);
        echo    '
            <h2>Change Email</h2>
            <input type="text" id="email" name="email" class="input-edit" placeholder="' . $user->email . '">
            <span id="error_email"></span>';
    }
    public static function validateCurrentPassword($con, $userId, $password) {
        $res = false;
        $stmt = $con->prepare('SELECT password FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($db_password);
        if ($stmt->fetch()) {
            if (password_verify($password, $db_password)) {
                $res = true;
            }
        }
        $stmt->close();
        return $res;
    }
    public static function UpdateProfilePic($con, $userId, $picture) {
        $stmt = $con->prepare('UPDATE users SET profile_pic = ? WHERE user_id = ?');
        $stmt->bind_param('si', $picture, $userId);
        if (!$stmt->execute()) return false;
        $stmt->close();
        return true;
    }
    public static function updateDisplayName($con, $userId, $name) {
        $stmt = $con->prepare('UPDATE users SET display_name = ? WHERE user_id = ?');
        $stmt->bind_param('si', $name, $userId);
        if (!$stmt->execute()) return false;
        $stmt->close();
        return true;
    }
    public static function updateEmail($con, $userId, $email) {
        $stmt = $con->prepare('UPDATE users SET email = ? WHERE user_id = ?');
        $stmt->bind_param('si', $email, $userId);
        if (!$stmt->execute()) return false;
        $stmt->close();
        return true;
    }
    public static function updatePassword($con, $userId, $password) {
        $securePassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $stmt->bind_param('si', $securePassword, $userId);
        if (!$stmt->execute()) return false;
        $stmt->close();
        return true;
    }
    public static function suggestedUsers($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, username, profile_pic FROM users WHERE user_id != ?
            AND user_id NOT IN (SELECT to_id FROM follows WHERE from_id = ?) ORDER BY RAND() LIMIT 8');
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $username, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, null, null, null, $username, null, null, null, $profile_pic);
        }
        $stmt->close();

        if (!empty($users)) {
            $count = count($users);

            echo '<div class="label">Suggested</div>';

            foreach ($users as $i => $user) {
                self::displayTextUser($con, $user);

                if ($i < $count - 1) {
                    echo '<hr>';
                }
            }
        }
    }
    public static function friends($con, $userId) {
        $stmt = $con->prepare('SELECT u.user_id, u.username, u.profile_pic FROM users u
            INNER JOIN follows f1 ON f1.to_id = u.user_id AND f1.from_id = ?
            INNER JOIN follows f2 ON f2.from_id = u.user_id AND f2.to_id = ?
            ORDER BY first_name ASC');
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $username, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, null, null, null, $username, null, null, null, $profile_pic);
        }
        $stmt->close();
        $loggedUser = $_SESSION['userId'];

        if (!empty($users)) {
            if ($loggedUser == $userId) {
                $count = count($users);
                echo    '
                    <div class="friends-scroll">
                        <div class="label">Friends</div>';

                foreach ($users as $i => $user) {
                    self::displayTextUser($con, $user);

                    if ($i < $count - 1) {
                        echo '<hr>';
                    }
                }
                echo '</div>';
            }
        }
    }
    public static function displayFollowers($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, display_name, username, date_created, profile_pic FROM users
            WHERE user_id IN (SELECT from_id FROM follows WHERE to_id = ?)');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $display_name, $username, $date, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, null, null, $display_name, $username, null, null, null, $profile_pic);
        }

        if (!empty($users)) {
            $count = count($users);
            foreach ($users as $i => $user) {
                self::displayUser($con, $user);
                if ($i < $count -1) {
                    echo '<hr>';
                }
            }
        }
    }
    public static function displayFollowing($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, display_name, username, date_created, profile_pic FROM users
            WHERE user_id IN (SELECT to_id FROM follows WHERE from_id = ?)');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $display_name, $username, $date, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, null, null, $display_name, $username, null, null, null, $profile_pic);
        }

        if (!empty($users)) {
            $count = count($users);
            foreach ($users as $i => $user) {
                self::displayUser($con, $user);
                if ($i < $count - 1) {
                    echo '<hr>';
                }
            }
        }
    }
    public static function redirectSuccess() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public static function redirectFail() {
        $msg = 'An unexpected error has occured, please try again';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            $separator = (parse_url($referer, PHP_URL_QUERY)) ? '&' : '?';
            header('Location: ' . $referer . $separator . 'message=' . urlencode($msg));
        } else {
            header('Location: ../index.php?message=' . urlencode($msg));
        }
        exit;
    }
}
