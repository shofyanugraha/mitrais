<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
class AuthenticationTest extends TestCase
{
  use DatabaseTransactions;
  

  /**
   * A basic test example.
   *
   * @return void
   */
  public function testRegisterSuccess()
  {
    // create a faker with Indonesian 
    $faker = \Faker\Factory::create('id_ID');
    
    $params = [];
    $params['first_name'] = $faker->firstName();
    $params['last_name'] = $faker->lastName();
    $params['phone'] = $faker->phoneNumber();
    $params['email'] = $faker->safeEmail();
    $params['sex'] = 100;
    $params['date_of_birth'] = $faker->date();

    
    $this->post('/register', $params, []);
    $this->seeStatusCode(200);

    // see json structure
    $this->seeJsonStructure(
          ['data' =>
            [
              'first_name',
              'last_name',
              'phone',
              'email',
              'date_of_birth',
              'sex',
              'created_at',
              'updated_at',
            ]
          ]    
        );
        
    $this->seeInDatabase('users', $params);
    
  }

  public function testRegisterFirstNameEmpty()
  {
    // create a faker with Indonesian 
    $faker = \Faker\Factory::create('id_ID');
    
    $params = [];
    $params['last_name'] = $faker->lastName();
    $params['phone'] = $faker->phoneNumber();
    $params['email'] = $faker->safeEmail();
    $params['sex'] = 100;
    $params['date_of_birth'] = $faker->date();

    $this->post('/register', $params, []);

    $this->seeStatusCode(422);

    $this->seeJsonStructure(
      [
        'message',
        'status',
        'error'
      ]    
    );

    $this->seeJson(['status'=>false]);
    
  }

  public function testRegisterLastNameEmpty()
  {
    // create a faker with Indonesian 
    $faker = \Faker\Factory::create('id_ID');
    
    $params = [];
    $params['first_name'] = $faker->firstName();
    $params['phone'] = $faker->phoneNumber();
    $params['email'] = $faker->safeEmail();
    $params['sex'] = 100;
    $params['date_of_birth'] = $faker->date();

    $this->post('/register', $params, []);

    $this->seeStatusCode(422);

    $this->seeJsonStructure(
      [
        'message',
        'status',
        'error'
      ]    
    );

    $this->seeJson(['status'=>false]);
    
  }

  public function testRegisterSexAndDOBEmpty()
  {
    // create a faker with Indonesian 
    $faker = \Faker\Factory::create('id_ID');
    
    $params = [];
    $params['first_name'] = $faker->firstName();
    $params['last_name'] = $faker->lastName();
    $params['phone'] = $faker->phoneNumber();
    $params['email'] = $faker->safeEmail();

    $this->post('/register', $params, []);

    $this->seeStatusCode(200);

    // see json structure
    $this->seeJsonStructure(
          ['data' =>
            [
              'first_name',
              'last_name',
              'phone',
              'email',
              'date_of_birth',
              'sex',
              'created_at',
              'updated_at',
            ]
          ]    
        );
        
    $this->seeInDatabase('users', $params);
    
  }


  public function testRegisterEmailExisted()
  {
    // create a faker with Indonesian 
    $faker = \Faker\Factory::create('id_ID');
    
    $params = [];
    $params['first_name'] = $faker->firstName();
    $params['last_name'] = $faker->lastName();
    $params['phone'] = $faker->phoneNumber();
    $params['email'] = $faker->safeEmail();
    $params['sex'] = 100;
    $params['date_of_birth'] = $faker->date();

    $this->post('/register', $params, []);
    $this->post('/register', $params, []);

    $this->seeStatusCode(422);

    $this->seeJsonStructure(
      [
        'message',
        'status',
        'error'
      ]    
    );

    $this->seeJson(['status'=>false]);
    
  }

  
}
