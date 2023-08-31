<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelImportController extends AbstractController
{
    #[Route('/excel/import', name: 'app_excel_import')]
    public function index(Request $request): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('excel_file');
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $uploadedFile->move($destination);

        return $this->render('excel_import/index.html.twig', [
            'controller_name' => 'ExcelImportController',
        ]);
    }
}

