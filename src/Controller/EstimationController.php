<?php

namespace App\Controller;

use App\Services\EstimationService;
use Dompdf\Dompdf;

class EstimationController extends Controller
{
    public function new(): string
    {
        return $this->twig->render('estimation/new.twig');
    }

    public function save(array $data): string
    {
        global $wpdb;
        $wasSaved = (new EstimationService($wpdb))->add($data);
        return json_encode((new EstimationService($wpdb))->getEstimateById($wasSaved));
    }

    public function pdf(array $estimation)
    {

        $html = $this->twig->render('estimation/pdf.twig', [
            'estimation' => $estimation
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream();
    }

}
