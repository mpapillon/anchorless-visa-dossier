<?php

namespace Database\Factories;

use App\Enums\FileUploadType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileUpload>
 */
class FileUploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * 
     */
    public function definition(): array
    {
        $files = [
            ['mime_type' => 'application/pdf', 'extension' => 'pdf'],
            ['mime_type' => 'image/jpeg', 'extension' => 'jpg'],
            ['mime_type' => 'image/png', 'extension' => 'png'],
        ];

        $file = fake()->randomElement($files);

        return [
            'type' => fake()->randomElement(FileUploadType::cases()),
            'file_path' => 'upload/' . fake()->uuid() . '.' . $file['extension'],
            'original_name' => fake()->word() . '.' . $file['extension'],
            'mime_type' => $file['mime_type'],
            'size' => fake()->numberBetween(10240, 4194304), // 10KB – 4MB
        ];
    }

    public function pdf(): static
    {
        return $this->state([
            'type' => FileUploadType::VisaRequestForm,
            'file_path' => 'upload/' . fake()->uuid() . '.pdf',
            'original_name' => fake()->word() . '.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    public function photo(): static
    {
        return $this->state([
            'type' => FileUploadType::Photo,
            'file_path' => 'upload/' . fake()->uuid() . '.jpg',
            'original_name' => fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    public function passport(): static
    {
        return $this->state([
            'type' => FileUploadType::Passport,
            'file_path' => 'upload/' . fake()->uuid() . '.jpg',
            'original_name' => fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }
}
