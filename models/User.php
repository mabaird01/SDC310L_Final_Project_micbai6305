<?php

class User
{
    private PDO $pdo;


    /**
     * Create a User model.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Find a user by email address.
     *
     * @param string $email
     * @return array|false
     */
    public function getByEmail(string $email): array|false
    {
        $sql = "SELECT
                    user_id,
                    first_name,
                    last_name,
                    email,
                    password_hash,
                    created_at
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch();
    }


    /**
     * Find a user by ID.
     *
     * @param int $userId
     * @return array|false
     */
    public function getById(int $userId): array|false
    {
        $sql = "SELECT
                    user_id,
                    first_name,
                    last_name,
                    email,
                    password_hash,
                    created_at
                FROM users
                WHERE user_id = :user_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetch();
    }


    /**
     * Create a new user.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $passwordHash
     * @return int
     */
    public function create(
        string $firstName,
        string $lastName,
        string $email,
        string $passwordHash
    ): int {
        $sql = "INSERT INTO users
                    (
                        first_name,
                        last_name,
                        email,
                        password_hash
                    )
                VALUES
                    (
                        :first_name,
                        :last_name,
                        :email,
                        :password_hash
                    )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':password_hash' => $passwordHash
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}