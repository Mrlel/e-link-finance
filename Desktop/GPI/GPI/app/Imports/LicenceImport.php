<?php

namespace App\Imports;

use App\Models\Licence;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class LicenceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $direction_id;

    public function __construct($direction_id)
    {
        $this->direction_id = $direction_id;
    }

    public function model(array $row)
    {
        return new Licence([
            'designation_licence' => $row['designation_licence'],
            'type_licence' => $row['type_licence'],
            'date_expiration' => Carbon::parse($row['date_expiration']),
            'cle_licence' => $row['cle_licence'],
            'environnement' => $row['environnement'],
            'langage_version' => $row['langage_version'],
            'sgbd_version' => $row['sgbd_version'],
            'base_donnees' => $row['base_donnees'],
            'libelle_licence' => $row['libelle_licence'],
            'direction_id' => $this->direction_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'designation_licence' => 'required|string|max:255',
            'type_licence' => 'required|string',
            'date_expiration' => 'required|date',
            'cle_licence' => 'required|string',
            'environnement' => 'required|string',
            'langage_version' => 'required|string',
            'sgbd_version' => 'required|string',
            'base_donnees' => 'required|string',
            'libelle_licence' => 'required|string',
        ];
    }
}