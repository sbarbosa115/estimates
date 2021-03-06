<?php

namespace App\Controller;

use App\Services\EstimationService;
use Dompdf\Dompdf;

class EstimationController extends Controller
{
    public function new(): string
    {
        $products = [];
        foreach(explode(',', get_option('estimate_products')) as $product) {
            if($product) {
                $products[] = ucwords(strtolower(trim($product)));
            }
        }
        return $this->twig->render('estimation/new.twig', [
            'products' => $products
        ]);
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

        $dompdf = new Dompdf(['enable_remote' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream($estimation['formType'].'-'.$estimation['id'].'.pdf');
    }

}
