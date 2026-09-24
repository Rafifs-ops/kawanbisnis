<?php

namespace App\Livewire\Dashboard;

use App\Models\BusinessPassport;
use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profil Bisnis')]
class PassportIndex extends Component
{
    public string $business_name = '';

    public string $business_type = 'F&B';

    public string $target_customer = '';

    public string $business_description = '';

    /** @var array<int, array{name: string, price: float, margin: float}> */
    public array $products = [];

    /** @var array<int, string> */
    public array $sales_channels = ['Instagram', 'Tokopedia'];

    public int $marketing_budget = 1000000;

    public int $team_capacity = 2;

    public bool $showAddProductModal = false;

    public string $new_product_name = '';

    public float $new_product_price = 0;

    public float $new_product_margin = 0;

    public ?BusinessPassport $passport = null;

    public bool $justSavedFirstTime = false;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $this->passport = $user->businessPassport;

        if ($this->passport) {
            $this->business_name = $this->passport->business_name;
            $this->business_type = $this->passport->business_type;
            $this->target_customer = $this->passport->target_customer ?? '';
            $this->business_description = $this->passport->business_description ?? '';
            $this->products = $this->passport->products ?? [];
            $this->sales_channels = $this->passport->sales_channels ?? ['Instagram', 'Tokopedia'];
            $this->marketing_budget = $this->passport->constraints['marketing_budget'] ?? 1000000;
            $this->team_capacity = $this->passport->constraints['team_capacity'] ?? 2;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:F&B,Fashion,Retail,Service',
            'target_customer' => 'nullable|string|max:255',
            'business_description' => 'nullable|string|max:1000',
        ]);

        $data = [
            ...$validated,
            'products' => $this->products,
            'sales_channels' => $this->sales_channels,
            'constraints' => [
                'marketing_budget' => $this->marketing_budget,
                'team_capacity' => $this->team_capacity,
            ],
        ];

        $isFirstSave = $this->passport === null;

        if ($this->passport) {
            $this->passport->update($data);
        } else {
            /** @var User $user */
            $user = auth()->user();
            /** @var BusinessPassport $passport */
            $passport = $user->businessPassport()->create($data);
            $this->passport = $passport;
        }

        $this->justSavedFirstTime = $isFirstSave;

        Flux::toast(variant: 'success', text: 'Profil bisnis berhasil disimpan.');
    }

    public function addProduct(): void
    {
        $this->validate([
            'new_product_name' => 'required|string|max:255',
            'new_product_price' => 'required|numeric|min:0',
            'new_product_margin' => 'required|numeric|min:0|max:100',
        ]);

        $this->products[] = [
            'name' => $this->new_product_name,
            'price' => $this->new_product_price,
            'margin' => $this->new_product_margin,
        ];

        $this->reset(['new_product_name', 'new_product_price', 'new_product_margin', 'showAddProductModal']);
    }

    public function removeProduct(int $index): void
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function render(): View
    {
        return view('livewire.dashboard.passport.index');
    }
}
