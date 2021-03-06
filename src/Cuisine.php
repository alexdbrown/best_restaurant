<?php
    class Cuisine
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO cuisines_table (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function getRestaurants()
        {
            $restaurants = Array();
            $db_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants_table WHERE cuisine_id = {$this->getId()};");
            foreach ($db_restaurants as $restaurant) {
                $restaurant_name = $restaurant['restaurant_name'];
                $phone = $restaurant['phone'];
                $address = $restaurant['address'];
                $website = $restaurant['website'];
                $cuisine_id = $restaurant['cuisine_id'];
                $id = $restaurant['id'];
                $new_restaurant = new Restaurant($restaurant_name, $phone, $address, $website, $cuisine_id, $id);
                array_push($restaurants, $new_restaurant);
            }
            return $restaurants;
        }


        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE cuisines_table SET = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM cuisines_table WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM restaurants_table WHERE cuisine_id = {$this->getId()};");
        }

        static function getAll()
        {
            $db_cuisines = $GLOBALS['DB']->query("SELECT * FROM cuisines_table;");
            $cuisines = array();
            foreach ($db_cuisines as $cuisine){
                $name = $cuisine['name'];
                $id = $cuisine['id'];
                $new_cuisine = new Cuisine($name, $id);
                array_push($cuisines, $new_cuisine);
            }
            return $cuisines;
        }

        static function find($search_id)
        {
            $found_cuisine = null;
            $cuisines = Cuisine::getAll();
            foreach ($cuisines as $cuisine) {
                $cuisine_id = $cuisine->getId();
                if ($cuisine_id == $search_id) {
                    $found_cuisine = $cuisine;
                }
            }
            return $found_cuisine;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM cuisines_table;");
        }
    }



 ?>
