<?php

namespace App\Models;

use App\Enums\FileUploadType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    /** @use HasFactory<\Database\Factories\FileUploadFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'type' => FileUploadType::class,
        ];
    }

    public function scopeWhereType(Builder $query, FileUploadType $type): void
    {
        $query->where('type', $type);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
