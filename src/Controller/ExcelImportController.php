<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use App\Repository\BandRepository;
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

    #[Route('/index', name: 'app_bands', methods: [Request::METHOD_GET])]
    public function index(BandRepository $bandRepository): Response
    {
        $bands = $bandRepository->findAll();

        return $this->render('excel_import/index.html.twig', [
            'bands' => $bands,
        ]);
    }

    #[Route('/create', name: 'app_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(BandType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();
            $this->entityManager->persist($band);
            $this->entityManager->flush();

            $this->addFlash('success', 'Band created successfully!');

            return $this->redirectToRoute('app_bands');
        }

        return $this->render('excel_import/create.html.twig', [
            'create_band_form' => $form,
        ]);
    }

    #[Route('/edit', name: 'app_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, BandRepository $bandRepository): Response
    {
        $band = $bandRepository->find($request->get('id'));
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();
            $this->entityManager->persist($band);
            $this->entityManager->flush();

            $this->addFlash('success', 'Band updated successfully!');

            return $this->redirectToRoute('app_bands');
        }

        return $this->render('excel_import/edit.html.twig', [
            'edit_band_form' => $form,
        ]);
    }

    #[Route('/import', name: 'app_excel_import', methods: [Request::METHOD_POST])]
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

                return $this->redirectToRoute('app_bands');
            } catch
            (FileException $e) {
                dd($e);
            }
        }

        return $this->render('excel_import/index.html.twig', [
            'controller_name' => 'ExcelImportController',
        ]);
    }
}
