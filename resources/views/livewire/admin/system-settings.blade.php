<div>

<div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-black border-b border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">System Settings</h1>
                    <p class="text-gray-400 mt-1">Configure application settings and preferences</p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="setActiveTab('backup')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 whitespace-nowrap {{ $activeTab === 'backup' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    <i class="fas fa-database mr-2"></i>
                    Backup
                </button>
            </nav>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-yellow-500 text-black px-6 py-3 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-500 text-white px-6 py-3 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Content -->
    <div class="px-6 py-6">
        @if ($activeTab === 'general')
            <!-- General Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">General Settings</h2>
                    
                    <form wire:submit="saveGeneralSettings">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Application Name *</label>
                                <input wire:model="appName" type="text" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('appName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Application URL *</label>
                                <input wire:model="appUrl" type="url" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('appUrl') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Timezone</label>
                                <select wire:model="appTimezone" 
                                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                    <option value="Africa/Dar_es_Salaam">Africa/Dar es Salaam</option>
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">America/New York</option>
                                    <option value="Europe/London">Europe/London</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Contact Email *</label>
                                <input wire:model="contactEmail" type="email" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('contactEmail') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Contact Phone</label>
                                <input wire:model="contactPhone" type="text" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('contactPhone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-300 text-sm font-medium mb-2">Description</label>
                                <textarea wire:model="appDescription" rows="3"
                                          class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Logo & Favicon Upload -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mt-6">
                    <h3 class="text-lg font-bold text-yellow-400 mb-4">Branding</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Logo</label>
                            <input wire:model="logo" type="file" accept="image/*"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @if ($logo)
                                <button wire:click="uploadLogo" 
                                        class="mt-2 bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-1 rounded text-sm">
                                    Upload Logo
                                </button>
                            @endif
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Favicon</label>
                            <input wire:model="favicon" type="file" accept="image/*"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @if ($favicon)
                                <button wire:click="uploadFavicon" 
                                        class="mt-2 bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-1 rounded text-sm">
                                    Upload Favicon
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @elseif ($activeTab === 'email')
            <!-- Email Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">Email Configuration</h2>
                    
                    <form wire:submit="saveEmailSettings">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Mail Driver</label>
                                <select wire:model="mailDriver" 
                                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                    <option value="smtp">SMTP</option>
                                    <option value="sendmail">Sendmail</option>
                                    <option value="mailgun">Mailgun</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Host *</label>
                                <input wire:model="mailHost" type="text" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('mailHost') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Port *</label>
                                <input wire:model="mailPort" type="number" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('mailPort') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Encryption</label>
                                <select wire:model="mailEncryption" 
                                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                    <option value="">None</option>
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Username</label>
                                <input wire:model="mailUsername" type="text" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                                <input wire:model="mailPassword" type="password" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">From Address *</label>
                                <input wire:model="mailFromAddress" type="email" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('mailFromAddress') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">From Name *</label>
                                <input wire:model="mailFromName" type="text" 
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('mailFromName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <div class="flex items-center space-x-3">
                                <input wire:model="testEmail" type="email" placeholder="Test email address"
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <button type="button" wire:click="testEmailConfiguration"
                                        class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                    Send Test Email
                                </button>
                            </div>
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save Email Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @elseif ($activeTab === 'system')
            <!-- System Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">System Configuration</h2>
                    
                    <form wire:submit="saveSystemSettings">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-white mb-4">Application Settings</h3>
                                <div class="flex flex-wrap gap-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="debugMode" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Debug Mode</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="cacheEnabled" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Cache Enabled</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="userRegistration" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">User Registration</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="emailVerification" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Email Verification</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="twoFactorAuth" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Two-Factor Authentication</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Session Lifetime (minutes) *</label>
                                <input wire:model="sessionLifetime" type="number" min="5" max="1440"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                @error('sessionLifetime') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Max File Upload Size</label>
                                <input wire:model="maxFileUploadSize" type="text" placeholder="e.g., 10M"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-300 text-sm font-medium mb-2">Allowed File Types</label>
                                <input wire:model="allowedFileTypes" type="text" placeholder="jpg,jpeg,png,pdf,doc,docx"
                                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="toggleMaintenanceMode"
                                    class="bg-{{ $maintenanceMode ? 'green' : 'red' }}-600 hover:bg-{{ $maintenanceMode ? 'green' : 'red' }}-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                            </button>
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save System Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @elseif ($activeTab === 'notifications')
            <!-- Notification Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">Notification Settings</h2>
                    
                    <form wire:submit="saveNotificationSettings">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-4">Notification Types</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="emailNotifications" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Email Notifications</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="pushNotifications" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">Push Notifications</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="smsNotifications" 
                                               class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                        <span class="ml-2 text-gray-300">SMS Notifications</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Slack Webhook URL</label>
                                    <input wire:model="slackWebhook" type="url" 
                                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                </div>

                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Discord Webhook URL</label>
                                    <input wire:model="discordWebhook" type="url" 
                                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save Notification Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @elseif ($activeTab === 'security')
            <!-- Security Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">Security Configuration</h2>
                    
                    <form wire:submit="saveSecuritySettings">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-4">Password Requirements</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Minimum Length *</label>
                                        <input wire:model="passwordMinLength" type="number" min="6" max="20"
                                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                        @error('passwordMinLength') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex flex-col space-y-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="passwordRequireSpecial" 
                                                   class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-300">Require Special Characters</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="passwordRequireNumbers" 
                                                   class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-300">Require Numbers</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="passwordRequireUppercase" 
                                                   class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-300">Require Uppercase</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-white mb-4">Login Security</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Max Login Attempts *</label>
                                        <input wire:model="maxLoginAttempts" type="number" min="3" max="10"
                                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                        @error('maxLoginAttempts') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Lockout Duration (min) *</label>
                                        <input wire:model="lockoutDuration" type="number" min="5" max="60"
                                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                        @error('lockoutDuration') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Session Timeout (min) *</label>
                                        <input wire:model="sessionTimeout" type="number" min="15" max="480"
                                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                        @error('sessionTimeout') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save Security Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @else
            <!-- Backup Settings -->
            <div class="max-w-4xl">
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-yellow-400 mb-6">Backup Configuration</h2>
                    
                    <form wire:submit="saveBackupSettings">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-4">Backup Settings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="flex items-center mb-4">
                                            <input type="checkbox" wire:model="autoBackup" 
                                                   class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-300">Enable Auto Backup</span>
                                        </label>
                                    </div>

                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Backup Frequency</label>
                                        <select wire:model="backupFrequency" 
                                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                            <option value="hourly">Hourly</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Retention Period (days) *</label>
                                        <input wire:model="backupRetention" type="number" min="1" max="365"
                                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                        @error('backupRetention') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-300 text-sm font-medium mb-2">Storage Location</label>
                                        <select wire:model="backupStorage" 
                                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                            <option value="local">Local Storage</option>
                                            <option value="s3">Amazon S3</option>
                                            <option value="google">Google Drive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="runBackup"
                                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-play mr-2"></i>
                                Run Backup Now
                            </button>
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                Save Backup Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

</div>
