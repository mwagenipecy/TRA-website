<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'certificate_code',
        'title',
        'description',
        'type',
        'user_id',
        'event_id',
        'institution_id',
        'issued_by',
        'issue_date',
        'expiry_date',
        'status',
        'verification_hash',
        'certificate_data',
        'template_used',
        'special_notes',
        'file_path',
        'revoked_by',
        'revoked_at',
        'revocation_reason',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'certificate_data' => 'array',
        'revoked_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            if (empty($certificate->certificate_code)) {
                $certificate->certificate_code = self::generateCertificateCode();
            }
            
            if (empty($certificate->verification_hash)) {
                $certificate->verification_hash = self::generateVerificationHash();
            }
            
            if (empty($certificate->issue_date)) {
                $certificate->issue_date = now()->toDateString();
            }
        });
    }

    /**
     * Generate unique certificate code.
     */
    private static function generateCertificateCode(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastCertificate = self::whereYear('created_at', $year)->latest('id')->first();
        $sequence = $lastCertificate ? $lastCertificate->id + 1 : 1;
        
        return "CERT-{$year}{$month}-" . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique verification hash.
     */
    private static function generateVerificationHash(): string
    {
        do {
            $hash = Str::random(32);
        } while (self::where('verification_hash', $hash)->exists());
        
        return $hash;
    }

    /**
     * Get the user who received the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event related to this certificate.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the institution that issued the certificate.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the user who issued the certificate.
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who revoked the certificate.
     */
    public function revoker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Check if certificate is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    /**
     * Check if certificate is revoked.
     */
    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    /**
     * Check if certificate is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now()->toDateString();
    }

    /**
     * Get certificate type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'completion' => 'Certificate of Completion',
            'participation' => 'Certificate of Participation',
            'achievement' => 'Certificate of Achievement',
            'recognition' => 'Certificate of Recognition',
            default => 'Certificate',
        };
    }

    /**
     * Get certificate status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }

        return match ($this->status) {
            'active' => 'Active',
            'revoked' => 'Revoked',
            'expired' => 'Expired',
            default => 'Unknown',
        };
    }

    /**
     * Get the certificate file URL.
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        
        return null;
    }

    /**
     * Get verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('public.certificates.verify', $this->verification_hash);
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        if ($this->expiry_date < now()->toDateString()) {
            return 0;
        }

        return now()->diffInDays($this->expiry_date);
    }

    /**
     * Revoke the certificate.
     */
    public function revoke(?User $revoker = null, ?string $reason = null): void
    {
        $this->update([
            'status' => 'revoked',
            'revoked_by' => $revoker?->id ?? auth()->id(),
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);

        Activity::log(
            'certificate_revoked',
            "Certificate '{$this->certificate_code}' was revoked",
            $revoker ?? auth()->user(),
            $this,
            $this->institution
        );
    }

    /**
     * Reactivate the certificate.
     */
    public function reactivate(): void
    {
        if ($this->isRevoked()) {
            $this->update([
                'status' => 'active',
                'revoked_by' => null,
                'revoked_at' => null,
                'revocation_reason' => null,
            ]);
        }
    }

    /**
     * Check if certificate can be downloaded.
     */
    public function canBeDownloaded(): bool
    {
        return $this->isActive() && $this->file_path && file_exists(storage_path('app/public/' . $this->file_path));
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active certificates.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            });
    }

    /**
     * Scope to get expired certificates.
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now()->toDateString());
    }

    /**
     * Scope to filter by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by institution.
     */
    public function scopeByInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope to search certificates.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('certificate_code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}