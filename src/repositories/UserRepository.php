<?php

namespace Reddit\repositories;
use Reddit\models\Db;
use Reddit\models\User;

class UserRepository extends Db
{
    public function existsUsername(string $username): bool
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE username = :username ");
        $stmt->bindParam(':username',$username);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function existsEmail(string $email): bool
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = :email ");
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function registerUser(User $user): void
    {
        $stmt = $this->connection->prepare("INSERT INTO user (username, email, password, bio, avatar, time, karma)
        VALUES (:username, :email, :password, :bio, :avatar, :time, :karma)");
        $stmt->bindValue(':username',$user->getUsername(), \PDO::PARAM_STR);
        $stmt->bindValue(':email',$user->getEmail(), \PDO::PARAM_STR);
        $stmt->bindValue(':password',$user->getPassword(), \PDO::PARAM_STR);
        $stmt->bindValue(':bio',$user->getBio(), \PDO::PARAM_STR);
        $stmt->bindValue(':avatar',$user->getAvatar(), \PDO::PARAM_STR);
        $stmt->bindValue(':time',$user->getTime(), \PDO::PARAM_STR);
        $stmt->bindValue(':karma',$user->getKarma(), \PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getUserByAttribute(string $attribute, mixed $value): User
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE $attribute = :attribute");
        $stmt->bindParam(':attribute',$value);
        $stmt->execute();

        $data = $stmt->fetch();
        $user = new User($data);
        return $user;
    }

    public function getUserById(int $userId): User
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam(':id',$userId);
        $stmt->execute();

        $data = $stmt->fetch();
        $user = new User($data);
        return $user;
    }

    public function getUser(string $username): User
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username',$username);
        $stmt->execute();

        $data = $stmt->fetch();
        $user = new User($data);
        return $user;
    }

    public function updateUser(User $user, int $id): void
    {
        $stmt = $this->connection->prepare("UPDATE user 
        SET username = :username,
        email = :email,
        password = :password,
        bio = :bio,
        avatar = :avatar,
        karma = :karma,
        time = :time
        WHERE id = :id");
        $stmt->bindValue(':username',$user->getUsername(), \PDO::PARAM_STR);
        $stmt->bindValue(':email',$user->getEmail(), \PDO::PARAM_STR);
        $stmt->bindValue(':password',$user->getPassword(), \PDO::PARAM_STR);
        $stmt->bindValue(':bio',$user->getBio(), \PDO::PARAM_STR);
        $stmt->bindValue(':avatar',$user->getAvatar(), \PDO::PARAM_STR);
        $stmt->bindValue(':karma',$user->getKarma(), \PDO::PARAM_INT);
        $stmt->bindValue(':time',$user->getTime(), \PDO::PARAM_STR);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
    }
}