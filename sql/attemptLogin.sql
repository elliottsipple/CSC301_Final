SELECT *
FROM users
WHERE
    username = :username AND
    password = :password