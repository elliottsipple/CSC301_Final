<?php

// include config and functions files
include('config.php');
include('functions.php');

// Get the carID from the url
$carid = get('carid');
print($carid);

// Get a list of cars from the database with the carID passed in the URL
$sql = file_get_contents('sql/getCar.sql');
$params = array(
	'carid' => $carid
);
print_r($params);
$statement = $database->prepare($sql);
$statement->execute($params);
$cars = $statement->fetchAll(PDO::FETCH_ASSOC);

// Set $car equal to the first car in $cars
$car = $cars[0];

// Get cities where cars are advertised from the database
$sql = file_get_contents('sql/getCarCities.sql');
$params = array(
	'carid' => $carid
);
$statement = $database->prepare($sql);
$statement->execute($params);
$cities = $statement->fetchAll(PDO::FETCH_ASSOC);

/* In the HTML:
	- Print the car make, model, color, year, miles, type, and price
	- List the cities where this car is currently advertised
*/
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<title>Car Information</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="page">
		<h1><?php echo $car['year'] ?> <?php echo $car['make'] ?> <?php echo $car['model'] ?></h1>
		<p>
			Color: <?php echo $car['color']; ?><br />
			Miles: <?php echo $car['miles']; ?><br />
            Type: <?php echo $car['type']; ?><br />
            Price: $<?php echo $car['price']; ?><br />
		</p>
		
		<ul>
			<?php foreach($cities as $city) : ?>
				<li><?php echo $city['name'] ?></li>
			<?php endforeach; ?>
		</ul>
		
	</div>
</body>
</html>