<?php

use PHPUnit\Framework\TestCase;

use Morphism\Morphism;

class MorphismArrayTest extends TestCase
{
    public function setUp(){
        $this->data          = array(
            array(
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
            ),
            array(
                "name"      => "Spiderman",
                "firstName" => "Peter",
                "lastName"  => "Parker",
                "address" => array(
                    "city"    => "New York City",
                    "country" => "USA"
                ),
                "phoneNumber" => array(
                    array(
                        "type"   => "home",
                        "number" => "293 093-2321"
                    )
                )
            )
        );
    }


    public function testActionArrayStringPath()
    {
        $schema = array(
            "city" => "address.city"
        );

        Morphism::setMapper("User", $schema);
        
        $result = Morphism::map("User", $this->data);
    
        $this->assertEquals(count($result), 2);
        $this->assertEquals($result[0]->city, "New York City");
    }


    public function testActionFunctionPath(){
        $schema = array(
            "city" => function($data){
                return strtolower($data["address"]["city"]);
            }
        );
        
        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result[0]->city, "new york city");
        $this->assertEquals($result[1]->city, "new york city");
    }

    public function testActionAgregatorPath(){
        $schema = array(
            "fullName" => array(
                "firstName", "lastName"
            )
        );
        
        Morphism::setMapper("User", $schema);

        $result = Morphism::map("User", $this->data);

        $this->assertEquals($result[0]->fullName, "Tony Stark"); 
        $this->assertEquals($result[1]->fullName, "Peter Parker"); 
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

        $this->assertEquals($result[0]->city, "new york city");
        $this->assertEquals($result[1]->city, "new york city");
    }

}