<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\Group;
use App\Models\School;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the default user
        $admin = User::create([
            'last_name'     => 'Admin',
            'first_name'    => 'Admin',
            'email'         => 'admin@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $teacher = User::create([
            'last_name'     => 'Teacher',
            'first_name'    => 'Teacher',
            'email'         => 'teacher@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $user = User::create([
            'last_name'     => 'Student',
            'first_name'    => 'Student',
            'email'         => 'student@codingfacttttory.com',
            'password'      => Hash::make('123456'),

        ] );
        $group = Group::create([
           // Assuming you already have a school with ID 1
        ]);

        // Assign students with IDs 1, 2, and 3 to the group
        $group->users()->attach([1, 2, 3]);  // Attaching students to the group using their user IDs

        $user = User::create([

            'last_name'     => 'Adam',
            'first_name'    => 'Honvault',
            'email'         => 'adam@honvault.com',
            'password'      => Hash::make('123456'),
        ]);
        // Create the default school
        $school = School::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);
        $cohort = Cohort::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);

        // Create the admin role
        UserSchool::create([
            'user_id'   => $admin->id,
            'school_id' => $school->id,
            'role'      => 'admin'
        ]);

        // Create the teacher role
        UserSchool::create([
            'user_id'   => $teacher->id,
            'school_id' => $school->id,
            'role'      => 'teacher'
        ]);

        // Create the student role
        UserSchool::create([
            'user_id'   => $user->id,
            'school_id' => $school->id,
            'role'      => 'student'
        ]);
    }
}
