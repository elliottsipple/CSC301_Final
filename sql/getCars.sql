SELECT *
FROM cars
WHERE
    make LIKE :term OR
    model LIKE :term OR
    year LIKE :term OR
    color LIKE :term