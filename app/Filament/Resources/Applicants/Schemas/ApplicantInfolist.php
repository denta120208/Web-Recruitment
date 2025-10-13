<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApplicantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('FirstName'),
                TextEntry::make('MiddleName')
                    ->placeholder('-'),
                TextEntry::make('LastName')
                    ->placeholder('-'),
                TextEntry::make('Gender')
                    ->placeholder('-'),
                TextEntry::make('DateOfBirth')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('CVPath')
                    ->placeholder('-'),
                TextEntry::make('PhotoPath')
                    ->placeholder('-'),
                TextEntry::make('IDCardPath')
                    ->placeholder('-'),
                TextEntry::make('Address')
                    ->placeholder('-'),
                TextEntry::make('City')
                    ->placeholder('-'),
                TextEntry::make('Gmail')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('LinkedIn')
                    ->placeholder('-'),
                TextEntry::make('Instagram')
                    ->placeholder('-'),
                TextEntry::make('Phone')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('CreatedAt')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('UpdatedAt')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
