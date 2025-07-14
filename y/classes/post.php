<?php
class Post {
    private $postId;
    private $postText;
    private $userId;
    private $originalPostId;
    private $replyToPostId;
    private $date;

    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function __construct($postId, $postText, $userId, $originalPostId, $replyToPostId, $date) {
        $this->postId = $postId;
        $this->postText = $postText;
        $this->userId = $userId;
        $this->originalPostId = $originalPostId;
        $this->replyToPostId = $replyToPostId;
        $this->date = $date;
    }
    public function getTimeString() {
        $timezone = new DateTimeZone('America/Halifax');
        $currentTime = new DateTime();
        $tweetTime = new DateTime($this->date, $timezone);
        $interval = $tweetTime->diff($currentTime);
        $time = "just now";
        $timeUnits = [
            'year' => $interval->y,
            'month' => $interval->m,
            'day' => $interval->d,
            'hour' => $interval->h,
            'minute' => $interval->i,
        ];
        foreach ($timeUnits as $unit => $value) {
            if ($value > 0) {
                $time = $value . ' ' . $unit . ($value > 1 ? 's' : '') . ' ago';
                break;
            }
        }
        return $time;
    }
    public static function getPostById($con, $postId) {
        $stmt = $con->prepare('SELECT post_id, post_text, user_id, original_post_id, reply_to_post_id, date_created FROM posts WHERE post_id = ?');
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->bind_result($postId, $postText, $userId, $originalPostId, $replyToPostId, $date);
        if ($stmt->fetch()) {
            $post = new Post($postId, $postText, $userId, $originalPostId, $replyToPostId, $date);
        } else {
            $post = null;
        }
        $stmt->close();
        return $post;
    }
    public static function insertPost($con, $postText, $userId) {
        $stmt = $con->prepare('INSERT INTO `posts` (`post_text`, `user_id`) VALUES (?,?)');
        $stmt->bind_param('si', $postText, $userId);
        if (!$stmt->execute()) return false;
        $stmt->close();
        return true;
    }
    public static function isReposted($con, $userId, $postId) {
        $stmt = $con->prepare('SELECT post_id FROM posts WHERE user_id = ? AND original_post_id = ?');
        $stmt->bind_param('ii', $userId, $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isReposted = $result->num_rows > 0;
        $stmt->close();
        return $isReposted;
    }
    public static function toggleRepost($con, $userId, $postId) {
        $isReposted = self::isReposted($con, $userId, $postId);
        if ($isReposted) {
            $stmt = $con->prepare('SELECT post_id FROM posts WHERE user_id = ? AND original_post_id = ?');
            $stmt->bind_param('ii', $userId, $postId);
            if (!$stmt->execute()) return false;
            $stmt->bind_result($repostId);
            $stmt->fetch();
            $stmt->close();

            $stmt = $con->prepare('SELECT post_id FROM posts WHERE reply_to_post_id = ?');
            $stmt->bind_param('i', $repostId);
            if (!$stmt->execute()) return false;
            $stmt->bind_result($post_id);
            $replyIds = [];
            while ($stmt->fetch()) {
                $replyIds[] = $post_id;
            }
            $stmt->close();

            $replyIds[] = $repostId;

            if (!empty($replyIds)) {
                $clause = implode(',', array_fill(0, count($replyIds), '?'));
                $stmt = $con->prepare("DELETE FROM likes WHERE post_id IN ($clause)");
                $stmt->bind_param(str_repeat('i', count($replyIds)), ...$replyIds);
                if (!$stmt->execute()) return false;
                $stmt->close();
            }

            $stmt = $con->prepare('DELETE FROM posts WHERE reply_to_post_id = ?');
            $stmt->bind_param('i', $repostId);
            if (!$stmt->execute()) return false;
            $stmt->close();

            $stmt = $con->prepare('DELETE FROM posts WHERE user_id = ? AND original_post_id = ?');
            $stmt->bind_param('ii', $userId, $postId);
            if (!$stmt->execute()) return false;
            $stmt->close();

        } else {
            $stmt = $con->prepare('INSERT INTO `posts` (`user_id`, `original_post_id`) VALUES (?,?)');
            $stmt->bind_param('ii', $userId, $postId);
            if (!$stmt->execute()) return false;
            $stmt->close();
        }
        return true;
    }
    public static function getPosts($con, $userId) {
        $stmt = $con->prepare('SELECT post_id, post_text, user_id, original_post_id, reply_to_post_id, date_created
            FROM posts
            WHERE reply_to_post_id = 0
            AND user_id = ?
            ORDER BY date_created DESC');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($postId, $postText, $userId, $originalPostId, $replyToPostId, $date);
        $posts = [];
        while ($stmt->fetch()) {
            $posts[] = new Post($postId, $postText, $userId, $originalPostId, $replyToPostId, $date);
        }
        $stmt->close();
        return $posts;
    }
    public static function insertReply($con, $postText, $userId, $replyToPostId) {
        $stmt = $con->prepare('INSERT INTO `posts` (`post_text`, `user_id`, `reply_to_post_id`) VALUES (?,?,?)');
        $stmt->bind_param('sii', $postText, $userId, $replyToPostId);
        $stmt->execute();
        $stmt->close();
    }
    public static function getReplies($con, $postId) {
        $stmt = $con->prepare('SELECT post_id, post_text, user_id, original_post_id, reply_to_post_id, date_created
            FROM posts WHERE reply_to_post_id = ? ORDER BY date_created LIMIT 5');
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->bind_result($post_id, $postText, $userId, $originalPostId, $replyToPostId, $date);
        $posts = [];
        while ($stmt->fetch()) {
            $posts[] = new Post($post_id, $postText, $userId, $originalPostId, $replyToPostId, $date);
        }
        $stmt->close();
        return $posts;
    }
    public static function isLiked($con, $postId, $userId) {
        $stmt = $con->prepare('SELECT like_id FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isLiked = $result->num_rows > 0;
        $stmt->close();
        return $isLiked;
    }
    public static function toggleLike($con, $postId, $userId) {
        $isLiked = self::isLiked($con, $postId, $userId);
        if ($isLiked) {
            $stmt = $con->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $con->prepare('INSERT INTO `likes` (`post_id`, `user_id`) VALUES (?,?)');
            $stmt->bind_param('ii', $postId, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
    public static function displayEachPost($con, $post) {
        $user = User::getUserById($con, $post->userId);
        $loggedUser = $_SESSION['userId'];
        $isReposted = self::isReposted($con, $loggedUser, $post->postId);
        $repostImg = ($isReposted) ? 'reposted.png' : 'repost.png';
        $isLiked = self::isLiked($con, $post->postId, $loggedUser);
        $likeImg = ($isLiked) ? 'liked.png' : 'like.png';
        echo    '
            <div class="post-background">
                <div class="append-reply">
                    <div class="content-padding">
                        <div class="flex-row">
                            <img src="images/profilepics/' . $user->profilePic . '" class="profile-pic">
                            <div>
                                <span class="bold">' . $user->displayName . '</span>
                                <a href="userpage.php?user_id=' . $user->userId . '" class="link-user">@' . $user->username . '</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content-padding">' . $post->postText . '
                        <div class="space-between">
                            <div class="timestamp">' . $post->getTimeString() . '</div>
                            <div class="icons">
                                <a href="#" class="reply" data-post-id="' . $post->postId . '">
                                    <img src="images/icons/reply.png" class="icon-size">
                                </a>
                                <form method="POST" action="process/repost_proc.php">
                                    <input type="hidden" name="post_id" value="' . $post->postId . '">
                                    <input type="image" src="images/icons/' . $repostImg . '" class="icon-size">
                                </form>
                                <form name="like_form" method="POST" action="process/like_proc.php">
                                    <input type="hidden" name="post_id" value="' . $post->postId . '">
                                    <input type="image" src="images/icons/' . $likeImg . '" class="icon-size">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
    public static function displayEachRepost($con, $post) {
        $originalPost = self::getPostById($con, $post->originalPostId);
        $originalUser = User::getUserById($con, $originalPost->userId);
        $loggedUser = $_SESSION['userId'];
        $isLiked = self::isLiked($con, $post->postId, $loggedUser);
        $likeImg = ($isLiked) ? 'liked.png' : 'like.png';

        echo    '
            <div class="post-background">
                <div class="append-reply">
                    <div class="content-padding">
                        <div class="flex-row">
                            <img src="images/profilepics/' . $originalUser->profilePic . '" class="profile-pic">
                            <div>
                                <span class="bold">' . $originalUser->displayName . '</span>
                                <a href="userpage.php?user_id=' . $originalUser->userId . '" class="link-user">@' . $originalUser->username . '</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content-padding">' . $originalPost->postText . '
                        <div class="space-between">
                            <div class="timestamp">' . $post->getTimeString() . '</div>
                            <div class="icons">
                                <a href="#" class="reply" data-post-id="' . $post->postId . '">
                                    <img src="images/icons/reply.png" class="icon-size">
                                </a>
                                <form method="POST" action="process/repost_proc.php">
                                    <input type="hidden" name="post_id" value="' . $originalPost->postId . '">
                                    <input type="image" src="images/icons/posted.png" class="icon-size">
                                </form>
                                <form name="like_form" method="POST" action="process/like_proc.php">
                                    <input type="hidden" name="post_id" value="' . $post->postId . '">
                                    <input type="image" src="images/icons/' . $likeImg . '" class="icon-size">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
    public static function displayPosts($con, $posts) {
        $loggedUser = $_SESSION['userId'];
        $userpage = $_GET['user_id'] ?? null;
        $count = count($posts);

        if ($count == 0 && $userpage != null) {
            echo    '
                <div class="flex-center">
                    <img src="images/noposts.png" width="328" height="384">
                </div>';
        } else {
            foreach ($posts as $post) {
                if ($post->originalPostId != 0) {
                    self::displayEachRepost($con, $post);
                } else {
                    self::displayEachPost($con, $post);
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