<?php
namespace tests\unitTests;
require_once 'autoloader.php';

use PHPUnit\Framework\TestCase;
use modell\User;

final class UserTest extends TestCase
{
    
    public function testUserEqualsStrict(){
        $userA = new User('userA', 'passwordA', 'emailA', false, false);
        $userB = new User('userA', 'passwordA', 'emailA', false, false);
        $this->assertTrue( $userA->equalsStrict($userB) );
    }
    
    public function testUserNotEqualsStrict(){
        $userA = new User('userA', 'passwordA', 'emailA', false, false);
        $userB = new User('userB', 'passwordB', 'emailB', false, false);
        $this->assertFalse( $userA->equalsStrict($userB) );
    }
    
    public function testUserEquals(){
        $userA = new User('userA');
        $userB = new User('userA');
        $this->assertTrue( $userA->equals($userB) );
    }
    
    public function testUserNotEquals(){
        $userA = new User('userA');
        $userB = new User('userB');
        $this->assertFalse( $userA->equals($userB) );
    }
    
}

?>

