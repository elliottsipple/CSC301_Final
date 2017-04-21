<?php

// include config and functions files
include('config.php');
include('functions.php');

// get type of form either add or edit from URL
$action = $_GET['action'];

// get carid from URL if it exists
$carid = get('carid');

// initially set $car to null
$car = null;

// initially set car_cities to an empty array
$car_cities = array();

if(!empty($carid)) {
    $sql = file_get_contents('sql/getCar.sql');
    $params = array(
        'carid' => $carid
    );
    $statement = $database->prepare($sql);
    $statement->execute($params);
    $cars = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    $car = $cars[0];
    
    // get car cities
    $sql = file_get_contents('sql/getCarCities.sql');
    $params = array(
        'carid' => $carid
    );
    $statement = $database->prepare($sql);
    $statement->execute($params);
    $car_cities_associative = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($car_cities_associative as $city) {
        $car_cities[] = $city['cityid'];
    }
}

// get an associative array of cities
$sql = file_get_contents('sql/getCities.sql');
$statement = $database->prepare($sql);
$statement->execute();
$cities = $statement->fetchAll(PDO::FETCH_ASSOC);

// If form submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $carid = $_POST['carid'];
    $make = $_POST['car-make'];
    $model = $_POST['car-model'];
    $year = $_POST['car-year'];
    $color = $_POST['car-color'];
    $miles = $_POST['car-miles'];
    $type = $_POST['car-type'];
    $price = $_POST['car-price'];
    $car_cities = $_POST['car-city'];

    if($action == 'add') {
        // add car
        $sql = file_get_contents('sql/insertCar.sql');
        $params = array(
            'carid' => $carid,
            'make' => $make,
            'model' => $model,
            'color' => $color,
            'year' => $year,
            'miles' => $miles,
            'type' => $type,
            'price' => $price
        );
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        // set cities for car
        $sql = file_get_contents('sql/insertCarCity.sql');
        $statement = $database->prepare($sql);
        
        foreach($car_cities as $city) {
            $params = array(
                'carid' => $carid,
                'cityid' => $city
            );
            $statement->execute($params);
        }
    }
    elseif($action == 'edit') {
        $sql = file_get_contents('sql/updateCar.sql');
        $params = array(
            'carid' => $carid,
            'make' => $make,
            'model' => $model,
            'color' => $color,
            'year' => $year,
            'miles' => $miles,
            'type' => $type,
            'price' => $price
        );
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        // remove current city info
        $sql = file_get_contents('sql/removeCities.sql');
        $params = array(
            'carid' => $carid
        );
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        // set cities for car
        $sql = file_get_contents('sql/insertCarCity.sql');
        $statement = $database->prepare($sql);
        
        foreach($car_cities as $city){
            $params = array(
                'carid' => $carid,
                'cityid' => $city
            );
            $statement->execute($params);
        };
    }
    // redirect to car listing page
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Car</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="page">
        <h1>Manage Car Information</h1>
        <form action="" method="POST">
            <div class="form-element">
                <label>CarID:</label>
                <?php if($action == 'add') : ?>
                    <input type="number" name="carid" class="textbox" value="<?php echo $car['carid'] ?>" />
                <?php else : ?>
                    <input readonly type="number" name="carid" class="textbox" value="<?php echo $car['carid'] ?>" />
                <?php endif; ?>
            </div>
            <div class="form-element">
                <label>Make:</label>
                <input type="text" name="car-make" class="textbox" value="<?php echo $car['make'] ?>" />
            </div>
            <div class="form-element">
                <label>Model:</label>
                <input type="text" name="car-model" class="textbox" value="<?php echo $car['model'] ?>" />
            </div>
            <div class="form-element">
                <label>Color:</label>
                <input type="text" name="car-color" class="textbox" value="<?php echo $car['color'] ?>" />
            </div>
            <div class="form-element">
                <label>Year:</label>
                <input type="number" setp="any" name="car-year" class="textbox" value="<?php echo $car['year'] ?>" />
            </div>
            <div class="form-element">
                <label>Miles:</label>
                <input type="number" name="car-miles" class="textbox" value="<?php echo $car['miles'] ?>" />
            </div>
            <div class="form-element">
                <label>Type:</label>
                <input type="text" name="car-type" class="textbox" value="<?php echo $car['type'] ?>" />
            </div>
            <div class="form-element">
                <label>Price:</label>
                <input type="number" step="any" name="car-price" class="textbox" value="<?php echo $car['price'] ?>" />
            </div>
            <div class="form-element">
                <label>City:</label>
                <?php foreach($cities as $city) : ?>
                    <?php if(in_array($city['cityid'], $car_cities)) : ?>
                        <input checked class="radio" type="checkbox" name="car-city[]" value="<?php echo $city['cityid'] ?>" /><span class="radio-label"><?php echo $city['name'] ?>, <?php echo $city['state'] ?></span><br />
                    <?php else : ?>
                        <input class="radio" type="checkbox" name="car-city[]" value="<?php echo $city['cityid'] ?>" /><span class="radio-label"><?php echo $city['name'] ?>, <?php echo $city['state'] ?></span><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="form-element">
                <input type="submit" class="button" />&nbsp;
                <input type="reset" class="button" />
            </div>
        </form>
    </div>
</body>
</html>