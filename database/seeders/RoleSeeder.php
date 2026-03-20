<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access and control',
                'color' => '#ef4444',
                'level' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Admin / Branch Manager',
                'slug' => 'admin',
                'description' => 'Branch-level management and oversight',
                'color' => '#f59e0b',
                'level' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Loan Officer',
                'slug' => 'loan-officer',
                'description' => 'Loan processing and customer management',
                'color' => '#3b82f6',
                'level' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Accountant / Finance Officer',
                'slug' => 'accountant',
                'description' => 'Financial management and accounting',
                'color' => '#10b981',
                'level' => 70,
                'is_active' => true,
            ],
            [
                'name' => 'Collector / Recovery Officer',
                'slug' => 'collector',
                'description' => 'Loan recovery and collections management',
                'color' => '#8b5cf6',
                'level' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Auditor / Compliance Officer',
                'slug' => 'auditor',
                'description' => 'Audit and compliance monitoring',
                'color' => '#06b6d4',
                'level' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Customer (Client / Borrower)',
                'slug' => 'customer',
                'description' => 'Loan customers and borrowers',
                'color' => '#84cc16',
                'level' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Guarantor',
                'slug' => 'guarantor',
                'description' => 'Loan guarantors with limited access',
                'color' => '#f97316',
                'level' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'IT Support / System Support',
                'slug' => 'it-support',
                'description' => 'Technical support and system maintenance',
                'color' => '#6366f1',
                'level' => 85,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
