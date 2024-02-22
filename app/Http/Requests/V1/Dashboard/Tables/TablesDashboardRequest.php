<?php
namespace App\Http\Requests\V1\Dashboard\Tables;

use Illuminate\Foundation\Http\FormRequest;


class TablesDashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'assets' => 'sometimes|array',
            'assets.*.name' => 'required_with:assets|string',
            'assets.*.x' => 'required_with:assets|numeric',
            'assets.*.y' => 'required_with:assets|numeric',
            'assets.*.img' => 'required_with:assets|string',
            'assets.*.key' => 'required_with:assets|string',
            'assets.*.nfc_number' => 'integer',
            'boardWidth' => 'required|integer|min:0',
            'boardHeight' => 'required|integer|min:0'
        ];
    }
}
