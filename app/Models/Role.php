<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'description', 'color', 'level', 'is_active'])]
class Role extends Model
{
    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Get sidebar menu items for this role.
     */
    public function getSidebarMenu(): array
    {
        return match($this->slug) {
            'super-admin' => $this->getSuperAdminMenu(),
            'admin' => $this->getAdminMenu(),
            'loan-officer' => $this->getLoanOfficerMenu(),
            'accountant' => $this->getAccountantMenu(),
            'collector' => $this->getCollectorMenu(),
            'auditor' => $this->getAuditorMenu(),
            'customer' => $this->getCustomerMenu(),
            'guarantor' => $this->getGuarantorMenu(),
            'it-support' => $this->getItSupportMenu(),
            default => []
        };
    }

    private function getSuperAdminMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'route' => 'dashboard.global',
                'children' => [
                    ['title' => 'Global Overview', 'route' => 'dashboard.global'],
                    ['title' => 'System KPIs', 'route' => 'dashboard.kpis'],
                    ['title' => 'Multi-branch Analytics', 'route' => 'dashboard.analytics'],
                ]
            ],
            [
                'title' => 'Organization Management',
                'icon' => 'building',
                'children' => [
                    ['title' => 'Branches', 'route' => 'organization.branches.index'],
                    ['title' => 'Departments', 'route' => 'organization.departments.index'],
                    ['title' => 'Staff Management', 'route' => 'organization.staff.index'],
                ]
            ],
            [
                'title' => 'User & Role Management',
                'icon' => 'users',
                'children' => [
                    ['title' => 'All Users', 'route' => 'users.index'],
                    ['title' => 'Roles & Permissions', 'route' => 'roles.index'],
                    ['title' => 'Activity Logs', 'route' => 'activity-logs.index'],
                ]
            ],
            [
                'title' => 'Customers',
                'icon' => 'user-check',
                'children' => [
                    ['title' => 'All Customers', 'route' => 'customers.index'],
                    ['title' => 'KYC Verification', 'route' => 'customers.kyc'],
                    ['title' => 'Customer Segmentation', 'route' => 'customers.segmentation'],
                ]
            ],
            [
                'title' => 'Loans',
                'icon' => 'hand-coins',
                'children' => [
                    ['title' => 'All Loans', 'route' => 'loans.index'],
                    ['title' => 'Loan Applications', 'route' => 'loans.applications'],
                    ['title' => 'Loan Approval', 'route' => 'loans.approval'],
                    ['title' => 'Disbursement', 'route' => 'loans.disbursement'],
                    ['title' => 'Repayment Tracking', 'route' => 'loans.repayments'],
                ]
            ],
            [
                'title' => 'Disbursement',
                'icon' => 'send-horizontal',
                'children' => [
                    ['title' => 'Disbursement Queue', 'route' => 'disbursements.queue'],
                    ['title' => 'Bulk Disbursement', 'route' => 'disbursements.bulk'],
                ]
            ],
            [
                'title' => 'Repayments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Payment Tracking', 'route' => 'repayments.tracking'],
                    ['title' => 'Schedules', 'route' => 'repayments.schedules'],
                    ['title' => 'Overdue Loans', 'route' => 'repayments.overdue'],
                ]
            ],
            [
                'title' => 'Collections',
                'icon' => 'shield-check',
                'children' => [
                    ['title' => 'Recovery Management', 'route' => 'collections.recovery'],
                    ['title' => 'Assigned Collectors', 'route' => 'collections.collectors'],
                    ['title' => 'Recovery Reports', 'route' => 'collections.reports'],
                ]
            ],
            [
                'title' => 'Accounting',
                'icon' => 'calculator',
                'children' => [
                    ['title' => 'Dashboard', 'route' => 'accounting.dashboard'],
                    ['title' => 'Transactions', 'route' => 'accounting.transactions'],
                    ['title' => 'Bank Reconciliation', 'route' => 'accounting.reconciliation'],
                    ['title' => 'Financial Reports', 'route' => 'accounting.reports'],
                ]
            ],
            [
                'title' => 'Reports & Analytics',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'Financial Reports', 'route' => 'accounting.reports'],
                    ['title' => 'Collection Reports', 'route' => 'collections.reports'],
                    ['title' => 'Repayment Reports', 'route' => 'repayments.schedules'],
                    ['title' => 'Activity Logs', 'route' => 'activity-logs.index'],
                ]
            ],
            [
                'title' => 'Document Management',
                'icon' => 'document-text',
                'children' => [
                    ['title' => 'Loan Agreements', 'route' => 'documents.loan-agreements'],
                    ['title' => 'Customer Documents', 'route' => 'documents.customer-documents'],
                    ['title' => 'Digital Signatures', 'route' => 'documents.digital-signatures'],
                    ['title' => 'Document Storage', 'route' => 'documents.document-storage'],
                ]
            ],
            [
                'title' => 'Risk & Credit Assessment',
                'icon' => 'shield-check',
                'children' => [
                    ['title' => 'Credit Scoring', 'route' => 'risk.credit-scoring'],
                    ['title' => 'Risk Analysis', 'route' => 'risk.risk-analysis'],
                    ['title' => 'Portfolio at Risk', 'route' => 'risk.portfolio-risk'],
                    ['title' => 'Loan Approval', 'route' => 'risk.loan-approval'],
                    ['title' => 'Risk Reports', 'route' => 'risk.risk-reports'],
                    ['title' => 'Performance Reports', 'route' => 'risk.performance-reports'],
                ]
            ],
            [
                'title' => 'Integrations',
                'icon' => 'plug',
                'children' => [
                    ['title' => 'Payment Gateways', 'route' => 'integrations.payments'],
                    ['title' => 'SMS/Email APIs', 'route' => 'integrations.communication'],
                    ['title' => 'Credit Bureau APIs', 'route' => 'integrations.credit'],
                ]
            ],
            [
                'title' => 'Security & Compliance',
                'icon' => 'shield',
                'children' => [
                    ['title' => 'Audit Logs', 'route' => 'security.audit'],
                    ['title' => 'Fraud Detection', 'route' => 'security.fraud'],
                    ['title' => 'AML Reports', 'route' => 'security.aml'],
                ]
            ],
            [
                'title' => 'System Settings',
                'icon' => 'settings',
                'children' => [
                    ['title' => 'Loan Settings', 'route' => 'settings.loans'],
                    ['title' => 'Interest & Penalties', 'route' => 'settings.interest'],
                    ['title' => 'Backup & Restore', 'route' => 'settings.backup'],
                ]
            ],
        ];
    }

    private function getAdminMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Branch Overview', 'route' => 'dashboard.branch'],
                    ['title' => 'Staff Performance', 'route' => 'dashboard.staff'],
                ]
            ],
            [
                'title' => 'Customers',
                'icon' => 'user-check',
                'children' => [
                    ['title' => 'Customer List', 'route' => 'customers.index'],
                    ['title' => 'KYC Approval', 'route' => 'customers.kyc-approval'],
                ]
            ],
            [
                'title' => 'Loans',
                'icon' => 'hand-coins',
                'children' => [
                    ['title' => 'Loan Applications', 'route' => 'loans.applications'],
                    ['title' => 'Approvals', 'route' => 'loans.approvals'],
                    ['title' => 'Active Loans', 'route' => 'loans.active'],
                ]
            ],
            [
                'title' => 'Disbursement',
                'icon' => 'send-horizontal',
                'children' => [
                    ['title' => 'Approve Disbursement', 'route' => 'disbursements.approve'],
                ]
            ],
            [
                'title' => 'Repayments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Payment Tracking', 'route' => 'repayments.tracking'],
                    ['title' => 'Overdue Monitoring', 'route' => 'repayments.overdue'],
                ]
            ],
            [
                'title' => 'Collections',
                'icon' => 'shield-check',
                'children' => [
                    ['title' => 'Assign Collectors', 'route' => 'collections.assign'],
                    ['title' => 'Recovery Tracking', 'route' => 'collections.tracking'],
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'Branch Reports', 'route' => 'reports.branch'],
                    ['title' => 'Loan Performance', 'route' => 'reports.loans'],
                ]
            ],
            [
                'title' => 'Staff',
                'icon' => 'users',
                'children' => [
                    ['title' => 'Loan Officers', 'route' => 'staff.loan-officers'],
                    ['title' => 'Collectors', 'route' => 'staff.collectors'],
                ]
            ],
        ];
    }

    private function getLoanOfficerMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'My Loans', 'route' => 'dashboard.my-loans'],
                    ['title' => 'Pending Applications', 'route' => 'dashboard.pending'],
                ]
            ],
            [
                'title' => 'Customers',
                'icon' => 'user-check',
                'children' => [
                    ['title' => 'Register Customer', 'route' => 'customers.register'],
                    ['title' => 'View Profiles', 'route' => 'customers.profiles'],
                ]
            ],
            [
                'title' => 'Loans',
                'icon' => 'hand-coins',
                'children' => [
                    ['title' => 'Apply Loan', 'route' => 'loans.apply'],
                    ['title' => 'Review Applications', 'route' => 'loans.review'],
                    ['title' => 'Recommend Approval', 'route' => 'loans.recommend'],
                ]
            ],
            [
                'title' => 'Credit Assessment',
                'icon' => 'clipboard-check',
                'children' => [
                    ['title' => 'Customer Scoring', 'route' => 'credit.scoring'],
                    ['title' => 'Risk Analysis', 'route' => 'credit.risk'],
                ]
            ],
            [
                'title' => 'Repayments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Payment Schedule', 'route' => 'repayments.schedule'],
                    ['title' => 'Customer Payment History', 'route' => 'repayments.history'],
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'My Performance', 'route' => 'reports.performance'],
                    ['title' => 'Loan Status Reports', 'route' => 'reports.loan-status'],
                ]
            ],
            [
                'title' => 'Notifications',
                'icon' => 'bell',
                'children' => [
                    ['title' => 'Alerts & Reminders', 'route' => 'notifications.alerts'],
                ]
            ],
        ];
    }

    private function getAccountantMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Financial Overview', 'route' => 'dashboard.financial'],
                ]
            ],
            [
                'title' => 'Disbursement',
                'icon' => 'send-horizontal',
                'children' => [
                    ['title' => 'Process Payments', 'route' => 'disbursements.process'],
                    ['title' => 'Disbursement Records', 'route' => 'disbursements.records'],
                ]
            ],
            [
                'title' => 'Repayments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Record Payments', 'route' => 'repayments.record'],
                    ['title' => 'Reconcile Transactions', 'route' => 'repayments.reconcile'],
                ]
            ],
            [
                'title' => 'Accounting',
                'icon' => 'calculator',
                'children' => [
                    ['title' => 'Ledger', 'route' => 'accounting.ledger'],
                    ['title' => 'Journals', 'route' => 'accounting.journals'],
                    ['title' => 'Expenses', 'route' => 'accounting.expenses'],
                    ['title' => 'Income Tracking', 'route' => 'accounting.income'],
                ]
            ],
            [
                'title' => 'Banking',
                'icon' => 'landmark',
                'children' => [
                    ['title' => 'Bank Reconciliation', 'route' => 'banking.reconciliation'],
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'Profit & Loss', 'route' => 'reports.profit-loss'],
                    ['title' => 'Cash Flow', 'route' => 'reports.cash-flow'],
                    ['title' => 'Balance Sheet', 'route' => 'reports.balance-sheet'],
                ]
            ],
        ];
    }

    private function getCollectorMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Assigned Cases', 'route' => 'dashboard.cases'],
                    ['title' => 'Recovery Progress', 'route' => 'dashboard.progress'],
                ]
            ],
            [
                'title' => 'Collections',
                'icon' => 'shield-check',
                'children' => [
                    ['title' => 'Overdue Loans', 'route' => 'collections.overdue'],
                    ['title' => 'Assigned Customers', 'route' => 'collections.customers'],
                    ['title' => 'Field Tracking', 'route' => 'collections.field'],
                    ['title' => 'Visit Logs', 'route' => 'collections.visits'],
                    ['title' => 'Customer Notes', 'route' => 'collections.notes'],
                ]
            ],
            [
                'title' => 'Payments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Record Collected Payments', 'route' => 'payments.record'],
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'Recovery Performance', 'route' => 'reports.recovery'],
                    ['title' => 'Collection Reports', 'route' => 'reports.collection'],
                ]
            ],
        ];
    }

    private function getAuditorMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Compliance Overview', 'route' => 'dashboard.compliance'],
                ]
            ],
            [
                'title' => 'Audit',
                'icon' => 'search',
                'children' => [
                    ['title' => 'Transaction Logs', 'route' => 'audit.transactions'],
                    ['title' => 'Loan Audit Trail', 'route' => 'audit.loans'],
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bar-chart',
                'children' => [
                    ['title' => 'Regulatory Reports', 'route' => 'reports.regulatory'],
                    ['title' => 'AML Reports', 'route' => 'reports.aml'],
                    ['title' => 'Risk Analysis', 'route' => 'reports.risk-analysis'],
                ]
            ],
            [
                'title' => 'Monitoring',
                'icon' => 'eye',
                'children' => [
                    ['title' => 'Suspicious Activities', 'route' => 'monitoring.suspicious'],
                    ['title' => 'Fraud Alerts', 'route' => 'monitoring.fraud'],
                ]
            ],
        ];
    }

    private function getCustomerMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Loan Summary', 'route' => 'dashboard.summary'],
                    ['title' => 'Next Payment', 'route' => 'dashboard.payment'],
                ]
            ],
            [
                'title' => 'Loans',
                'icon' => 'hand-coins',
                'children' => [
                    ['title' => 'Apply Loan', 'route' => 'loans.apply'],
                    ['title' => 'Loan Status', 'route' => 'loans.status'],
                    ['title' => 'Loan History', 'route' => 'loans.history'],
                ]
            ],
            [
                'title' => 'Repayments',
                'icon' => 'receipt',
                'children' => [
                    ['title' => 'Payment Schedule', 'route' => 'repayments.schedule'],
                    ['title' => 'Make Payment', 'route' => 'repayments.pay'],
                    ['title' => 'Payment History', 'route' => 'repayments.history'],
                ]
            ],
            [
                'title' => 'Documents',
                'icon' => 'file-text',
                'children' => [
                    ['title' => 'Upload KYC', 'route' => 'documents.kyc'],
                    ['title' => 'Download Statements', 'route' => 'documents.statements'],
                ]
            ],
            [
                'title' => 'Support',
                'icon' => 'help-circle',
                'children' => [
                    ['title' => 'Chat / Help Desk', 'route' => 'support.chat'],
                ]
            ],
            [
                'title' => 'Notifications',
                'icon' => 'bell',
                'children' => [
                    ['title' => 'Alerts & Reminders', 'route' => 'notifications.alerts'],
                ]
            ],
        ];
    }

    private function getGuarantorMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'Guaranteed Loans Overview', 'route' => 'dashboard.guaranteed'],
                ]
            ],
            [
                'title' => 'Loans',
                'icon' => 'hand-coins',
                'children' => [
                    ['title' => 'Loans You Guaranteed', 'route' => 'loans.guaranteed'],
                    ['title' => 'Status Tracking', 'route' => 'loans.tracking'],
                ]
            ],
            [
                'title' => 'Documents',
                'icon' => 'file-text',
                'children' => [
                    ['title' => 'Upload Agreements', 'route' => 'documents.agreements'],
                ]
            ],
            [
                'title' => 'Notifications',
                'icon' => 'bell',
                'children' => [
                    ['title' => 'Alerts on defaults', 'route' => 'notifications.defaults'],
                ]
            ],
        ];
    }

    private function getItSupportMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'children' => [
                    ['title' => 'System Health', 'route' => 'dashboard.health'],
                ]
            ],
            [
                'title' => 'Users',
                'icon' => 'users',
                'children' => [
                    ['title' => 'Manage Access Issues', 'route' => 'users.access'],
                ]
            ],
            [
                'title' => 'Logs',
                'icon' => 'file-text',
                'children' => [
                    ['title' => 'Error Logs', 'route' => 'logs.errors'],
                    ['title' => 'System Logs', 'route' => 'logs.system'],
                ]
            ],
            [
                'title' => 'Maintenance',
                'icon' => 'wrench',
                'children' => [
                    ['title' => 'Backup', 'route' => 'maintenance.backup'],
                    ['title' => 'Updates', 'route' => 'maintenance.updates'],
                ]
            ],
            [
                'title' => 'Security',
                'icon' => 'shield',
                'children' => [
                    ['title' => 'Monitor Access', 'route' => 'security.access'],
                    ['title' => 'Threat Detection', 'route' => 'security.threats'],
                ]
            ],
        ];
    }
}
