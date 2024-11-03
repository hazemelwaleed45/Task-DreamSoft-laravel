Rate Limiting
To prevent abuse, the API has a rate limit of 100 requests per hour per IP address. This is configured in Laravel’s Throttle middleware.

Security
The API employs IP whitelisting to restrict access. Only requests from the specified IP addresses will be allowed. You can manage the list of allowed IPs using environment variables.

Caching
List Departments: Cached for 10 minutes.
Count Employees by Department: Cached for 5 minutes.
Endpoints
1. List Departments
Endpoint: Route::get('/departments', [DepartmentController::class, 'index']);
Description: Retrieves a list of all departments.
Response:
Status Code: 200 OK
Body:

{
    "data": [
        {
            "id": 1,
            "name": "Marketing"
        },
        {
            "id": 2,
            "name": "Engineering"
        }
    ]
}
Caching: Cached for 10 minutes. and expired if any update in database .


2. List Employees by Department
Endpoint: GET /departments/{id}/employees
Description: Retrieves all employees belonging to the specified department.
Response:
Status Code: 200 OK
Body:
{
    "data": [
        {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "phone_number": "1234567890",
            "hire_date": "2024-11-03",
            "salary": 50000,
            "department_id": 1
        }
    ]
}


3. Add a New Employee
Endpoint: POST /employees
Description: Creates a new employee record.
Request Body:
{
    "first_name": "Jane",
    "last_name": "Doe",
    "email": "jane.doe@example.com",
    "phone_number": "0987654321",
    "hire_date": "2024-11-03",
    "salary": 60000,
    "department_id": 1
}
Response:
Status Code: 201 Created
Body:
{
    "data": {
        "id": 2,
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane.doe@example.com",
        "phone_number": "0987654321",
        "hire_date": "2024-11-03",
        "salary": 60000,
        "department_id": 1
    }
}
Validation: Checks for unique email and positive salary.


4. Update Employee Details
Endpoint: PUT /employees/{id}
Description: Updates an existing employee record.
Request Body:
{
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane.smith@example.com",
    "phone_number": "0987654321",
    "hire_date": "2024-11-03",
    "salary": 65000,
    "department_id": 1
}
Response:
Status Code: 200 OK
Body:
{
    "data": {
        "id": 2,
        "first_name": "Jane",
        "last_name": "Smith",
        "email": "jane.smith@example.com",
        "phone_number": "0987654321",
        "hire_date": "2024-11-03",
        "salary": 65000,
        "department_id": 1
    }
}


5. Delete an Employee
Endpoint: DELETE /employees/{id}
Description: Deletes an employee record.
Response:
Status Code: 204 No Content


6. Count Employees by Department
Endpoint: GET /departments/employees/count
Description: Retrieves the total number of employees in each department, ordered by department name.
Response:
Status Code: 200 OK
Body:
{
    "data": [
        {
            "department": "Marketing",
            "employee_count": 10
        },
        {
            "department": "Engineering",
            "employee_count": 20
        }
    ]
}
Caching: Cached for 5 minutes. and expired if any update in database .



7. Highest Salary Information
Endpoint: GET /employees/highest-salary
Description: Retrieves the highest salary across all employees, along with the employee's name, department, and hire date.
Response:
Status Code: 200 OK
Body:
{
    "data": {
        "name": "John Doe",
        "salary": 120000,
        "department": "Engineering",
        "hire_date": "2022-05-12"
    }
}


8. Average Salary by Department
Endpoint: GET /departments/average-salary
Description: Calculates and returns the average salary for each department.
Response:
Status Code: 200 OK
Body:
{
    "data": [
        {
            "department": "Marketing",
            "average_salary": 55000
        },
        {
            "department": "Engineering",
            "average_salary": 75000
        }
    ]
}


Error Handling
All endpoints will return an appropriate HTTP status code and an error message in the response body in case of failure.

Common Error Responses
422 Unprocessable Entity: Validation errors for input data.
403 Forbidden: Unauthorized access due to insufficient permissions or unauthorized IP.
404 Not Found: Resource not found (e.g., employee or department does not exist).
500 Internal Server Error: An unexpected error occurred on the server.
