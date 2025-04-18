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
            'average'         => '15',
            'first_name'    => 'Admin',
            'email'         => 'admin@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $teacher = User::create([
            'last_name'     => 'Teacher',
            'average'         => '20',
            'first_name'    => 'Teacher',
            'email'         => 'teacher@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $user = User::create([
            'last_name'     => 'Student',
            'cohort_id'         => '0',
            'average'         => '5',
            'first_name'    => 'Student',
            'email'         => 'student@codingfacttttory.com',
            'password'      => Hash::make('123456'),

        ] );
        $student1 = User::create([
            'last_name'     => 'Martin',
            'first_name'    => 'Lucas',
            'cohort_id'         => '0',
            'email'         => 'lucas.martin@codingfactory.com',
            'average'       => 12.4,
            'password'      => Hash::make('123456'),
        ]);

        $student2 = User::create([
            'last_name'     => 'Durand',
            'first_name'    => 'Chloé',
            'email'         => 'chloe.durand@codingfactory.com',
            'average'       => 14.8,
            'cohort_id'         => '0',
            'password'      => Hash::make('123456'),
        ]);

        $student3 = User::create([
            'last_name'     => 'Bernard',
            'first_name'    => 'Hugo',
            'cohort_id'         => '0',
            'email'         => 'hugo.bernard@codingfactory.com',
            'average'       => 9.5,
            'password'      => Hash::make('123456'),
        ]);

        $student4 = User::create([
            'last_name'     => 'Petit',
            'first_name'    => 'Emma',
            'cohort_id'         => '0',
            'email'         => 'emma.petit@codingfactory.com',
            'average'       => 16.2,
            'password'      => Hash::make('123456'),
        ]);

        $student5 = User::create([
            'last_name'     => 'Robert',
            'first_name'    => 'Léo',
            'cohort_id'         => '0',
            'email'         => 'leo.robert@codingfactory.com',
            'average'       => 11.1,
            'password'      => Hash::make('123456'),
        ]);

        $student6 = User::create([
            'cohort_id'         => '0',
            'last_name'     => 'Richard',
            'first_name'    => 'Camille',
            'email'         => 'camille.richard@codingfactory.com',
            'average'       => 17.6,
            'password'      => Hash::make('123456'),
        ]);

        $student7 = User::create([
            'last_name'     => 'Dubois',
            'first_name'    => 'Noah',
            'email'         => 'noah.dubois@codingfactory.com',
            'average'       => 13.9,
            'cohort_id'         => '1',
            'password'      => Hash::make('123456'),
        ]);

        $student8 = User::create([
            'last_name'     => 'Lefevre',
            'first_name'    => 'Jade',
            'cohort_id'         => '1',
            'email'         => 'jade.lefevre@codingfactory.com',
            'average'       => 15.7,
            'password'      => Hash::make('123456'),
        ]);

        $student9 = User::create([
            'last_name'     => 'Moreau',
            'first_name'    => 'Tom',
            'email'         => 'tom.moreau@codingfactory.com',
            'cohort_id'         => '1',
            'average'       => 10.3,
            'password'      => Hash::make('123456'),
        ]);

        $student10 = User::create([
            'last_name'     => 'Simon',
            'first_name'    => 'Inès',
            'email'         => 'ines.simon@codingfactory.com',
            'average'       => 18.0,
            'cohort_id'         => '1',
            'password'      => Hash::make('123456'),
        ]);


        $user = User::create([

            'last_name'     => 'Adam',
            'first_name'    => 'Honvault',
            'email'         => 'adam@honvault.com',
            'average'         => '10',
            'cohort_id'         => '1',
            'password'      => Hash::make('123456'),
        ]);
        // Create the default school
        $school = School::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);
        $cohort = Cohort::create([
            'school_id'   => $school->id,
            'name'      => 'Coding Factory',
            'description'   => 'Cergy',

        ]);
        $cohort = Cohort::create([
            'school_id'   => $school->id,
            'name'      => 'Coding Factory',
            'description'   => 'Paris',

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
