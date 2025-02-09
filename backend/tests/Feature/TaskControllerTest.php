<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;
use App\Models\Category;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(User $user)
    {
        $token = JWTAuth::fromUser($user);
        return ['Authorization' => "Bearer $token"];
    }

    public function test_it_returns_tasks_for_authenticated_user()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);

        Task::factory()->count(5)->create(['user_id' => $user->id]);

        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks', $headers);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

  
    public function test_it_returns_paginated_tasks()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);

        Task::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks', $headers);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'next_page_url', 'prev_page_url'])
            ->assertJsonCount(10, 'data');
    }

    public function test_it_creates_a_task_successfully()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);
        $category = Category::factory()->create();

        $taskData = [
            'title' => 'My Task',
            'description' => 'This is a test task',
            'due_date' => now()->addDays(2)->toDateString(),
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/tasks', $taskData, $headers);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task created successfully',
            ])
            ->assertJsonPath('task.title', 'My Task');

        $this->assertDatabaseHas('tasks', [
            'title' => 'My Task',
            'description' => 'This is a test task',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_it_updates_a_task_successfully()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);
        $category = Category::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'due_date' => now()->addDays(3)->toDateString(),
            'category_id' => $category->id,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData, $headers);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task updated successfully',
            ])
            ->assertJsonPath('task.title', 'Updated Task Title')
            ->assertJsonPath('task.description', 'Updated description')
            ->assertJsonPath('task.due_date', $updatedData['due_date'])
            ->assertJsonPath('task.category_id', $category->id);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'due_date' => $updatedData['due_date'],
            'category_id' => $category->id,
        ]);
    } 
    public function test_it_deletes_a_task_successfully()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);
        $category = Category::factory()->create();
    
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $headers);
    
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully',
            ]);
    
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_it_returns_unauthorized_error_when_deleting_other_users_task()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $headers = $this->authenticate($user1);
        $category = Category::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user2->id,
            'category_id' => $category->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $headers);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }
}
