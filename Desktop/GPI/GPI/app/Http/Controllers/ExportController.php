<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Equipement;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AssignerLogiciel;
use App\Traits\LogsModifications;


class ExportController extends Controller
{
   use LogsModifications;
   // ------- EXPORTER EN FORMAT CSV------------//
   /*
      public function exportData()
   {
       $attributions = Attribution::with(['user', 'equipement'])->get();
   
       $filename = 'historique_des_attributions.csv';
   
       $handle = fopen($filename, 'w+');
   
       fputcsv($handle, [
           'ID Attribution',
           'Nom Utilisateur', 'Email', 'Contact', 'Emploi', 'Fonction', 'Grade',
           'Nom Equipement', 'Categorie', 'Marque', 'Modèle', 'Numéro Série', 'Date Acquisition', 'Etat',
           'Date Attribution'
       ]);
   
       foreach ($attributions as $attribution) {
           fputcsv($handle, [
               $attribution->id,
               $attribution->user->name,
               $attribution->user->email,
               $attribution->user->Contact,
               $attribution->user->emploie,
               $attribution->user->fonction,
               $attribution->user->grade,
               $attribution->equipement->nom,
               $attribution->equipement->categorie,
               $attribution->equipement->marque,
               $attribution->equipement->modele,
               $attribution->equipement->numero_serie,
               $attribution->equipement->date_acquisition,
               $attribution->equipement->etat,
               $attribution->created_at->format('d/m/Y H:i:s') 
           ]);
       }
   
       fclose($handle);
          return response()->download($filename)->deleteFileAfterSend();
   }
   

   // ------- EXPORTER EN FORMAT WORD------------//



public function exportToWord()
{
    $attributions = Attribution::with(['user', 'equipement'])->get();

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $section->addTitle("Historique des Attributions", 1);

    $table = $section->addTable();

    $table->addRow();
    $table->addCell(2000)->addText("ID Attribution");
    $table->addCell(3000)->addText("Nom Utilisateur");
    $table->addCell(3000)->addText("Email");
    $table->addCell(2000)->addText("Contact");
    $table->addCell(2000)->addText("Equipement");
    $table->addCell(2000)->addText("Catégorie");
    $table->addCell(2000)->addText("État");

    foreach ($attributions as $attribution) {
        $table->addRow();
        $table->addCell(2000)->addText($attribution->id);
        $table->addCell(3000)->addText($attribution->user->name);
        $table->addCell(3000)->addText($attribution->user->email);
        $table->addCell(2000)->addText($attribution->user->Contact);
        $table->addCell(2000)->addText($attribution->equipement->nom);
        $table->addCell(2000)->addText($attribution->equipement->categorie);
        $table->addCell(2000)->addText($attribution->equipement->etat);
    }

    $filename = 'historique_attributions.docx';

    $tempFile = tempnam(sys_get_temp_dir(), $filename);
    $phpWord->save($tempFile, 'Word2007');

    return response()->download($tempFile, $filename)->deleteFileAfterSend();
} */
   // ------- EXPORTER EN FORMAT PDF------------//

   public function exportToPDF()
   {
       // Créez une instance de Dompdf
       $dompdf = new Dompdf();

       // Contenu HTML à transformer en PDF
       $html = "
       <h1>Historique des Attributions</h1>
       <p>Voici un exemple d'export PDF.</p>";

       // Chargez le contenu HTML dans Dompdf
       $dompdf->loadHtml($html);

       // Configurez la taille et l'orientation de la page
       $dompdf->setPaper('A4', 'portrait');

       // Render du fichier PDF
       $dompdf->render();


       return response($dompdf->output(), 200)
           ->header('Content-Type', 'application/pdf')
           ->header('Content-Disposition', 'attachment; filename="historique_attributions.pdf"');
   }

   public function previewPDF()
   {
       $assignments = Assignment::with('user', 'equipement')
           ->where('direction_id', auth()->user()->direction_id)
           ->get();

       $pdf = PDF::loadView('pdf.preview', compact('assignments'));

       return $pdf->stream('preview.pdf');
   }
   public function previewLicencePDF()
   {
    $licences = AssignerLogiciel::with('user', 'logiciel')
        ->where('direction_id', auth()->user()->direction_id)
        ->get();

    $pdf = PDF::loadView('pdf.preview_licence', compact('licences'));

    return $pdf->stream('preview_licence.pdf');
   }



}
