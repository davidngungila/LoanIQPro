<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Two Factor Authentication Routes
Route::get('/2fa/challenge', [TwoFactorController::class, 'create'])->name('2fa.challenge')->middleware('auth');
Route::post('/2fa/verify', [TwoFactorController::class, 'store'])->name('2fa.verify')->middleware('auth');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend')->middleware('auth');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Super Admin Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/global', [DashboardController::class, 'superAdminDashboard'])->name('global');
    Route::get('/kpis', [DashboardController::class, 'systemKpis'])->name('kpis');
    Route::get('/analytics', [DashboardController::class, 'multiBranchAnalytics'])->name('analytics');
});

// Customer Routes
Route::prefix('customers')->name('customers.')->middleware(['auth', 'role:super-admin,admin,loan-officer'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Customers\CustomerController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Customers\CustomerController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Customers\CustomerController::class, 'store'])->name('store');
    
    // Specific routes must come before parameterized routes
    Route::get('/kyc', [\App\Http\Controllers\Customers\CustomerController::class, 'kyc'])->name('kyc');
    Route::get('/segmentation', [\App\Http\Controllers\Customers\CustomerController::class, 'segmentation'])->name('segmentation');
    
    Route::post('/{customer}/toggle-status', [\App\Http\Controllers\Customers\CustomerController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{customer}/verify-kyc', [\App\Http\Controllers\Customers\CustomerController::class, 'verifyKyc'])->name('verify-kyc');
    
    // Parameterized routes must come last
    Route::get('/{customer}', [\App\Http\Controllers\Customers\CustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [\App\Http\Controllers\Customers\CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [\App\Http\Controllers\Customers\CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [\App\Http\Controllers\Customers\CustomerController::class, 'destroy'])->name('destroy');
});

// Loan Routes
Route::prefix('loans')->name('loans.')->middleware(['auth', 'role:super-admin,admin,loan-officer'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Loans\LoanController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Loans\LoanController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Loans\LoanController::class, 'store'])->name('store');
    
    // Specific routes must come before parameterized routes
    Route::get('/applications', [\App\Http\Controllers\Loans\LoanController::class, 'applications'])->name('applications');
    Route::get('/approval', [\App\Http\Controllers\Loans\LoanController::class, 'approval'])->name('approval');
    Route::get('/disbursement', [\App\Http\Controllers\Loans\LoanController::class, 'disbursement'])->name('disbursement');
    Route::get('/repayments', [\App\Http\Controllers\Loans\LoanController::class, 'repayments'])->name('repayments');
    
    Route::post('/{loan}/approve', [\App\Http\Controllers\Loans\LoanController::class, 'approve'])->name('approve');
    Route::post('/{loan}/reject', [\App\Http\Controllers\Loans\LoanController::class, 'reject'])->name('reject');
    Route::post('/{loan}/disburse', [\App\Http\Controllers\Loans\LoanController::class, 'disburse'])->name('disburse');
    
    // Parameterized routes must come last
    Route::get('/{loan}', [\App\Http\Controllers\Loans\LoanController::class, 'show'])->name('show');
    Route::get('/{loan}/edit', [\App\Http\Controllers\Loans\LoanController::class, 'edit'])->name('edit');
    Route::put('/{loan}', [\App\Http\Controllers\Loans\LoanController::class, 'update'])->name('update');
    Route::delete('/{loan}', [\App\Http\Controllers\Loans\LoanController::class, 'destroy'])->name('destroy');
});

// Disbursement Routes
Route::prefix('disbursements')->name('disbursements.')->middleware(['auth', 'role:super-admin,admin,loan-officer,accountant'])->group(function () {
    Route::get('/queue', [\App\Http\Controllers\Disbursements\DisbursementController::class, 'queue'])->name('queue');
    Route::get('/bulk', [\App\Http\Controllers\Disbursements\DisbursementController::class, 'bulk'])->name('bulk');
    Route::post('/bulk-process', [\App\Http\Controllers\Disbursements\DisbursementController::class, 'bulkProcess'])->name('bulk-process');
});

// Repayments Routes
Route::prefix('repayments')->name('repayments.')->middleware(['auth', 'role:super-admin,admin,loan-officer,accountant,collector'])->group(function () {
    Route::get('/tracking', [\App\Http\Controllers\Repayments\RepaymentController::class, 'tracking'])->name('tracking');
    Route::get('/schedules', [\App\Http\Controllers\Repayments\RepaymentController::class, 'schedules'])->name('schedules');
    Route::get('/overdue', [\App\Http\Controllers\Repayments\RepaymentController::class, 'overdue'])->name('overdue');
    Route::post('/record-payment', [\App\Http\Controllers\Repayments\RepaymentController::class, 'recordPayment'])->name('record-payment');
});

// Collections Routes
Route::prefix('collections')->name('collections.')->middleware(['auth', 'role:super-admin,admin,collector'])->group(function () {
    Route::get('/recovery', [\App\Http\Controllers\Collections\CollectionController::class, 'recovery'])->name('recovery');
    Route::get('/collectors', [\App\Http\Controllers\Collections\CollectionController::class, 'collectors'])->name('collectors');
    Route::get('/reports', [\App\Http\Controllers\Collections\CollectionController::class, 'reports'])->name('reports');
    Route::post('/assign-collector', [\App\Http\Controllers\Collections\CollectionController::class, 'assignCollector'])->name('assign-collector');
});

// Accounting Routes
Route::prefix('accounting')->name('accounting.')->middleware(['auth', 'role:super-admin,admin,accountant'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Accounting\AccountingController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [\App\Http\Controllers\Accounting\AccountingController::class, 'transactions'])->name('transactions');
    Route::get('/reports', [\App\Http\Controllers\Accounting\AccountingController::class, 'reports'])->name('reports');
    Route::get('/reconciliation', [\App\Http\Controllers\Accounting\AccountingController::class, 'reconciliation'])->name('reconciliation');
});

