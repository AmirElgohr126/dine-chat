<?php
namespace App\Http\Requests\Dashboard\Tables;

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
            'assets' => 'required|array',
            'assets.*.id' => 'required|string',
            'assets.*.x' => 'required|numeric',
            'assets.*.y' => 'required|numeric',
            'assets.*.img' => 'required|url',
            'assets.*.key' => 'required|string',
            'boardWidth' => 'required|integer|min:0',
            'boardHeight' => 'required|integer|min:0'
        ];
    }
}

?>
