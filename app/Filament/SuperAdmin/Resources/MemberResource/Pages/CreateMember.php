<?php

namespace App\Filament\SuperAdmin\Resources\MemberResource\Pages;

use App\Filament\SuperAdmin\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
}
