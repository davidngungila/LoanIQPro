<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Customer;

class DocumentController extends Controller
{
    public function loanAgreements()
    {
        $stats = [
            'total_agreements' => 1250,
            'pending_signatures' => 45,
            'signed_today' => 23,
            'digitally_signed' => 1180,
        ];

        $agreements = [
            [
                'id' => 1,
                'loan_id' => 'LN001234',
                'customer' => 'John Doe',
                'loan_amount' => 50000,
                'agreement_type' => 'Personal Loan Agreement',
                'status' => 'signed',
                'signature_type' => 'digital',
                'signed_date' => '2024-03-20',
                'created_date' => '2024-03-15',
            ],
            [
                'id' => 2,
                'loan_id' => 'LN001235',
                'customer' => 'Jane Smith',
                'loan_amount' => 75000,
                'agreement_type' => 'Business Loan Agreement',
                'status' => 'pending_signature',
                'signature_type' => 'digital',
                'signed_date' => null,
                'created_date' => '2024-03-19',
            ],
            [
                'id' => 3,
                'loan_id' => 'LN001236',
                'customer' => 'Mike Johnson',
                'loan_amount' => 100000,
                'agreement_type' => 'Mortgage Agreement',
                'status' => 'draft',
                'signature_type' => 'physical',
                'signed_date' => null,
                'created_date' => '2024-03-18',
            ],
        ];

        return view('documents.loan-agreements', compact('stats', 'agreements'));
    }

    public function customerDocuments()
    {
        $stats = [
            'total_documents' => 3450,
            'uploaded_today' => 67,
            'pending_verification' => 23,
            'verified_documents' => 3200,
        ];

        $documents = [
            [
                'id' => 1,
                'customer' => 'John Doe',
                'document_type' => 'ID Document',
                'file_name' => 'john_doe_id.pdf',
                'file_size' => '2.5 MB',
                'upload_date' => '2024-03-20',
                'status' => 'verified',
                'uploaded_by' => 'John Doe',
            ],
            [
                'id' => 2,
                'customer' => 'Jane Smith',
                'document_type' => 'Proof of Income',
                'file_name' => 'jane_smith_income.pdf',
                'file_size' => '1.8 MB',
                'upload_date' => '2024-03-20',
                'status' => 'pending_verification',
                'uploaded_by' => 'Jane Smith',
            ],
            [
                'id' => 3,
                'customer' => 'Mike Johnson',
                'document_type' => 'Bank Statement',
                'file_name' => 'mike_johnson_bank.pdf',
                'file_size' => '3.2 MB',
                'upload_date' => '2024-03-19',
                'status' => 'verified',
                'uploaded_by' => 'Mike Johnson',
            ],
        ];

        $documentTypes = [
            'ID Document' => 1250,
            'Proof of Income' => 890,
            'Bank Statement' => 750,
            'Collateral Documents' => 320,
            'Other' => 240,
        ];

        return view('documents.customer-documents', compact('stats', 'documents', 'documentTypes'));
    }

    public function digitalSignatures()
    {
        $stats = [
            'total_signatures' => 2100,
            'pending_signatures' => 45,
            'completed_today' => 67,
            'signature_rate' => 94.5,
        ];

        $signatures = [
            [
                'id' => 1,
                'document' => 'Personal Loan Agreement - LN001234',
                'signer' => 'John Doe',
                'signer_email' => 'john.doe@email.com',
                'status' => 'signed',
                'sent_date' => '2024-03-18',
                'signed_date' => '2024-03-20',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Chrome 120.0.0.0',
            ],
            [
                'id' => 2,
                'document' => 'Business Loan Agreement - LN001235',
                'signer' => 'Jane Smith',
                'signer_email' => 'jane.smith@email.com',
                'status' => 'pending',
                'sent_date' => '2024-03-19',
                'signed_date' => null,
                'ip_address' => null,
                'user_agent' => null,
            ],
            [
                'id' => 3,
                'document' => 'Mortgage Agreement - LN001236',
                'signer' => 'Mike Johnson',
                'signer_email' => 'mike.johnson@email.com',
                'status' => 'viewed',
                'sent_date' => '2024-03-17',
                'signed_date' => null,
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Safari 17.0.0.0',
            ],
        ];

        return view('documents.digital-signatures', compact('stats', 'signatures'));
    }

    public function documentStorage()
    {
        $stats = [
            'total_storage_used' => 15.6,
            'total_documents' => 3450,
            'storage_capacity' => 100,
            'encrypted_files' => 3200,
        ];

        $storageByType = [
            'Loan Agreements' => ['count' => 1250, 'size' => 5.2],
            'Customer Documents' => ['count' => 1800, 'size' => 8.4],
            'Collateral Documents' => ['count' => 280, 'size' => 1.5],
            'Legal Documents' => ['count' => 120, 'size' => 0.5],
        ];

        $recentUploads = [
            [
                'id' => 1,
                'file_name' => 'loan_agreement_ln001234.pdf',
                'file_type' => 'Loan Agreement',
                'file_size' => '2.5 MB',
                'upload_date' => '2024-03-20 10:30',
                'uploaded_by' => 'John Doe',
                'encrypted' => true,
            ],
            [
                'id' => 2,
                'file_name' => 'customer_id_jane_smith.pdf',
                'file_type' => 'ID Document',
                'file_size' => '1.8 MB',
                'upload_date' => '2024-03-20 09:45',
                'uploaded_by' => 'Jane Smith',
                'encrypted' => true,
            ],
            [
                'id' => 3,
                'file_name' => 'collateral_mike_johnson.pdf',
                'file_type' => 'Collateral',
                'file_size' => '3.2 MB',
                'upload_date' => '2024-03-20 08:15',
                'uploaded_by' => 'Mike Johnson',
                'encrypted' => true,
            ],
        ];

        return view('documents.document-storage', compact('stats', 'storageByType', 'recentUploads'));
    }

    public function uploadDocument(Request $request)
    {
        // Handle document upload logic
        return response()->json(['message' => 'Document uploaded successfully']);
    }

    public function signDocument(Request $request)
    {
        // Handle digital signature logic
        return response()->json(['message' => 'Document signed successfully']);
    }

    public function downloadDocument($id)
    {
        // Handle secure document download
        return response()->download('path/to/document');
    }
}
