<?php

namespace App\Controller;

use App\Entity\Band;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelImportController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/excel/import', name: 'app_excel_import')]
    public function import(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('excel_file');
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
            $newFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);
            $newFilename = $newFilename . '-' . uniqid() . '.' . $extension;

            try {
                $uploadedFile->move($destination, $newFilename);
                $spreadsheet = IOFactory::load($destination . $newFilename);
                $spreadsheet->getActiveSheet()->removeRow(1);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(returnCellRef: true);

                foreach ($sheetData as $row) {
                    $band = new Band();
                    $band->setName($row['A']);
                    $band->setOrigin($row['B']);
                    $band->setCity($row['C']);
                    $band->setStartYear((int)$row['D']);
                    $band->setSeparationYear((int)$row['E']);
                    $band->setFounders($row['F']);
                    $band->setMembers((int)$row['G']);
                    $band->setMusicalCurrent($row['H']);
                    $band->setPresentation($row['I']);

                    $this->entityManager->persist($band);
                }

                $this->entityManager->flush();

                $this->addFlash('success', 'L\'importation du fichier Excel a été effectuée avec succès.');

                return $this->redirectToRoute('app_excel_import');
            } catch
            (FileException $e) {
                dd($e);
            }
        }

        return $this->render('excel_import/import.html.twig', [
            'controller_name' => 'ExcelImportController',
        ]);
    }
}
