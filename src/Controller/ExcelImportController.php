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
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('excel_file');
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $newFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);
            $uploadedFile->move($destination, $newFilename . '-' . uniqid() . '.' . $extension);
            $this->addFlash('success', 'L\'importation du fichier Excel a été effectuée avec succès.');

            return $this->redirectToRoute('app_excel_import');
        }

        return $this->render('excel_import/index.html.twig', [
            'controller_name' => 'ExcelImportController',
        ]);
    }
}
