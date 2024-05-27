<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../../accuel/includes/bdd.php");
class BlogHandler {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function submitComment($id, $comment,$Table, $column) {
        $sql = "INSERT INTO $Table ($column, id_user, comment) VALUES (:id_blog, :id_user, :comment)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_blog' => $id, 'id_user' => $_SESSION['id'], 'comment' => $comment]);
        return $stmt->rowCount() > 0;
    }
    public function likeUnlike($id, $likeTable, $mainTable, $column) {
        $sql = "SELECT id FROM $likeTable WHERE id_user = :id_user AND $column = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "id_user"=>$_SESSION['id'],
            "id"=>$id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            $this->unlikePost($id, $likeTable, $mainTable,$column);
            return 'noice';
        } else {
            $this->likePost($id,$likeTable, $mainTable,$column);
            return 'success';
        }
    } 
    private function unlikePost($id, $likeTable, $mainTable,$column) {
        $sql = "UPDATE $mainTable SET likes = likes - 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $sql = "DELETE FROM $likeTable WHERE id_user = ? AND $column = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$_SESSION['id'],$id]);
    }

    private function likePost($id, $likeTable, $mainTable,$column) {
        $sql = "UPDATE $mainTable SET likes = likes + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $sql = "INSERT INTO $likeTable ($column, id_user) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, $_SESSION['id']]);
    }
    public function getComments($id, $Table, $column) {
        $sql = "SELECT * FROM $Table WHERE $column = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteComment($id, $Table) {
        $sql = "DELETE FROM $Table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
    public function getLikes($id, $Table, $column) {
        $sql = "SELECT * FROM $Table WHERE $column = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create_post($title, $description, $Table, $image, $dir_r) {
        // Check if the image is valid
        if ($this->check_image($image)) {
           
            if($this->add_image($image, $title, '../img/')){
                // Insert post data into the database
                $sql = "INSERT INTO $Table (title, description, id_user) VALUES (:title, :description, :id_user)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['title' => $title, 'description' => $description, 'id_user' => $_SESSION['id']]);
                return true;
            } else {
                header("location:add_post.php?message=Failed to upload the file");
                exit;
                }
    }else{
        // Handle file upload failure
        header("location:add_post.php?message=wrong file");
        exit;
    }
    }
    

    public function delete_post($id, $title, $Table, $CTable, $column,$dir_r, $likeTable, $columnL) {
        $words = explode(' ', $title);
        $title_n = implode('', $words); 
        if ($this->delete_image($title_n, $dir_r)) {
            // Begin a transaction
            $this->pdo->beginTransaction();
            
            try {
                // Delete related comments
                $sql = "DELETE FROM $CTable WHERE $column = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $sql = "DELETE FROM $likeTable WHERE $columnL = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                // Delete the post
                $sql = "DELETE FROM $Table WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
    
                // Commit the transaction
                $this->pdo->commit();
                return $stmt->rowCount() > 0;
            } catch (Exception $e) {
                // Rollback the transaction if something failed
                $this->pdo->rollBack();
                throw $e;
            }
        } else {
            return false;
        }
    }
    public function update_post($id, $title, $description, $Table, $image, $dir_r) {
if(isset($image)){
        if($this->check_image($image)){
            if($this->add_image($image, $title, $dir_r)){
                $sql = "UPDATE $Table SET title = :title, description = :description WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['title' => $title, 'description' => $description, 'id' => $id]);
                return $stmt->rowCount() > 0;
            }
        }
    }else{
        $sql = "UPDATE $Table SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['title' => $title, 'description' => $description, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }
    }
    private function check_image($image) {
        if (isset($image["error"]) && $image["error"] === UPLOAD_ERR_OK) {
            // Sanitize and validate file type
            $acceptable = ["image/jpeg", "image/gif", "image/png", "application/pdf"];
            if (!in_array($image["type"], $acceptable)) {
                header('location:add_post.php?message=wrong file type');
                exit;
            }
            // Validate file size
            $maxSize = 1024 * 1024 * 5; // 5 MB
            if ($image["size"] > $maxSize) {
                header("location:add_post.php?message=file bigger than 3M");
                exit;
            }
            return true; // Image is valid
        }
        return false; // Image is not valid
    }
    public function add_image($image, $title, $dir_r) {
        if ($this->check_image($image)) {
            $words = explode(' ', $title);
            $title_n = implode('', $words);        
            $uploadDirectory = $dir_r . $title_n . '_' . $_SESSION['id'];
            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true); // Create recursively if doesn't exist
            }
    
            // Generate filename
            $array = explode('.', $image['name']);
            $ext = end($array);
            $filename = $title_n . uniqid() . '.' . $ext;
            $destination = $uploadDirectory . '/' . $filename;
    
            // Move uploaded image to the directory
            if (move_uploaded_file($image['tmp_name'], $destination)) {
                return true;
            } else {
                // Handle file upload failure
                header("location:add_post.php?message=Failed to upload the file");
                exit;
            }
        }
    }
    private function delete_image($title,$dir_r) {
        $dir = $dir_r . $title . '_' . $_SESSION['id'];
        if (file_exists($dir)) {
            $scandir = scandir($dir);
            foreach ($scandir as $row) {
                unlink($dir . '/' . $row);
            }
            rmdir($dir);
        }else{
            return true;
        }
    }
}

