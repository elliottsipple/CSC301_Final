<?php

// Include configuration file
include('config.php');

// Include functions for application
include('functions.php');

// Get search term from URL using the get function
$term = get('search-term');

// Get car search results from database using searchCars function
$cars = searchCars($term, $database);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
	    <title>Cars</title>
        <meta name="description" content="The HTML5 Herald">
        <meta name="author" content="SitePoint">

        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="page">
            <h1>Cars for Sale</h1>
            <form method="GET">
                <input type="text" name="search-term" placeholder="Search..." />
                <input type="submit" value="Search" />
            </form>
            <?php
            foreach($cars as $car) : ?>
                <p>
                    <?php echo $car['year']; ?> <?php echo $car['make']; ?> <?php echo $car['model']; ?><br />
                    <?php echo $car['color']; ?><br />
                    Miles: <?php echo $car['miles']; ?><br />
                    Price: $<?php echo $car['price']; ?><br />
                    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>"><br />
                    <a href="form.php?action=edit&carid=<?php echo $car['carid'] ?>">Edit Car Information</a><br />
                    <a href="car.php?carid=<?php echo $car['carid'] ?>">View Car</a>
                </p>
            <?php endforeach; ?>
            <p><a href="form.php?action=add">Add a Car</a></p>
    
            <!--Print currently accessed by the current username-->
            <p>Currently logged in as: <?php echo $user->name ?></p>
            
            <!--Link for logout page-->
            <p><a href="logout.php">Log Out</a></p>
        </div>
    </body>
</html>    