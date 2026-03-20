<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create a default branch
        $branch = Branch::create([
            'name' => 'Head Office',
            'code' => 'HO001',
            'address' => '123 Main Street, City, Country',
            'phone' => '+1234567890',
            'email' => 'headoffice@loanmanager.com',
            'manager_name' => 'System Administrator',
            'is_active' => true,
        ]);

        // Create demo users for each role
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567890',
                'is_active' => true,
                'department' => 'IT',
                'branch_id' => $branch->id,
                'role' => 'super-admin',
            ],
            [
                'name' => 'Branch Manager',
                'email' => 'manager@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567891',
                'is_active' => true,
                'department' => 'Management',
                'branch_id' => $branch->id,
                'role' => 'admin',
            ],
            [
                'name' => 'Loan Officer',
                'email' => 'loanofficer@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567892',
                'is_active' => true,
                'department' => 'Loans',
                'branch_id' => $branch->id,
                'role' => 'loan-officer',
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567893',
                'is_active' => true,
                'department' => 'Finance',
                'branch_id' => $branch->id,
                'role' => 'accountant',
            ],
            [
                'name' => 'Collector',
                'email' => 'collector@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567894',
                'is_active' => true,
                'department' => 'Collections',
                'branch_id' => $branch->id,
                'role' => 'collector',
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567895',
                'is_active' => true,
                'department' => 'Compliance',
                'branch_id' => $branch->id,
                'role' => 'auditor',
            ],
            [
                'name' => 'Demo Customer',
                'email' => 'customer@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567896',
                'is_active' => true,
                'department' => 'Customer',
                'branch_id' => $branch->id,
                'role' => 'customer',
            ],
            [
                'name' => 'IT Support',
                'email' => 'itsupport@demo.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567897',
                'is_active' => true,
                'department' => 'IT',
                'branch_id' => $branch->id,
                'role' => 'it-support',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $roleId = \App\Models\Role::where('slug', $role)->first()->id;
            $user->roles()->attach($roleId);
        }
    }
}
