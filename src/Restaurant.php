<?php
    class Restaurant
    {
        private $restaurant_name;
        private $phone;
        private $address;
        private $website;
        private $cuisine_id;
        private $id;


        function __construct ($restaurant_name, $phone, $address, $website, $cuisine_id, $id = null)
        {
            $this->restaurant_name = $restaurant_name;
            $this->phone = $phone;
            $this->address = $address;
            $this->website = $website;
            $this->cuisine_id = $cuisine_id;
            $this->id = $id;
        }

        function setRestaurantName($new_restaurant_name)
        {
            $this->restaurant_name = (string) $new_restaurant_name;
        }

        function setPhone($new_phone)
        {
            $this->phone = (string) $new_phone;
        }

        function setAddress($new_address)
        {
            $this->address = (string) $new_address;
        }

        function setWebsite($new_website)
        {
            $this->website = (string) $new_website;
        }

        function getRestaurantName()
        {
            return $this->restaurant_name;
        }

        function getPhone()
        {
            return $this->phone;
        }

        function getAddress()
        {
            return $this->address;
        }

        function getWebsite()
        {
            return $this->website;
        }

        function getCuisineId()
        {
            return $this->cuisine_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO restaurants_table (restaurant_name, phone, address, website, cuisine_id) VALUES ('{$this->getRestaurantName()}', '{$this->getPhone()}', '{$this->getAddress()}', '{$this->getWebsite()}', {$this->getCuisineId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($column_to_update, $new_information)
        {
            $GLOBALS['DB']->exec("UPDATE restaurants_table SET {$column_to_update}= '{$new_information}' WHERE id = {$this->getId()};");
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM restaurants_table WHERE id ={$this->getId()};");
        }

        static function getAll()
        {
            $db_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants_table;");
            $restaurants = array();
            foreach($db_restaurants as $restaurant) {
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

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM restaurants_table;");
        }

        static function find($search_id)
        {
            $found_restaurant = null;
            $restaurants = Restaurant::getAll();
            foreach ($restaurants as $restaurant) {
                $restaurant_id = $restaurant->getId();
                if ($restaurant_id == $search_id) {
                    $found_restaurant = $restaurant;
                }
            }
            return $found_restaurant;
        }
    }
 ?>
