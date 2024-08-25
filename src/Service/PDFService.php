<?php

namespace App\Service;
use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{
    private $dompdf;

    /**
     * PDFService constructor.
     */
    public function __construct()
    {
        $options = new Options();
        $options->setDefaultFont('helvetica');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->setDpi(150);
        $this->dompdf = new Dompdf($options);
        $this->dompdf->setPaper('A4', '');
    }

    /**
     * Méthode pour créer un fichier PDF
     *
     * @param $content
     *
     * @return string PDF content
     */
    public function generatePDF($content)
    {
        $this->dompdf->loadHtml($content);
        $this->dompdf->render();
        return $this->dompdf->output();
    }
}