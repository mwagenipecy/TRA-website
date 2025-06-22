<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Institution;
use App\Notifications\CertificateIssued;
use App\Notifications\CertificateRevoked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CertificateService
{
    /**
     * Issue certificate(s) to user(s)
     *
     * @param array $data
     * @param array $users
     * @return array
     */
    public function issueCertificate(array $data, $users)
    {
        $certificates = [];
        
        DB::transaction(function () use ($data, $users, &$certificates) {
            foreach ($users as $userId) {
                $certificate = Certificate::create([
                    'certificate_code' => $this->generateCertificateCode($data),
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'type' => $data['type'],
                    'user_id' => $userId,
                    'event_id' => $data['event_id'] ?? null,
                    'institution_id' => Auth::user()->currentInstitution->id,
                    'issued_by' => Auth::id(),
                    'issue_date' => $data['issue_date'],
                    'expiry_date' => $data['expiry_date'] ?? null,
                    'verification_hash' => $this->generateVerificationHash(),
                    'certificate_data' => $data['certificate_data'] ?? null,
                    'template_used' => $data['template_used'] ?? 'default',
                    'special_notes' => $data['special_notes'] ?? null,
                    'file_path' => $data['file_path'] ?? null,
                    'status' => 'active'
                ]);
                
                // Send notification to recipient
                $user = User::find($userId);
                $user->notify(new CertificateIssued($certificate));
                
                $certificates[] = $certificate;
            }
        });
        
        return $certificates;
    }
    
    /**
     * Revoke a certificate
     *
     * @param Certificate $certificate
     * @param string|null $reason
     * @return void
     */
    public function revokeCertificate(Certificate $certificate, $reason = null)
    {
        DB::transaction(function () use ($certificate, $reason) {
            $certificate->update([
                'status' => 'revoked',
                'revoked_by' => Auth::id(),
                'revoked_at' => now(),
                'revocation_reason' => $reason ?? 'Certificate revoked by ' . Auth::user()->name
            ]);
            
            // Send notification to certificate holder
            $certificate->user->notify(new CertificateRevoked($certificate));
        });
    }
    
    /**
     * Generate certificate PDF (placeholder for future implementation)
     *
     * @param Certificate $certificate
     * @return array
     */
    public function generateCertificatePDF(Certificate $certificate)
    {
        try {
            // This would integrate with a PDF library like TCPDF, DomPDF, or LaravelSnappy
            // For now, we'll return a placeholder implementation
            
            $pdfData = [
                'certificate' => $certificate,
                'recipient' => $certificate->user,
                'institution' => $certificate->institution,
                'issuer' => $certificate->issuer,
                'issue_date' => $certificate->issue_date->format('F d, Y'),
                'verification_url' => route('certificates.verify') . '?code=' . $certificate->certificate_code
            ];
            
            // Future implementation would generate PDF here
            // $pdf = PDF::loadView('certificates.pdf-template', $pdfData);
            // $filename = $certificate->certificate_code . '.pdf';
            // $path = 'certificates/' . $filename;
            // Storage::put($path, $pdf->output());
            
            return [
                'success' => false,
                'message' => 'PDF generation feature will be implemented with a PDF library',
                'data' => $pdfData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify a certificate by code or hash
     *
     * @param string $codeOrHash
     * @return array
     */
    public function verifyCertificate($codeOrHash)
    {
        $certificate = Certificate::with(['user', 'institution', 'event'])
            ->where('certificate_code', $codeOrHash)
            ->orWhere('verification_hash', $codeOrHash)
            ->first();
            
        if (!$certificate) {
            return [
                'status' => 'invalid',
                'certificate' => null,
                'message' => 'Certificate not found'
            ];
        }
        
        if ($certificate->status === 'revoked') {
            return [
                'status' => 'revoked',
                'certificate' => $certificate,
                'message' => 'Certificate has been revoked'
            ];
        }
        
        if ($certificate->isExpired()) {
            return [
                'status' => 'expired',
                'certificate' => $certificate,
                'message' => 'Certificate has expired'
            ];
        }
        
        return [
            'status' => 'valid',
            'certificate' => $certificate,
            'message' => 'Certificate is valid and active'
        ];
    }
    
    /**
     * Generate unique certificate code
     *
     * @param array $data
     * @return string
     */
    private function generateCertificateCode($data)
    {
        $institution = Auth::user()->currentInstitution;
        
        if (!$institution) {
            throw new \Exception('User must belong to an institution to generate certificate code');
        }
        
        $prefix = strtoupper(substr($institution->code, 0, 3));
        $year = Carbon::parse($data['issue_date'])->format('Y');
        $type = strtoupper(substr($data['type'], 0, 3));
        
        // Get next sequential number for this institution and year
        $lastCertificate = Certificate::where('institution_id', $institution->id)
            ->whereYear('issue_date', $year)
            ->orderBy('id', 'desc')
            ->first();
            
        $number = $lastCertificate ? 
            (int) substr($lastCertificate->certificate_code, -4) + 1 : 1;
        
        $number = str_pad($number, 4, '0', STR_PAD_LEFT);
        
        $code = "{$prefix}-{$year}-{$type}-{$number}";
        
        // Ensure uniqueness
        while (Certificate::where('certificate_code', $code)->exists()) {
            $number = str_pad((int) $number + 1, 4, '0', STR_PAD_LEFT);
            $code = "{$prefix}-{$year}-{$type}-{$number}";
        }
        
        return $code;
    }
    
    /**
     * Generate unique verification hash
     *
     * @return string
     */
    private function generateVerificationHash()
    {
        do {
            $hash = hash('sha256', Str::random(40) . time() . Auth::id() . mt_rand());
        } while (Certificate::where('verification_hash', $hash)->exists());
        
        return $hash;
    }
    
    /**
     * Get certificate statistics
     *
     * @param int|null $institutionId
     * @param int|null $year
     * @return array
     */
    public function getCertificateStats($institutionId = null, $year = null)
    {
        $query = Certificate::query();
        
        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }
        
        if ($year) {
            $query->whereYear('issue_date', $year);
        }
        
        $totalQuery = clone $query;
        $activeQuery = clone $query;
        $revokedQuery = clone $query;
        $expiredQuery = clone $query;
        $thisMonthQuery = clone $query;
        $typeQuery = clone $query;
        
        return [
            'total' => $totalQuery->count(),
            'active' => $activeQuery->where('status', 'active')->count(),
            'revoked' => $revokedQuery->where('status', 'revoked')->count(),
            'expired' => $expiredQuery->where('status', 'active')
                                    ->where('expiry_date', '<', now())
                                    ->count(),
            'this_month' => $thisMonthQuery->whereMonth('issue_date', now()->month)
                                          ->whereYear('issue_date', now()->year)
                                          ->count(),
            'by_type' => $typeQuery->groupBy('type')
                                  ->selectRaw('type, count(*) as count')
                                  ->pluck('count', 'type')
                                  ->toArray()
        ];
    }
    
    /**
     * Get monthly certificate issuance data
     *
     * @param int $year
     * @param int|null $institutionId
     * @return array
     */
    public function getMonthlyIssuanceData($year, $institutionId = null)
    {
        $query = Certificate::selectRaw('MONTH(issue_date) as month, COUNT(*) as count')
            ->whereYear('issue_date', $year)
            ->groupBy('month')
            ->orderBy('month');
            
        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }
        
        $data = $query->pluck('count', 'month')->toArray();
        
        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('M');
            $monthlyData[$monthName] = $data[$i] ?? 0;
        }
        
        return $monthlyData;
    }
    
    /**
     * Get top performing institutions by certificate count
     *
     * @param int $year
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopInstitutions($year = null, $limit = 10)
    {
        $query = Certificate::select('institution_id', DB::raw('COUNT(*) as certificate_count'))
            ->with('institution:id,name,code')
            ->groupBy('institution_id')
            ->orderByDesc('certificate_count')
            ->limit($limit);
            
        if ($year) {
            $query->whereYear('issue_date', $year);
        }
        
        return $query->get();
    }
    
    /**
     * Search certificates with advanced filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchCertificates(array $filters = [])
    {
        $query = Certificate::with(['user', 'institution', 'event', 'issuer']);
        
        // Text search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('certificate_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Type filter
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        // Institution filter
        if (!empty($filters['institution_id'])) {
            $query->where('institution_id', $filters['institution_id']);
        }
        
        // Event filter
        if (!empty($filters['event_id'])) {
            $query->where('event_id', $filters['event_id']);
        }
        
        // Year filter
        if (!empty($filters['year'])) {
            $query->whereYear('issue_date', $filters['year']);
        }
        
        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->where('issue_date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->where('issue_date', '<=', $filters['date_to']);
        }
        
        // Expiry filter
        if (!empty($filters['expiry_status'])) {
            switch ($filters['expiry_status']) {
                case 'expired':
                    $query->where('expiry_date', '<', now());
                    break;
                case 'expiring_soon':
                    $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                    break;
                case 'valid':
                    $query->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    });
                    break;
            }
        }
        
        return $query;
    }
    
    /**
     * Bulk operations on certificates
     *
     * @param array $certificateIds
     * @param string $action
     * @param array $data
     * @return array
     */
    public function bulkOperation(array $certificateIds, string $action, array $data = [])
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        DB::transaction(function () use ($certificateIds, $action, $data, &$results) {
            foreach ($certificateIds as $certificateId) {
                try {
                    $certificate = Certificate::findOrFail($certificateId);
                    
                    switch ($action) {
                        case 'revoke':
                            $this->revokeCertificate($certificate, $data['reason'] ?? null);
                            break;
                            
                        case 'extend_expiry':
                            $certificate->update([
                                'expiry_date' => $data['new_expiry_date']
                            ]);
                            break;
                            
                        case 'change_template':
                            $certificate->update([
                                'template_used' => $data['template']
                            ]);
                            break;
                            
                        default:
                            throw new \Exception("Unknown action: {$action}");
                    }
                    
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Certificate {$certificateId}: " . $e->getMessage();
                }
            }
        });
        
        return $results;
    }
    
    /**
     * Generate certificate summary report
     *
     * @param array $filters
     * @return array
     */
    public function generateSummaryReport(array $filters = [])
    {
        $query = $this->searchCertificates($filters);
        
        $certificates = $query->get();
        
        return [
            'total_certificates' => $certificates->count(),
            'by_status' => $certificates->groupBy('status')->map->count(),
            'by_type' => $certificates->groupBy('type')->map->count(),
            'by_institution' => $certificates->groupBy('institution.name')->map->count(),
            'by_month' => $certificates->groupBy(function($cert) {
                return $cert->issue_date->format('Y-m');
            })->map->count(),
            'expiry_breakdown' => [
                'no_expiry' => $certificates->whereNull('expiry_date')->count(),
                'valid' => $certificates->where('expiry_date', '>', now())->count(),
                'expired' => $certificates->where('expiry_date', '<', now())->count(),
                'expiring_soon' => $certificates->whereBetween('expiry_date', [now(), now()->addDays(30)])->count()
            ],
            'average_processing_time' => $this->calculateAverageProcessingTime($certificates),
            'top_issuers' => $certificates->groupBy('issuer.name')->map->count()->sortDesc()->take(10)
        ];
    }
    
    /**
     * Calculate average processing time (placeholder)
     *
     * @param \Illuminate\Support\Collection $certificates
     * @return float
     */
    private function calculateAverageProcessingTime($certificates)
    {
        // This would calculate the time between certificate request and issuance
        // For now, return a placeholder value
        return 2.5; // days
    }
}