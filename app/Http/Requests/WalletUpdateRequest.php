<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\PartnerOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isApproved();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'wallet_network' => ['required', 'string', Rule::in(array_keys(PartnerOptions::NETWORKS))],
            'wallet_address' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'wallet_network.required' => 'Выберите сеть для выплат.',
            'wallet_network.in' => 'Выбрана недопустимая сеть.',
            'wallet_address.required' => 'Укажите адрес кошелька.',
            'wallet_address.min' => 'Адрес кошелька выглядит слишком коротким.',
        ];
    }
}
