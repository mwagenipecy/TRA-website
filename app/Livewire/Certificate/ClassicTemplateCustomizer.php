<?php

namespace App\Livewire\Certificate;

use Livewire\Component;

class ClassicTemplateCustomizer extends Component
{
    public $certificateId;
    public $currentSettings = [];
    public $previewMode = true;
    
    // Customization properties
    public $borderStyle = 'double';
    public $sealStyle = 'ceremonial';
    public $colorScheme = 'amber';
    public $fontSize = 'normal';
    public $showPattern = true;
    public $showCorners = true;
    public $showSeal = true;
    public $showVerificationCode = true;
    
    protected $listeners = ['updatePreview' => 'refreshPreview'];

    public function mount($certificateId, $settings = [])
    {
        $this->certificateId = $certificateId;
        $this->currentSettings = $settings;
        $this->loadSettings();
    }

    private function loadSettings()
    {
        if (!empty($this->currentSettings)) {
            foreach ($this->currentSettings as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function updateSetting($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
            $this->emitSettingsUpdate();
        }
    }

    public function toggleSetting($property)
    {
        if (property_exists($this, $property) && is_bool($this->$property)) {
            $this->$property = !$this->$property;
            $this->emitSettingsUpdate();
        }
    }

    private function emitSettingsUpdate()
    {
        $settings = [
            'borderStyle' => $this->borderStyle,
            'sealStyle' => $this->sealStyle,
            'colorScheme' => $this->colorScheme,
            'fontSize' => $this->fontSize,
            'showPattern' => $this->showPattern,
            'showCorners' => $this->showCorners,
            'showSeal' => $this->showSeal,
            'showVerificationCode' => $this->showVerificationCode
        ];

        $this->emit('settingsUpdated', $settings);
        $this->dispatchBrowserEvent('classic-customizer-updated', $settings);
    }

    public function applyPreset($presetName)
    {
        $presets = [
            'elegant' => [
                'borderStyle' => 'double',
                'sealStyle' => 'ceremonial',
                'colorScheme' => 'gold',
                'fontSize' => 'normal',
                'showPattern' => true,
                'showCorners' => true,
                'showSeal' => true,
                'showVerificationCode' => true
            ],
            'vintage' => [
                'borderStyle' => 'decorative',
                'sealStyle' => 'official',
                'colorScheme' => 'bronze',
                'fontSize' => 'large',
                'showPattern' => true,
                'showCorners' => true,
                'showSeal' => true,
                'showVerificationCode' => false
            ],
            'modern' => [
                'borderStyle' => 'single',
                'sealStyle' => 'simple',
                'colorScheme' => 'emerald',
                'fontSize' => 'normal',
                'showPattern' => false,
                'showCorners' => false,
                'showSeal' => true,
                'showVerificationCode' => true
            ],
            'minimal' => [
                'borderStyle' => 'single',
                'sealStyle' => 'simple',
                'colorScheme' => 'amber',
                'fontSize' => 'small',
                'showPattern' => false,
                'showCorners' => false,
                'showSeal' => false,
                'showVerificationCode' => true
            ]
        ];

        if (isset($presets[$presetName])) {
            foreach ($presets[$presetName] as $key => $value) {
                $this->$key = $value;
            }
            $this->emitSettingsUpdate();
        }
    }

    public function resetToDefaults()
    {
        $this->applyPreset('elegant');
    }

    public function exportSettings()
    {
        $settings = [
            'borderStyle' => $this->borderStyle,
            'sealStyle' => $this->sealStyle,
            'colorScheme' => $this->colorScheme,
            'fontSize' => $this->fontSize,
            'showPattern' => $this->showPattern,
            'showCorners' => $this->showCorners,
            'showSeal' => $this->showSeal,
            'showVerificationCode' => $this->showVerificationCode
        ];

        $this->dispatchBrowserEvent('export-settings', [
            'settings' => $settings,
            'filename' => 'classic-template-settings.json'
        ]);
    }

    public function refreshPreview()
    {
        $this->emitSettingsUpdate();
    }

    public function render()
    {
        return view('livewire.certificate.classic-template-customizer');
    }
}