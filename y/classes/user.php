<?php
class User {
    private $userId;
    private $firstName;
    private $lastName;
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
    public function __construct($userId, $firstName, $lastName, $username, $password, $email, $date, $profilePic) {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->date = $date;
        $this->profilePic = $profilePic;
    }
    public function fullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
    public static function createUser($con, $user) {
        $secure_password = password_hash($user->password, PASSWORD_DEFAULT);
        $defaultPic = 'default_picture.jpg';
        $userId = null;
        $stmt = $con->prepare('INSERT INTO `users` (`first_name`, `last_name`, `screen_name`, `password`, `email`, `profile_pic`)
            VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('ssssss', $user->firstName, $user->lastName, $user->username, $secure_password, $user->email, $defaultPic);
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
        $stmt = $con->prepare('SELECT user_id, password FROM users WHERE screen_name = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($userId, $password);
        if ($stmt->fetch()) {
            $user = new User($userId, null, null, null, $password, null, null, null);
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
        $stmt = $con->prepare('SELECT user_id FROM users WHERE screen_name = ?');
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
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, email, date_created, profile_pic FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($userId, $fname, $lname, $username, $email, $date, $profilePic);
        if ($stmt->fetch()) {
            $user = new User($userId, $fname, $lname, $username, null, $email, $date, $profilePic);
        } else {
            $user = null;
            $msg = 'An unexpected error has occured, please sign in again';
            header('Location: login.php?message=' . urlencode($msg));
            exit;
        }
        $stmt->close();
        return $user;
    }
    public static function UploadProfilePic($con, $picture, $userId) {
        $stmt = $con->prepare('UPDATE users SET profile_pic = ? WHERE user_id = ?');
        $stmt->bind_param('si', $picture, $userId);
        if ($stmt->execute()) {
            $stmt->close();
            User::redirectSuccessful();
        } else {
            $stmt->close();
            User::redirectUnsuccessful();
        }
    }
    public static function displayProfilePic($con, $userId) {
        $user = User::getUserById($con, $userId);
        echo '<img class="profilepic" src="images/profilepics/' . $user->profilePic . '">';
    }
    public static function countTweets($con, $userId) {
        $stmt = $con->prepare('SELECT COUNT(*) FROM tweets WHERE user_id = ? AND reply_to_tweet_id = 0');
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
    public static function displayUserInfo($con, $userId) {
        $user = User::getUserById($con, $userId);
        $countTweets = User::countTweets($con, $userId);
        $countFollowing = User::countFollowing($con, $userId);
        $countFollowers = User::countFollowers($con, $userId);
        $date = date('M d, Y', strtotime($user->date));
        $loggedUser = $_SESSION['userId'];
        $isFollowing = User::isFollowing($con, $loggedUser, $user->userId);
        $followText = ($isFollowing) ? 'Unfollow' : 'Follow';

        echo '
            <div class="bold">
                <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                <a href="userpage.php?user_id=' . $user->userId . '">' . $user->fullName() . '</a><br>
            </div>
            <table>
                <tr>
                    <td>Tweets</td>
                    <td>Following</td>
                    <td>Followers</td>
                </tr>
                <tr>
                    <td>' . $countTweets . '</td>
                    <td><a href="following.php?user_id=' . $user->userId . '">' . $countFollowing . '</a></td>
                    <td><a href="followers.php?user_id=' . $user->userId . '">' . $countFollowers . '</a></td>
                </tr>
            </table>';
        if ($userId != $loggedUser) {
            echo    '
                    <div class="userpage-follow">
                        <form action="process/follow_proc.php" method="post">
                            <input type="hidden" name="user_id" value="' . $user->userId . '">
                            <input type="submit" class="follow-button" value="' . $followText . '">
                        </form>
                    </div>';

        } else {
            echo    '<div class="label">Member Since:<br>' . $date . '</div>';
        }
            
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
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $con->prepare('INSERT INTO `follows` (`from_id`, `to_id`) VALUES (?,?)');
            $stmt->bind_param('ii', $fromId, $toId);
            $stmt->execute();
            $stmt->close();
        }
        User::redirectSuccessful();
    }
    public static function suggestedUsers($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, profile_pic FROM users WHERE user_id != ?
            AND user_id NOT IN (SELECT to_id FROM follows WHERE from_id = ?) ORDER BY RAND() LIMIT 6');
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $username, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, $fname, $lname, $username, null, null, null, $profile_pic);
        }
        $stmt->close();
        $count = count($users);
        foreach ($users as $i => $user) {
            $isFollowing = User::isFollowing($con, $userId, $user->userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
            echo '
                <div class="flex-row">
                    <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                        <div>
                            <a class="bold" href="userpage.php?user_id=' . $user->userId . '"> @' . $user->username . '</a><br>' . $user->fullName() . '
                            <form action="process/follow_proc.php" method="post">
                                <input type="hidden" name="user_id" value="' . $user->userId . '">
                                <input type="submit" class="follow-button" value="' . $followText . '">
                            </form>
                        </div>
                    </div>';
            if ($i < $count - 1) {
                echo '<hr>';
            }
        }
    }
    public static function usersYouFollow($con, $userId) {
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, profile_pic FROM users
            WHERE user_id IN (SELECT to_id FROM follows WHERE from_id = ?) ORDER BY RAND() LIMIT 3');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $username, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, $fname, $lname, $username, null, null, null, $profile_pic);
        }
        $count = count($users);
        foreach ($users as $i => $user) {
            $isFollowing = User::isFollowing($con, $userId, $user->userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
            echo    '
                    <div class="flex-row">
                        <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                        <div>
                            <a class="bold" href="userpage.php?user_id=' . $user->userId . '">@' . $user->username . '</a><br>' . $user->fullName() . '
                            <form action="process/follow_proc.php" method="post">
                                <input type="hidden" name="user_id" value="' . $user->userId . '">
                                <input type="submit" class="follow-button" value="' . $followText . '">
                            </form>
                        </div>
                    </div>';
            if ($i < $count - 1) {
                echo '<hr>';
            }
        }
    }
    public static function displayFollowing($con, $userId) {
        $loggedUser = $_SESSION['userId'];
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, date_created, profile_pic FROM users
            WHERE user_id IN (SELECT to_id FROM follows WHERE from_id = ?)');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $username, $date, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, $fname, $lname, $username, null, null, null, $profile_pic);
        }
        $count = count($users);
        foreach ($users as $i => $user) {
            $isFollowing = User::isFollowing($con, $loggedUser, $user->userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
            echo    '
                    <div class="flex-row">
                        <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                        <div>
                            <a class="bold" href="userpage.php?user_id=' . $user->userId . '">@' . $user->username . '</a><br>' . $user->fullName() . '
                            <form action="process/follow_proc.php" method="post">
                                <input type="hidden" name="user_id" value="' . $user->userId . '">
                                <input type="submit" class="follow-button" value="' . $followText . '">
                            </form>
                        </div>
                    </div>';
            if ($i < $count - 1) {
                echo '<hr>';
            }
        }
    }
    public static function displayFollowers($con, $userId) {
        $loggedUser = $_SESSION['userId'];
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, date_created, profile_pic FROM users
            WHERE user_id IN (SELECT from_id FROM follows WHERE to_id = ?)');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $username, $date, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, $fname, $lname, $username, null, null, null, $profile_pic);
        }
        $count = count($users);
        foreach ($users as $i => $user) {
            $isFollowing = User::isFollowing($con, $loggedUser, $user->userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
            echo    '
                    <div class="flex-row">
                        <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                        <div>
                            <a class="bold" href="userpage.php?user_id=' . $user->userId . '">@' . $user->username . '</a><br>' . $user->fullName() . '
                            <form action="process/follow_proc.php" method="post">
                                <input type="hidden" name="user_id" value="' . $user->userId . '">
                                <input type="submit" class="follow-button" value="' . $followText . '">
                            </form>
                        </div>
                    </div>';
            if ($i < $count - 1) {
                echo '<hr>';
            }
        }
    }
    public static function redirectSuccessful() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public static function redirectUnsuccessful() {
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