class friends{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function check_friend($id) {
        $sql = "SELECT id FROM friends WHERE id_user_f = :id_user_f AND id_user_m = :id_user_m";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user_f' => $_SESSION['id'], 'id_user_m' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return true;
        } else {
            return false;
        }
    }
    public function getProfile($id){
        $sql = "SELECT id, username,lname,fname,number,image FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPost($id) {
        $sql = "SELECT * FROM posts WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMainPage(){
        $sql = "SELECT id_user_m FROM friends WHERE id_user_f = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$_SESSION['id']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $posts = [];

        foreach ($result as $row) {
            $userPosts = $this->getPost($row['id_user_m']);
            $posts = array_merge($posts, $userPosts);
        }
        return $this->shuffle_assoc($posts); // Shuffle all posts together once
    }
    private function shuffle_assoc($list) {
        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $randomized = array();
        foreach ($keys as $key) {
            $randomized[$key] = $list[$key];
        }
        return $randomized;
    }
    
public function getFriends($search) {
    $sql = ('SELECT username,image,id FROM users WHERE username LIKE ?');
    $stmt = $this->pdo->prepare($sql);
    $success = $stmt->execute([
        "%" . $search ."%"
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
        public function add_remove_Friend($id) { 
          $sql ="SELECT id FROM friends WHERE id_user_f = :id_user_f AND id_user_m = :id_user_m";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_user_f' => $_SESSION['id'], 'id_user_m' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                $this->removeFriend($result['id']);
                echo 'noice';
            }else{ 
                $this->addFriend($id);
                echo 'success';
            } 
        } 
        private function removeFriend($id) {
            $sql = "DELETE FROM friends WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        }
        private function addFriend($id) {
            $sql = "INSERT INTO friends (id_user_f, id_user_m) VALUES (:id_user_f, :id_user_m)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_user_f' => $_SESSION['id'], 'id_user_m' => $id]);
            return $stmt->rowCount() > 0;
        }  
   

}
class dm{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function createDm($id) {
        $sql = "INSERT INTO dm (id_user1, id_user2) VALUES (:id_user1, :id_user2)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user1' => $_SESSION['id'], 'id_user2' => $id]);

         if($stmt->rowCount() > 0){
            $sql = "SELECT id FROM dm WHERE id_user1 = :id_user1 AND id_user2 = :id_user2";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_user1' => $_SESSION['id'], 'id_user2' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
         }else{
            return false;
         } 
    }
    public function checkDm($id) {
        $sql = "SELECT id FROM dm WHERE (id_user1 = :id_user1 AND id_user2 = :id_user2) or (id_user1 = :id_user2 AND id_user2 = :id_user1)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user1' => $_SESSION['id'], 'id_user2' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result['id'];
        } else {
            return false;
        }
    }
    public function getDm(){
        $sql = "SELECT dm.id, users.username 
        FROM dm 
        INNER JOIN users ON (dm.id_user1 = users.id OR dm.id_user2 = users.id) 
        WHERE (dm.id_user1 = :id OR dm.id_user2 = :id) AND users.id != :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $_SESSION['id'],'id_user1' => $_SESSION['id'], 'id_user2' => $_SESSION['id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMessages($id) {
        $sql = "SELECT message,id_user FROM messages WHERE id_dm = :id ORDER BY sent_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ 'id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function sendMessage($id, $message) {
        $sql = "INSERT INTO messages (id_user, id_dm, message) VALUES (:id_user, :id_dm, :message)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $_SESSION['id'], 'id_dm' => $id, 'message' => $message]);
        return $stmt->rowCount() > 0;
    }
   public function check_user($id){
    $sql = " SELECT id_user1, id_user2 FROM dm WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result['id_user1'] == $_SESSION['id'] || $result['id_user2'] == $_SESSION['id']){
        return true;
   }else{
    header('location:../../admin/user/smart.php');
    exit;
   }
}
}