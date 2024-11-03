<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Department;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }
    public function test_employee_count_by_department()
    {
        
        Department::factory()->count(5)->create();

        $response = $this->getJson('/api/departments/employees/count');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['department_id', 'total']
        ]);
    }

    public function test_average_salary_for_departments()
    {
        Department::factory()->create(['id' => 1]);
        $response = $this->getJson('/api/departments/average-salary');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['department_id', 'average_salary']
        ]);
    }
}
