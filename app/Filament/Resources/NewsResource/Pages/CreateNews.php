<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // Jika user bukan admin → wajib isi otomatis author_id
        if (!$user->isAdmin()) {

            if (!$user->author) {
                throw new \Exception("User ini belum memiliki data Author. Tambahkan Author dulu.");
            }

            $data['author_id'] = $user->author->id;
        }

        return $data;
    }
}
