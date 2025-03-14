<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Users
        User::factory()->create([
            'first_name' => 'Mohamed',
            'last_name' => 'Abu El-Nour',
            'email' => 'moabuelnour@programmer.net',
            'password' => Hash::make('password'),
        ]);
        $users = User::factory()->count(5)->create();

        // Create Projects
        $projects = Project::factory()->count(3)->create();

        // Assign Users to Projects (Many-to-Many)
        foreach ($projects as $project) {
            $project->users()->attach($users->random(rand(1, 3))->pluck('id'));
        }

        // Create Attributes
        $attributes = [
            ['name' => 'Department', 'type' => 'select', 'options' => json_encode(['IT', 'HR', 'Finance'])],
            ['name' => 'Start Date', 'type' => 'date'],
            ['name' => 'Budget', 'type' => 'number'],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }

        // Assign Attribute Values to Projects
        foreach ($projects as $project) {
            AttributeValue::create([
                'attribute_id' => 1, // Department
                'entity_id' => $project->id,
                'value' => 'IT',
            ]);
            AttributeValue::create([
                'attribute_id' => 2, // Start Date
                'entity_id' => $project->id,
                'value' => now()->subDays(rand(1, 100))->toDateString(),
            ]);
            AttributeValue::create([
                'attribute_id' => 3, // Budget
                'entity_id' => $project->id,
                'value' => rand(10000, 50000),
            ]);
        }

        // Create Timesheets & Assign to Users/Projects
        foreach ($users as $user) {
            Timesheet::factory()->count(5)->create([
                'user_id' => $user->id,
                'project_id' => $projects->random()->id,
            ]);
        }
    }
}
