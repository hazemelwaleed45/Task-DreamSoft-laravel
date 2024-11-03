<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Department;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
   
    use RefreshDatabase;

   
    public function setUp(): void
    {
        parent::setUp();
        
    }
    public function test_create_employee_with_valid_data()
    {
        $this->withoutMiddleware();
        $department = Department::factory()->create();
        
        $data = [
            'first_name' => 'Hazem',
            'last_name' => 'Elwaledd',
            'email' => 'hazemElwaleed@example.com',
            'phone_number' => '123456789',
            'hire_date' => now()->toDateTimeString(),
            'salary' => 50000,
            'department_id' => $department->id
        ];

        $response = $this->postJson('/api/employees', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                        'first_name' => 'Hazem',
                        'last_name' => 'Elwaledd',
                        'email' => 'hazemElwaleed@example.com',
                        'phone_number' => '123456789',
                        'hire_date' => now()->toDateTimeString(),
                        'salary' => 50000,
                        'department_id' => $department->id
                     ]
                 ]);
    }

    public function test_create_employee_with_invalid_data()
    {
        $this->withoutMiddleware();
        $response = $this->postJson('/api/employees', [
            'first_name' => '', 
            'email' => 'not-an-email', 
        ]);

        $response->assertStatus(422);
    }

   
    public function test_fetch_employees_from_non_existent_department()
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/departments/999/employees'); 

        $response->assertStatus(404);
    }

    
    public function test_rate_limiting()
    {
        for ($i = 0; $i < 100; $i++) {
            $response = $this->getJson('/api/employees');
        }
        $response->assertStatus(429); 
    }

    public function test_access_from_unauthorized_ip()
    {
        $this->withHeaders(['REMOTE_ADDR' => '10.0.0.1']) 
             ->getJson('/api/employees')
             ->assertStatus(403)
             ->assertJson(['error' => 'Access denied.']);
    }

}