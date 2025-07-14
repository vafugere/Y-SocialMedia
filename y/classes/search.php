<?php
class Search {
    public static function getSearchedUsers($con, $searchText) {
        $stmt = $con->prepare('SELECT user_id, display_name, username, profile_pic FROM users
            WHERE display_name LIKE ? OR username LIKE ? LIMIT 5');
        $searchTerm = "%{$searchText}%";
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $stmt->bind_result($user_id, $display_name, $username, $profilePic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, null, null, $display_name, $username, null, null, null, $profilePic);
        }
        $stmt->close();
        return $users;
    }
    public static function displaySearchedUsers($con, $searchText) {
        $users = self::getSearchedUsers($con, $searchText);
        if (!empty($users)) {
            $count = count($users);
            foreach ($users as $i => $user) {
                User::displayUser($con, $user);
                if ($i < $count -1 ) {
                    echo '<hr>';
                }
            }
            echo '<br>';
        }
    }
    public static function getSearchedPosts($con, $searchText) {
        $stmt = $con->prepare('SELECT post_id, post_text, user_id, date_created FROM posts
            WHERE reply_to_post_id = 0
            AND post_text LIKE ?
            LIMIT 5');
        $searchTerm = "%{$searchText}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $stmt->bind_result($post_id, $post_text, $user_id, $date_created);
        $posts = [];
        while ($stmt->fetch()) {
            $posts[] = new Post($post_id, $post_text, $user_id, null, null, $date_created);
        }
        $stmt->close();
        return $posts;
    }
    public static function displaySearchedPosts($con, $searchText) {
        $posts = self::getSearchedPosts($con, $searchText);
        if (!empty($posts)) {
            foreach ($posts as $post) {
                Post::displayEachPost($con, $post);
            }
        }
    }
}