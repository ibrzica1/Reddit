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

    public function registerUser(User $user): void
    {
        $stmt = $this->connection->prepare("INSERT INTO user (username, email, password, bio, avatar, time, karma)
        VALUES (:username, :email, :password, :bio, :avatar, :time, :karma)");
        $stmt->bindParam(':username',$user->username);
        $stmt->bindParam(':email',$user->email);
        $password = password_hash($user->password,PASSWORD_BCRYPT);
        $stmt->bindParam(':password',$user->password);
        $stmt->bindParam(':bio',$user->bio);
        $stmt->bindParam(':avatar',$user->avatar);
        $stmt->bindParam(':time',$user->time);
        $stmt->bindParam(':karma',$user->karma);

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

    public function getUserAtribute(string $attribute, int $id): mixed
    {
        $stmt = $this->connection->prepare(
        "SELECT * FROM user WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch();
        $user = new User($data);
        return $user->$attribute;
    }

    public function updateUser(string $atribute, string $value, int $id): void
    {
        $stmt = $this->connection->prepare("UPDATE user 
        SET $atribute = :atribute
        WHERE id = :id");
        $stmt->bindParam(':atribute',$value);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
    }
}