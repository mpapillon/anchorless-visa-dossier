<?php

namespace App\Http\Requests;

use App\Enums\FileUploadType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreFileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:4096',
                'mimes:pdf,jpeg,png,jpg',
                'mimetypes:application/pdf,image/jpeg,image/png',
            ],
            'type' => [
                'required',
                new Enum(FileUploadType::class),
            ],
        ];
    }
}
