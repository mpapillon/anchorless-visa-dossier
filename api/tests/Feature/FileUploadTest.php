<?php

use App\Enums\FileUploadType;
use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake();
});

// --- index ---

it('returns files grouped by type', function () {
    FileUpload::factory()->pdf()->create();
    FileUpload::factory()->photo()->count(2)->create();

    $response = $this->getJson('/api/file-uploads');

    $response->assertSuccessful();

    $data = $response->json();

    expect($data)->toHaveKey(FileUploadType::VisaRequestForm->value)
        ->and($data[FileUploadType::VisaRequestForm->value])->toHaveCount(1)
        ->and($data[FileUploadType::Photo->value])->toHaveCount(2);
});

it('returns an empty object when no files exist', function () {
    $this->getJson('/api/file-uploads')
        ->assertSuccessful()
        ->assertExactJson([]);
});

// --- store ---

it('uploads a file successfully', function () {
    $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

    $response = $this->postJson('/api/file-uploads', [
        'file' => $file,
        'type' => FileUploadType::VisaRequestForm->value,
    ]);

    $response->assertCreated()
        ->assertJsonFragment([
            'type' => FileUploadType::VisaRequestForm->value,
            'originalName' => 'document.pdf',
            'mimeType' => 'application/pdf',
        ]);

    expect(FileUpload::count())->toBe(1);
    Storage::assertExists('upload/' . basename(FileUpload::first()->file_path));
});

it('rejects a file exceeding 4MB', function () {
    $file = UploadedFile::fake()->create('big.pdf', 5120, 'application/pdf');

    $this->postJson('/api/file-uploads', [
        'file' => $file,
        'type' => FileUploadType::VisaRequestForm->value,
    ])->assertUnprocessable()
        ->assertJsonFragment(['message' => 'Validation failed']);
});

it('rejects a disallowed file type', function (string $mime, string $name) {
    $file = UploadedFile::fake()->create($name, 100, $mime);

    $this->postJson('/api/file-uploads', [
        'file' => $file,
        'type' => FileUploadType::VisaRequestForm->value,
    ])->assertUnprocessable();
})->with([
    'gif'  => ['image/gif', 'image.gif'],
    'webp' => ['image/webp', 'image.webp'],
    'mp4'  => ['video/mp4', 'video.mp4'],
]);

it('rejects an invalid type value', function () {
    $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

    $this->postJson('/api/file-uploads', [
        'file' => $file,
        'type' => 'invalid_type',
    ])->assertUnprocessable()
        ->assertJsonStructure(['message', 'errors' => ['type']]);
});

it('requires both file and type fields', function (array $payload) {
    $this->postJson('/api/file-uploads', $payload)
        ->assertUnprocessable();
})->with([
    'missing file' => [['type' => FileUploadType::Photo->value]],
    'missing type' => [['file' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf')]],
]);

// --- show ---

it('returns a single file', function () {
    $fileUpload = FileUpload::factory()->pdf()->create();

    $this->getJson("/api/file-uploads/{$fileUpload->id}")
        ->assertSuccessful()
        ->assertJsonFragment(['id' => $fileUpload->id]);
});

it('returns 404 for a missing file', function () {
    $this->getJson('/api/file-uploads/999')
        ->assertNotFound();
});

// --- download ---

it('downloads a file with its original name', function () {
    $file = UploadedFile::fake()->create('passport.pdf', 500, 'application/pdf');
    $path = $file->store('upload');

    $fileUpload = FileUpload::factory()->pdf()->create([
        'file_path' => $path,
        'original_name' => 'passport.pdf',
    ]);

    $this->get("/api/file-uploads/{$fileUpload->id}/download")
        ->assertSuccessful()
        ->assertDownload('passport.pdf');
});

it('returns 404 when downloading a missing file', function () {
    $this->get('/api/file-uploads/999/download')
        ->assertNotFound();
});

// --- destroy ---

it('deletes a file and removes it from storage', function () {
    $fileUpload = FileUpload::factory()->pdf()->create();

    $this->deleteJson("/api/file-uploads/{$fileUpload->id}")
        ->assertNoContent();

    expect(FileUpload::count())->toBe(0);
    Storage::assertMissing($fileUpload->file_path);
});
