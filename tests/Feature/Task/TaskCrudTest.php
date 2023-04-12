<?php

namespace Tests\Feature\Task;

use App\Enums\UserType;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private User $admin;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'type' => UserType::TYPE_ADMIN->value
        ]);
        $this->actingAs($this->admin);
    }
    /**
     * Fetch list tasks successful.
     */
    public function test_fetch_list_tasks_successful(): void
    {
        // seed data
        $this->seed([UserSeeder::class]);

        $tasks = Task::factory(10)->create();

        // call api
        $response = $this->get(route('tasks.index'));

        // assert
        $response->assertStatus(200);
        $response->assertSee($tasks->first()->title);
    }
    /**
     * Create a new task successful.
     */
    public function test_create_tasks_successful(): void
    {
        // seed data
        $data = $this->getData();

        // call api
        $response = $this->post(route('tasks.store'), $data);

        // assert
        $response->assertRedirect(route('tasks.index'));
        $tasks = Task::where('assigned_by_id', $this->admin->id)->get();
        $this->assertCount(1, $tasks);
        $this->assertEquals($tasks->first()->title, $data['title']);
    }

    public function test_create_task_with_required_title_failed_validation()
    {
        // seed data
        $data = $this->getData();
        $data['title'] = null;

        // call api
        $response = $this->post(route('tasks.store'), $data);

        // assert
        $response->assertInvalid(['title']);
        $tasks = Task::where('assigned_by_id', $this->admin->id)->get();
        $this->assertEmpty($tasks);
    }

    private function getData()
    {
        return Task::factory()->make([
            'assigned_to_id' => User::factory()->create(),
            'assigned_by_id' => $this->admin->id
        ])->toArray();
    }
}
