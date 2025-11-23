<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        
        if (!$user->isAdmin()) {

            if (!$user->author) {
                throw new \Exception("User ini belum memiliki data Author. Tambahkan Author dulu.");
            }

            $data['author_id'] = $user->author->id;
        }

        return $data;
    }
}
