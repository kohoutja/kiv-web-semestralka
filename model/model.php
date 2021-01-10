<?php

class Model
{

    protected PDO $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function signin($email, $password): int
    {
        $sql = "SELECT * FROM semestralka.user WHERE email=:email";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("email" => $email));
        $result = $statement->fetchAll();

        if (count($result) < 1) {
            // user does not exist
            return 1;
        } else if (count($result) > 1) {
            // if there is more then 1 result -> more of the same usernames prevent this!
            return 2;
        } else {
            if ($password == $result[0]["password"]) {
                $_SESSION["id"] = $result[0]["id"];
                $_SESSION["username"] = $result[0]["username"];
                $_SESSION["role"] = $result[0]["role"];
                $_SESSION["email"] = $result[0]["email"];
                $_SESSION["fullName"] = $result[0]["fullName"];
                $_SESSION["signed"] = true;
                return 0;
            } else {
                return 3; // wrong password
            }
        }
    }

    public function isEmailOccupied($email): bool
    {
        $sql = "SELECT * FROM semestralka.user WHERE email=:email";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("email" => $email));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return count($result) >= 1;
    }

    public function addUser($email, $username, $password, $fullName)
    {
        $sql = "INSERT INTO semestralka.user(email, username, password, fullName) VALUES (:email, :username, :password, :fullName)";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("email" => $email, "username" => $username, "password" => $password, "fullName" => $fullName));
    }

    public function getPublishedPosts()
    {
        $sql = "SELECT author, title, published, fullName FROM semestralka.post, semestralka.user 
                WHERE published = 1 AND user.id = author";
        $statement = $this->database->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostByTitle($title)
    {
        $sql = "SELECT p.id, p.author, p.title, p.description, p.file, p.published, u.fullName 
                FROM semestralka.post AS p, semestralka.user AS u WHERE title=:title AND u.id = author";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("title" => $title));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($postId)
    {
        $sql = "SELECT * FROM semestralka.post WHERE id=:postId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPosts($userId)
    {
        $sql = "SELECT title, post.description, file, published, state.description FROM semestralka.post, semestralka.state 
                WHERE author=:id AND state.id = state";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("id" => $userId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submitPost($author, $title, $description, $filename)
    {
        $sql = "INSERT INTO semestralka.post(author, title, description, file) VALUES (:id, :title, :description, :file)";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("id" => $author, "title" => $title, "description" => $description, "file" => $filename));

    }

    public function getReviewerAssignWaitingPosts()
    {
        $sql = "SELECT title, id FROM semestralka.post WHERE state=1";
        $statement = $this->database->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostReviewersById($id)
    {
        $sql = "SELECT u.fullName, ar.reviewed FROM semestralka.user AS u, semestralka.assigned_reviewer AS ar 
                WHERE u.id=ar.reviewer AND ar.post=:id";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("id" => $id));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableReviewers($id)
    {
        $sql = "SELECT DISTINCT fullName, id FROM semestralka.user WHERE role<=2 AND id NOT IN 
                (SELECT DISTINCT u.id FROM semestralka.user AS u JOIN semestralka.assigned_reviewer AS aq ON u.id=aq.reviewer 
                WHERE aq.post=:id)";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("id" => $id));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $sql = "SELECT user.*, role.id FROM semestralka.user LEFT JOIN semestralka.role ON user.role = role.id";
        $statement = $this->database->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRoles()
    {
        $sql = "SELECT * FROM semestralka.role";
        $statement = $this->database->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($email, $role)
    {
        $sql = "UPDATE semestralka.user SET user.role=:role WHERE user.email=:email";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("email" => $email, "role" => $role));
    }

    public function getReviewerWaitingPosts($id)
    {
        $sql = "SELECT p.title, ar.reviewed FROM semestralka.post as p JOIN semestralka.assigned_reviewer as ar ON p.id=ar.post 
                WHERE p.id=ar.post AND p.published=0 AND ar.reviewer=:id ORDER BY ar.reviewed";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("id" => $id));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignReviewer($postId, $reviewerId)
    {
        $sql = "INSERT INTO semestralka.assigned_reviewer(reviewer, post) VALUES (:reviewer, :post)";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("reviewer" => $reviewerId, "post" => $postId));
    }

    public function getAssignedReviewsCount($postId)
    {
        $sql = "SELECT COUNT(*) FROM semestralka.assigned_reviewer WHERE post=:post";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("post" => $postId));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePostState($postId, $state)
    {
        $sql = "UPDATE semestralka.post SET state=:state WHERE id=:id";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("state" => $state, "id" => $postId));
    }

    public function wasAlreadyReviewed($userId, $postId)
    {
        $sql = "SELECT reviewed FROM semestralka.assigned_reviewer WHERE post=:postId AND reviewer=:userId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId, "userId" => $userId));
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result["reviewed"] == 1;
    }

    public function updateReview($criterion1, $criterion2, $criterion3, $overall, $text, $postId, $userId)
    {
        $sql = "UPDATE semestralka.review SET criterion1=:c1, criterion2=:c2, criterion3=:c3, overall=:ol, text=:text 
                WHERE reviewer=:userId AND post=:postId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("c1" => $criterion1, "c2" => $criterion2, "c3" => $criterion3, "ol" => $overall,
            "text" => $text, "userId" => $userId, "postId" => $postId));
    }

    public function addReview($criterion1, $criterion2, $criterion3, $overall, $text, $postId, $userId)
    {
        $sql = "INSERT INTO semestralka.review(post, reviewer, criterion1, criterion2, criterion3, overall, text) 
                VALUES (:postId, :id, :c1, :c2, :c3, :ol, :text)";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId, "id" => $userId, "c1" => $criterion1, "c2" => $criterion2,
            "c3" => $criterion3, "ol" => $overall, "text" => $text));
    }

    public function setPostAsReviewed($userId, $postId)
    {
        $sql = "UPDATE semestralka.assigned_reviewer SET reviewed=1 WHERE post=:postId AND reviewer=:userId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId, "userId" => $userId));
    }

    public function getReviewedUnpublishedPosts()
    {
        $sql = "SELECT DISTINCT p.title, p.id, COUNT(*) FROM semestralka.post AS p JOIN semestralka.assigned_reviewer AS ar ON ar.post=p.id 
                WHERE ar.reviewed=1 AND p.published=0 GROUP BY ar.post HAVING COUNT(*) >=3";
        $statement = $this->database->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostReviews($postId)
    {
        $sql = "SELECT r.*, u.username FROM semestralka.review AS r, semestralka.user AS u WHERE post=:postId AND u.id=r.reviewer";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function acceptPost($postId)
    {
        $sql = "UPDATE semestralka.post SET published=1, state=4 WHERE id=:postId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId));
    }

    public function denyPost($postId)
    {
        $sql = "UPDATE semestralka.post SET published=0, state=5 WHERE id=:postId";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("postId" => $postId));
    }

    public function checkReviewsDone($postId)
    {
        $sql = "SELECT COUNT(*) FROM semestralka.assigned_reviewer WHERE post=:post AND reviewed=1";
        $statement = $this->database->prepare($sql);
        $statement->execute(array("post" => $postId));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}