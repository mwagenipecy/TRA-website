<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class SystemSettings extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';
    
    // General Settings
    public $appName;
    public $appUrl;
    public $appTimezone;
    public $appDescription;
    public $contactEmail;
    public $contactPhone;
    public $logo;
    public $favicon;
    
    // Email Settings
    public $mailDriver;
    public $mailHost;
    public $mailPort;
    public $mailUsername;
    public $mailPassword;
    public $mailEncryption;
    public $mailFromAddress;
    public $mailFromName;
    public $testEmail;
    
    // System Settings
    public $maintenanceMode = false;
    public $debugMode = false;
    public $cacheEnabled = true;
    public $sessionLifetime;
    public $maxFileUploadSize;
    public $allowedFileTypes;
    public $userRegistration = true;
    public $emailVerification = true;
    public $twoFactorAuth = false;
    
    // Notification Settings
    public $emailNotifications = true;
    public $pushNotifications = false;
    public $smsNotifications = false;
    public $slackWebhook;
    public $discordWebhook;
    
    // Security Settings
    public $passwordMinLength = 8;
    public $passwordRequireSpecial = true;
    public $passwordRequireNumbers = true;
    public $passwordRequireUppercase = true;
    public $maxLoginAttempts = 5;
    public $lockoutDuration = 15;
    public $sessionTimeout = 120;
    
    // Backup Settings
    public $autoBackup = true;
    public $backupFrequency = 'daily';
    public $backupRetention = 30;
    public $backupStorage = 'local';

    public function mount()
    {
        $this->loadSettings();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function loadSettings()
    {
        // General Settings
        $this->appName = config('app.name', 'TRA Tax Club Management');
        $this->appUrl = config('app.url', 'http://localhost');
        $this->appTimezone = config('app.timezone', 'Africa/Dar_es_Salaam');
        $this->appDescription = config('app.description', 'Tanzania Revenue Authority Tax Club Management System');
        $this->contactEmail = config('mail.from.address', 'admin@tra.go.tz');
        $this->contactPhone = config('app.contact_phone', '+255 22 211 7000');
        
        // Email Settings
        $this->mailDriver = config('mail.default', 'smtp');
        $this->mailHost = config('mail.mailers.smtp.host', 'smtp.gmail.com');
        $this->mailPort = config('mail.mailers.smtp.port', 587);
        $this->mailUsername = config('mail.mailers.smtp.username', '');
        $this->mailEncryption = config('mail.mailers.smtp.encryption', 'tls');
        $this->mailFromAddress = config('mail.from.address', '');
        $this->mailFromName = config('mail.from.name', '');
        
        // System Settings
        $this->debugMode = config('app.debug', false);
        $this->sessionLifetime = config('session.lifetime', 120);
        $this->maxFileUploadSize = ini_get('upload_max_filesize');
        $this->allowedFileTypes = 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx';
        
        // Load from cache or database
        $settings = Cache::get('system_settings', []);
        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function saveGeneralSettings()
    {
        $this->validate([
            'appName' => 'required|string|max:255',
            'appUrl' => 'required|url',
            'contactEmail' => 'required|email',
            'contactPhone' => 'nullable|string|max:20',
        ]);

        $settings = [
            'app_name' => $this->appName,
            'app_url' => $this->appUrl,
            'app_timezone' => $this->appTimezone,
            'app_description' => $this->appDescription,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'General settings saved successfully!');
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'mailHost' => 'required|string',
            'mailPort' => 'required|integer',
            'mailFromAddress' => 'required|email',
            'mailFromName' => 'required|string',
        ]);

        $settings = [
            'mail_driver' => $this->mailDriver,
            'mail_host' => $this->mailHost,
            'mail_port' => $this->mailPort,
            'mail_username' => $this->mailUsername,
            'mail_password' => $this->mailPassword,
            'mail_encryption' => $this->mailEncryption,
            'mail_from_address' => $this->mailFromAddress,
            'mail_from_name' => $this->mailFromName,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'Email settings saved successfully!');
    }

    public function saveSystemSettings()
    {
        $this->validate([
            'sessionLifetime' => 'required|integer|min:5|max:1440',
            'maxFileUploadSize' => 'nullable|string',
            'allowedFileTypes' => 'nullable|string',
        ]);

        $settings = [
            'maintenance_mode' => $this->maintenanceMode,
            'debug_mode' => $this->debugMode,
            'cache_enabled' => $this->cacheEnabled,
            'session_lifetime' => $this->sessionLifetime,
            'max_file_upload_size' => $this->maxFileUploadSize,
            'allowed_file_types' => $this->allowedFileTypes,
            'user_registration' => $this->userRegistration,
            'email_verification' => $this->emailVerification,
            'two_factor_auth' => $this->twoFactorAuth,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'System settings saved successfully!');
    }

    public function saveNotificationSettings()
    {
        $settings = [
            'email_notifications' => $this->emailNotifications,
            'push_notifications' => $this->pushNotifications,
            'sms_notifications' => $this->smsNotifications,
            'slack_webhook' => $this->slackWebhook,
            'discord_webhook' => $this->discordWebhook,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'Notification settings saved successfully!');
    }

    public function saveSecuritySettings()
    {
        $this->validate([
            'passwordMinLength' => 'required|integer|min:6|max:20',
            'maxLoginAttempts' => 'required|integer|min:3|max:10',
            'lockoutDuration' => 'required|integer|min:5|max:60',
            'sessionTimeout' => 'required|integer|min:15|max:480',
        ]);

        $settings = [
            'password_min_length' => $this->passwordMinLength,
            'password_require_special' => $this->passwordRequireSpecial,
            'password_require_numbers' => $this->passwordRequireNumbers,
            'password_require_uppercase' => $this->passwordRequireUppercase,
            'max_login_attempts' => $this->maxLoginAttempts,
            'lockout_duration' => $this->lockoutDuration,
            'session_timeout' => $this->sessionTimeout,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'Security settings saved successfully!');
    }

    public function saveBackupSettings()
    {
        $this->validate([
            'backupRetention' => 'required|integer|min:1|max:365',
        ]);

        $settings = [
            'auto_backup' => $this->autoBackup,
            'backup_frequency' => $this->backupFrequency,
            'backup_retention' => $this->backupRetention,
            'backup_storage' => $this->backupStorage,
        ];

        $this->saveSettings($settings);
        session()->flash('message', 'Backup settings saved successfully!');
    }

    public function saveSettings($settings)
    {
        $existingSettings = Cache::get('system_settings', []);
        $mergedSettings = array_merge($existingSettings, $settings);
        Cache::put('system_settings', $mergedSettings, now()->addDays(30));
        
        // Also save to database if you have a settings table
        // foreach ($settings as $key => $value) {
        //     Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        // }
    }

    public function uploadLogo()
    {
        $this->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $path = $this->logo->store('logos', 'public');
        $this->saveSettings(['logo_path' => $path]);
        session()->flash('message', 'Logo uploaded successfully!');
        $this->logo = null;
    }

    public function uploadFavicon()
    {
        $this->validate([
            'favicon' => 'required|image|max:512',
        ]);

        $path = $this->favicon->store('favicon', 'public');
        $this->saveSettings(['favicon_path' => $path]);
        session()->flash('message', 'Favicon uploaded successfully!');
        $this->favicon = null;
    }

    public function testEmailConfiguration()
    {
        $this->validate([
            'testEmail' => 'required|email',
        ]);

        try {
            Mail::to($this->testEmail)->send(new TestEmail());
            session()->flash('message', 'Test email sent successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            session()->flash('message', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function optimizeSystem()
    {
        try {
            Artisan::call('optimize');
            session()->flash('message', 'System optimized successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to optimize system: ' . $e->getMessage());
        }
    }

    public function runBackup()
    {
        try {
            Artisan::call('backup:run');
            session()->flash('message', 'Backup completed successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to run backup: ' . $e->getMessage());
        }
    }

    public function toggleMaintenanceMode()
    {
        try {
            if ($this->maintenanceMode) {
                Artisan::call('up');
                $this->maintenanceMode = false;
                session()->flash('message', 'Maintenance mode disabled!');
            } else {
                Artisan::call('down');
                $this->maintenanceMode = true;
                session()->flash('message', 'Maintenance mode enabled!');
            }
            
            $this->saveSettings(['maintenance_mode' => $this->maintenanceMode]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to toggle maintenance mode: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}