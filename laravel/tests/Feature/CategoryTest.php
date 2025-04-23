<?php

namespace Tests\Unit;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use function Psy\debug;

class CategoryTest extends TestCase
{
    /**
     * Test ID: Category-001
     * Description: Verify that the GET /api/categories endpoint returns a successful response.
     * Precondition: None
     * Test Steps:
     *  1. Send a GET request to /api/categories
     *  2. Assert that the response status is 200
     * Test Data: None
     * Expected Result: The response status should be 200
     * Actual Result: Response returned 200
     * Status: PASSED
     * Remark: None
     */
    public function test_if_we_can_access_get_all_categories_api(): void
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
    }

    /**
     * Test ID: Category-002
     * Description: Verify that we can create new categories using the POST /api/categories endpoint.
     * Precondition: None
     * Test Steps:
     *  1. Send a POST request with a valid category name
     *  2. Assert that the response status is 201
     *  3. Assert that the correct success message and category name is returned
     * Test Data:
     *          1. name: test_category_01
     *          2. name: test_category_02
     *          3. name: test_category_03
     * Expected Result: The response status should be 201 and the category should be created
     * Actual Result: Response returned 201 and the category was created
     * Status: PASSED
     * Remark: None
     */
    public function test_if_we_can_access_create_category_api(): void
    {
        $response = $this->postJson('/api/categories', [
            "name" => "test_category_01"
        ]);
        $response->assertStatus(201);
        $response->assertJson(["message" => "Category created successfully"]);
        $response->assertSee("test_category_01");

        $response = $this->postJson('/api/categories', [
            "name" => "test_category_02"
        ]);
        $response->assertStatus(201);
        $response->assertJson(["message" => "Category created successfully"]);
        $response->assertSee("test_category_02");

        $response = $this->postJson('/api/categories', [
            "name" => "test_category_03"
        ]);
        $response->assertStatus(201);
        $response->assertJson(["message" => "Category created successfully"]);
        $response->assertSee("test_category_03");
    }

    /**
     * Test ID: Category-003
     * Description: Verify that we can retrieve a specific category by its ID using the GET /api/categories/{id} endpoint.
     * Precondition: A category must exist in the database
     * Test Steps:
     *  1. Create a category
     *  2. Send a GET request with the category ID
     *  3. Assert that the response status is 200 and contains the correct category data
     * Test Data: name: CategoryToGet
     * Expected Result: The response should contain the created category with status 200
     * Actual Result: Response returned 200 and contained correct category data
     * Status: PASSED
     * Remark: None
     */
    public function test_if_we_can_access_get_a_category_by_id_api(): void
    {
        $category = \App\Models\Category::create([
            'name' => 'CategoryToGet'
        ]);
    
        $response = $this->get("/api/categories/{$category->id}");
        $response->assertStatus(200)->assertJsonFragment([
            "id" => $category->id,
            "name" => "CategoryToGet"
        ]);
    }
    /**
     * Test ID: Category-004
     * Description: Verify that a category can be updated by its ID using the PATCH /api/categories/{id} endpoint.
     * Precondition: A category with ID 2 should exist
     * Test Steps:
     *  1. Send a PATCH request to update the category
     *  2. Assert that the response status is 200
     *  3. Verify that the returned data includes the updated name
     * Test Data: name: test_category_updated
     * Expected Result: Status should be 200 and data should reflect the update
     * Actual Result: Response returned 200 and data was updated
     * Status: PASSED
     * Remark: None
     */
    public function test_if_we_can_access_update_a_category_by_id_api(): void
    {
        $response = $this->patch('/api/categories/2', ["name" => "test_category_updated"]);
        $response->assertStatus(200)->assertSee([
            "id" => 2,
            "name" => "test_category_updated"
        ]);
    }

    /**
     * Test ID: Category-005
     * Description: Verify that a category can be deleted by its ID using the DELETE /api/categories/{id} endpoint.
     * Precondition: A category must exist in the database
     * Test Steps:
     *  1. Create a category
     *  2. Send a DELETE request to remove it
     *  3. Assert that the response status is 200 with a success message
     *  4. Try deleting and retrieving the category again to ensure it's removed
     * Test Data: name: CategoryToDelete
     * Expected Result: The category is deleted, and subsequent operations return 404
     * Actual Result: Delete returned 200 and second delete/get returned 404
     * Status: PASSED
     * Remark: None
     */
    public function test_if_we_can_access_delete_a_category_by_id_api(): void
    {
        $category = \App\Models\Category::create([
            'name' => 'CategoryToDelete'
        ]);
    
        $response = $this->delete("/api/categories/{$category->id}");
        $response->assertStatus(200)->assertJson([
            "message" => 'Category deleted successfully'
        ]);
    
        // Try deleting again
        $request = $this->delete("/api/categories/{$category->id}");
        $request->assertStatus(404)->assertJson([
            "message" => 'Category not found'
        ]);
    
        // Try fetching deleted category
        $request = $this->get("/api/categories/{$category->id}");
        $request->assertStatus(404);
    }
}