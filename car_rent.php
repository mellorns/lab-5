<?php
function vendors(PDO $db)
{
    $statement = $db->query("SELECT DISTINCT * FROM vendors");
    while ($data = $statement->fetch()) {
        echo "<option value='$data[0]'>$data[1]</option>";
    }
}

function cars(PDO $db)
{
    $statement = $db->query("SELECT DISTINCT ID_Cars, name FROM cars");
    while ($data = $statement->fetch()) {
        echo "<option value='$data[0]'>$data[1]</option>";
    }
}

function costInDate(PDO $db, $date)
{
    $statement = $db->prepare("SELECT name, date_start, time_start, cost FROM cars INNER JOIN rent ON ID_Cars=FID_Car WHERE ? BETWEEN date_start and date_end");
    $statement->execute([$date]);
    echo "<table style='text-align: center'>";
    echo " <tr>
         <th> Name  </th>
         <th> Cost </th>
        </tr> ";
    while ($data = $statement->fetch()) {
        $cost = (strtotime($date) - strtotime($data["date_start"]."T".$data["time_start"]))/3600*$data["cost"];
        echo " <tr>
             <td> {$data['name']}  </td>
             <td> {$cost} </td>
            </tr> ";
    }
    echo "</table>";
}

function carByVendor(PDO $db, $vendor)
{
    $statement = $db->prepare("SELECT name, release_date, race FROM cars WHERE FID_Vendors=?");
    $statement->execute([$vendor]);
    echo "<table style='text-align: center'>";
    echo " <tr>
         <th> Name  </th>
         <th> Release Date </th>
         <th> Race </th>
        </tr> ";
    while ($data = $statement->fetch()) {
        echo " <tr>
             <td> {$data['name']}  </td>
             <td> {$data['release_date']} </td>
             <td> {$data['race']} </td>
            </tr> ";
    }
    echo "</table>";
}

function freeCarInDate(PDO $db, $free_car)
{
    $statement = $db->prepare("SELECT name, release_date, race FROM cars INNER JOIN rent ON ID_Cars=FID_Car WHERE ? NOT BETWEEN date_start and date_end");
    $statement->execute([$free_car]);
    echo "<table style='text-align: center'>";
    echo " <tr>
         <th> Name  </th>
         <th> Release Date </th>
         <th> Race </th>
        </tr> ";
    while ($data = $statement->fetch()) {
        echo " <tr>
             <td> {$data['name']}  </td>
             <td> {$data['release_date']} </td>
             <td> {$data['race']} </td>
            </tr> ";
    }
    echo "</table>";
}

function addCar($db, $car, $date_start, $date_end, $cost)
{
    $statement = $db->prepare("INSERT INTO rent (FID_Car, date_start, date_end, cost) VALUES (?, ?, ?, ?)");
    $statement->execute([$car, $date_start, $date_end, $cost]);
}

function updateRace($db, $car, $race)
{
    $statement = $db->prepare("UPDATE cars SET race = ? WHERE ID_Cars = ?");
    $statement->execute([$race, $car]);
}