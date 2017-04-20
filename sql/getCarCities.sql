SELECT *
FROM car_cities
JOIN cities on car_cities.cityid = cities.cityid
WHERE carid = :carid