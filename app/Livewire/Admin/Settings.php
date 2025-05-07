<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class Settings extends Component
{
    public $siteName;
    public $siteDescription;
    public $contactEmail;
    public $enableRegistration = true;
    public $enableShop = true;
    public $maintenanceMode = false;
    public $analyticsCode;
    public $defaultCurrency = 'BRL';
    public $taxRate = 0;
    public $shippingFee = 0;
    public $freeShippingThreshold = 0;
    
    public function mount()
    {
        // Carregar configurações do cache ou usar valores padrão
        $this->siteName = Cache::get('settings.site_name', config('app.name'));
        $this->siteDescription = Cache::get('settings.site_description', '');
        $this->contactEmail = Cache::get('settings.contact_email', config('mail.from.address'));
        $this->enableRegistration = Cache::get('settings.enable_registration', true);
        $this->enableShop = Cache::get('settings.enable_shop', true);
        $this->maintenanceMode = app()->isDownForMaintenance();
        $this->analyticsCode = Cache::get('settings.analytics_code', '');
        $this->defaultCurrency = Cache::get('settings.default_currency', 'BRL');
        $this->taxRate = Cache::get('settings.tax_rate', 0);
        $this->shippingFee = Cache::get('settings.shipping_fee', 0);
        $this->freeShippingThreshold = Cache::get('settings.free_shipping_threshold', 0);
    }
    
    public function saveGeneralSettings()
    {
        $this->validate([
            'siteName' => 'required|string|max:255',
            'siteDescription' => 'nullable|string|max:1000',
            'contactEmail' => 'required|email',
            'enableRegistration' => 'boolean',
            'enableShop' => 'boolean',
        ]);
        
        // Salvar configurações no cache
        Cache::put('settings.site_name', $this->siteName, now()->addYear());
        Cache::put('settings.site_description', $this->siteDescription, now()->addYear());
        Cache::put('settings.contact_email', $this->contactEmail, now()->addYear());
        Cache::put('settings.enable_registration', $this->enableRegistration, now()->addYear());
        Cache::put('settings.enable_shop', $this->enableShop, now()->addYear());
        
        $this->dispatch('notify', [
            'message' => 'Configurações gerais salvas com sucesso!',
            'type' => 'success'
        ]);
    }
    
    public function saveShopSettings()
    {
        $this->validate([
            'defaultCurrency' => 'required|string|size:3',
            'taxRate' => 'required|numeric|min:0|max:100',
            'shippingFee' => 'required|numeric|min:0',
            'freeShippingThreshold' => 'required|numeric|min:0',
        ]);
        
        // Salvar configurações no cache
        Cache::put('settings.default_currency', $this->defaultCurrency, now()->addYear());
        Cache::put('settings.tax_rate', $this->taxRate, now()->addYear());
        Cache::put('settings.shipping_fee', $this->shippingFee, now()->addYear());
        Cache::put('settings.free_shipping_threshold', $this->freeShippingThreshold, now()->addYear());
        
        $this->dispatch('notify', [
            'message' => 'Configurações da loja salvas com sucesso!',
            'type' => 'success'
        ]);
    }
    
    public function saveAnalyticsSettings()
    {
        $this->validate([
            'analyticsCode' => 'nullable|string',
        ]);
        
        // Salvar configurações no cache
        Cache::put('settings.analytics_code', $this->analyticsCode, now()->addYear());
        
        $this->dispatch('notify', [
            'message' => 'Configurações de analytics salvas com sucesso!',
            'type' => 'success'
        ]);
    }
    
    public function toggleMaintenanceMode()
    {
        if ($this->maintenanceMode) {
            // Ativar modo de manutenção
            Artisan::call('down');
            $this->dispatch('notify', [
                'message' => 'Modo de manutenção ativado!',
                'type' => 'success'
            ]);
        } else {
            // Desativar modo de manutenção
            Artisan::call('up');
            $this->dispatch('notify', [
                'message' => 'Modo de manutenção desativado!',
                'type' => 'success'
            ]);
        }
        
        $this->maintenanceMode = app()->isDownForMaintenance();
    }
    
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        
        $this->dispatch('notify', [
            'message' => 'Cache limpo com sucesso!',
            'type' => 'success'
        ]);
    }
    
    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin', ['title' => 'Configurações']);
    }
}