// User & Role Management Routes
Route::prefix('users')->name('users.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Users\UserController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Users\UserController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Users\UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [\App\Http\Controllers\Users\UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [\App\Http\Controllers\Users\UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [\App\Http\Controllers\Users\UserController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/toggle-status', [\App\Http\Controllers\Users\UserController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/{user}', [\App\Http\Controllers\Users\UserController::class, 'show'])->name('show');
});

Route::prefix('roles')->name('roles.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Users\RoleController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Users\RoleController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Users\RoleController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [\App\Http\Controllers\Users\RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [\App\Http\Controllers\Users\RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [\App\Http\Controllers\Users\RoleController::class, 'destroy'])->name('destroy');
    Route::get('/{role}', [\App\Http\Controllers\Users\RoleController::class, 'show'])->name('show');
});

Route::prefix('activity-logs')->name('activity-logs.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Users\ActivityLogController::class, 'index'])->name('index');
    Route::get('/{log}', [\App\Http\Controllers\Users\ActivityLogController::class, 'show'])->name('show');
    Route::delete('/{log}', [\App\Http\Controllers\Users\ActivityLogController::class, 'destroy'])->name('destroy');
    Route::delete('/', [\App\Http\Controllers\Users\ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');
    Route::get('/export', [\App\Http\Controllers\Users\ActivityLogController::class, 'export'])->name('export');
    Route::post('/cleanup', [\App\Http\Controllers\Users\ActivityLogController::class, 'cleanup'])->name('cleanup');
});
Route::prefix('organization')->name('organization.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/branches', [\App\Http\Controllers\Organization\BranchController::class, 'index'])->name('branches.index');
    Route::get('/branches/create', [\App\Http\Controllers\Organization\BranchController::class, 'create'])->name('branches.create');
    Route::post('/branches', [\App\Http\Controllers\Organization\BranchController::class, 'store'])->name('branches.store');
    Route::get('/branches/{branch}/edit', [\App\Http\Controllers\Organization\BranchController::class, 'edit'])->name('branches.edit');
    Route::put('/branches/{branch}', [\App\Http\Controllers\Organization\BranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [\App\Http\Controllers\Organization\BranchController::class, 'destroy'])->name('branches.destroy');
    
    Route::get('/departments', [\App\Http\Controllers\Organization\DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [\App\Http\Controllers\Organization\DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [\App\Http\Controllers\Organization\DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [\App\Http\Controllers\Organization\DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [\App\Http\Controllers\Organization\DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [\App\Http\Controllers\Organization\DepartmentController::class, 'destroy'])->name('departments.destroy');
    
    Route::get('/staff', [\App\Http\Controllers\Organization\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [\App\Http\Controllers\Organization\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [\App\Http\Controllers\Organization\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{user}/edit', [\App\Http\Controllers\Organization\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{user}', [\App\Http\Controllers\Organization\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{user}', [\App\Http\Controllers\Organization\StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/staff/{user}/toggle-status', [\App\Http\Controllers\Organization\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
});

// Admin Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/branch', [DashboardController::class, 'adminDashboard'])->name('branch');
    Route::get('/staff', function () { return view('dashboard.admin.staff'); })->name('staff');
});

// Loan Officer Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:loan-officer'])->group(function () {
    Route::get('/my-loans', [DashboardController::class, 'loanOfficerDashboard'])->name('my-loans');
    Route::get('/pending', function () { return view('dashboard.loan-officer.pending'); })->name('pending');
});

// Accountant Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:accountant'])->group(function () {
    Route::get('/financial', [DashboardController::class, 'accountantDashboard'])->name('financial');
});

// Collector Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:collector'])->group(function () {
    Route::get('/cases', [DashboardController::class, 'collectorDashboard'])->name('cases');
    Route::get('/progress', function () { return view('dashboard.collector.progress'); })->name('progress');
});

// Auditor Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:auditor'])->group(function () {
    Route::get('/compliance', [DashboardController::class, 'auditorDashboard'])->name('compliance');
});

// Customer Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/summary', [DashboardController::class, 'customerDashboard'])->name('summary');
    Route::get('/payment', function () { return view('dashboard.customer.payment'); })->name('payment');
});

