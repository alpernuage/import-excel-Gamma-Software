<?php

namespace App\Service;

use App\Entity\Band;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExcelImportService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function importExcel(UploadedFile $uploadedFile, string $destination): void
    {
        $newFilename = $this->generateUniqueFileName($uploadedFile);
        $uploadedFile->move($destination, $newFilename);

        $spreadsheet = IOFactory::load($destination . $newFilename);
        $spreadsheet->getActiveSheet()->removeRow(1);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(returnCellRef: true);

        foreach ($sheetData as $row) {
            $band = new Band();
            $band->setName($row['A']);
            $band->setOrigin($row['B']);
            $band->setCity($row['C']);
            $band->setStartYear($row['D']);
            $band->setSeparationYear($row['E']);
            $band->setFounders($row['F']);
            $band->setMembers($row['G']);
            $band->setMusicalCurrent($row['H']);
            $band->setPresentation($row['I']);

            $this->entityManager->persist($band);
        }

        $this->entityManager->flush();
    }

    public function generateUniqueFileName(UploadedFile $uploadedFile): string
    {
        $newFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);

        return $newFilename . '-' . uniqid() . '.' . $extension;
    }
}
