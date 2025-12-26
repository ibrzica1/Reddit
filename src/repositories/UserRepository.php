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
        $stmt->bindParam(':username',$user->username);
        $stmt->bindParam(':email',$user->email);
        $stmt->bindParam(':password',$user->password);
        $stmt->bindParam(':bio',$user->bio);
        $stmt->bindParam(':avatar',$user->avatar);
        $stmt->bindParam(':karma',$user->karma);
        $stmt->bindParam(':time',$user->time);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
    }
}