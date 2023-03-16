<?php

use PHPUnit\Framework\TestCase;

use Morphism\Morphism;

require __DIR__ . "/Mocks/User.php";

class MorphismTest extends TestCase
{
    public function setUp(){
        $this->data          = array(
            "name"      => "Iron Man",
            "firstName" => "Tony",
            "lastName"  => "Stark",
            "address" => array(
                "city"    => "New York City",
                "country" => "USA"
            ),
            "phoneNumber" => array(
                array(
                    "type"   => "home",
                    "number" => "212 555-1234"
                ),
                array(
                    "type"   => "mobile",
                    "number" => "646 555-4567"
                )
            )
        );
    }


    public function testActionStringPath(){
        $schema = array(
            "city" => "address.city"
        );

        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result->city, "New York City");

    }

    public function testActionFunctionPath(){
        $schema = array(
            "city" => function($data){
                return strtolower($data["address"]["city"]);
            }
        );
        
        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result->city, "new york city"); 
    }

    public function testActionAgregatorPath(){
        $schema = array(
            "fullName" => array(
                "firstName", "lastName"
            )
        );
        
        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result->fullName, "Tony Stark"); 
    }

    public function testActionFunctionObjectPath(){
        $schema = array(
            "city" => (object) array(
                "path" => "address.city",
                "fn"   => function($city) {
                    return strtolower($city);
                }
            )
        );
        
        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result->city, "new york city"); 
    }
}