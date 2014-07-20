<?php 
//classtest1.php

$myCar = new Car();
$myCar->color = 'red';
$myCar->price = 30000;
$myCar->model = 'Ferrari';
$myCar->mph = 140;

$hours = 2;

echo('My Car is a ' . $myCar->color . ' ' . $myCar->model . ' and it can go ' . $myCar->go($hours) . ' miles in ' . $hours . ' hours');


class Car {
	//varible in a class are properties?
	public $color = '';
	public $mph = 0;
	public $price = 0;
	public $model = '';
	
	//method are like function in class
	
	function go($hours) {
		return ($this->mph * $hours);
	}
			
}

?>