// Guarantor Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:guarantor'])->group(function () {
    Route::get('/guaranteed', [DashboardController::class, 'guarantorDashboard'])->name('guaranteed');
});

// IT Support Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:it-support'])->group(function () {
    Route::get('/health', [DashboardController::class, 'itSupportDashboard'])->name('health');
});

// Integrations Routes
Route::prefix('integrations')->name('integrations.')->middleware(['auth', 'role:super-admin,admin,it-support'])->group(function () {
    Route::get('/payments', [\App\Http\Controllers\Integrations\IntegrationController::class, 'payments'])->name('payments');
    Route::get('/communication', [\App\Http\Controllers\Integrations\IntegrationController::class, 'communication'])->name('communication');
    Route::get('/credit', [\App\Http\Controllers\Integrations\IntegrationController::class, 'credit'])->name('credit');
});

// Security Routes
Route::prefix('security')->name('security.')->middleware(['auth', 'role:super-admin,admin,auditor,it-support'])->group(function () {
    Route::get('/audit', [\App\Http\Controllers\Security\SecurityController::class, 'audit'])->name('audit');
    Route::get('/fraud', [\App\Http\Controllers\Security\SecurityController::class, 'fraud'])->name('fraud');
    Route::get('/aml', [\App\Http\Controllers\Security\SecurityController::class, 'aml'])->name('aml');
});

// Settings Routes
Route::prefix('settings')->name('settings.')->middleware(['auth', 'role:super-admin,admin'])->group(function () {
    Route::get('/loans', [\App\Http\Controllers\Settings\SettingsController::class, 'loans'])->name('loans');
    Route::get('/interest', [\App\Http\Controllers\Settings\SettingsController::class, 'interest'])->name('interest');
    Route::get('/backup', [\App\Http\Controllers\Settings\SettingsController::class, 'backup'])->name('backup');
});

// Risk & Credit Assessment Routes
Route::prefix('risk')->name('risk.')->middleware(['auth', 'role:super-admin,admin,loan-officer,auditor'])->group(function () {
    Route::get('/credit-scoring', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'creditScoring'])->name('credit-scoring');
    Route::get('/risk-analysis', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'riskAnalysis'])->name('risk-analysis');
    Route::get('/portfolio-risk', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'portfolioRisk'])->name('portfolio-risk');
    Route::get('/loan-approval', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'loanApproval'])->name('loan-approval');
    Route::get('/risk-reports', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'riskReports'])->name('risk-reports');
    Route::get('/performance-reports', [\App\Http\Controllers\Risk\RiskAssessmentController::class, 'performanceReports'])->name('performance-reports');
});

// Document Management Routes
Route::prefix('documents')->name('documents.')->middleware(['auth', 'role:super-admin,admin,loan-officer,document-manager'])->group(function () {
    Route::get('/loan-agreements', [\App\Http\Controllers\Documents\DocumentController::class, 'loanAgreements'])->name('loan-agreements');
    Route::get('/customer-documents', [\App\Http\Controllers\Documents\DocumentController::class, 'customerDocuments'])->name('customer-documents');
    Route::get('/digital-signatures', [\App\Http\Controllers\Documents\DocumentController::class, 'digitalSignatures'])->name('digital-signatures');
    Route::get('/document-storage', [\App\Http\Controllers\Documents\DocumentController::class, 'documentStorage'])->name('document-storage');
    Route::post('/upload', [\App\Http\Controllers\Documents\DocumentController::class, 'uploadDocument'])->name('upload');
    Route::post('/sign', [\App\Http\Controllers\Documents\DocumentController::class, 'signDocument'])->name('sign');
    Route::get('/download/{id}', [\App\Http\Controllers\Documents\DocumentController::class, 'downloadDocument'])->name('download');
});

// Default route
Route::get('/', function () {
    return redirect()->route('login');
});
