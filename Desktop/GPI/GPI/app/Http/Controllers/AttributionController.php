<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribution;
use App\Models\Equipement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Licence;
use App\Traits\LogsModifications;


class AttributionController extends Controller
{
    use LogsModifications;


public function previewPDF()
{
    $attributions = Attribution::with('user', 'equipement')->get();

    $pdf = PDF::loadView('pdf.preview', compact('attributions'));

    return $pdf->stream('preview.pdf');
}

public function delete_attribution($id){
    $attributions = Attribution::find($id);
    $attributions ->delete();
    $this->logModification(
        'suppression',
        "Suppression d'une attribution",
        $id
    );
    return redirect('/List_attributions')->with('success', 'Attribution supprimé avec succéss.');
}
}
