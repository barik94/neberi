<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Officer;
use AppBundle\Entity\Report;
use AppBundle\Form\Type\ReportFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function renderOfficersInfoAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $officers = $em->getRepository('AppBundle:Officer')->findAll();

        return $this->render('homepage.html.twig', array(
            'officers' => $officers,
        ));
    }

    /**
     * @Route("/handle", name="handle_report")
     */
    public function handleReportAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $report = new Report();
        $form = $this->createForm(new ReportFormType(), $report);

        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            if ($form->isValid()) {
                if (!$officer = $em->getRepository('AppBundle:Officer')->findOneByToken($report->getToken())) {
                    $officer = new Officer();
                    $officer->setToken($report->getToken());
                }

                $officer->incrementAmount();
                $officer->setFio($report->getFio());

                $report->setPhoto($this->saveFile($report->getPhotoFile(), 'photo'));
                $report->setMedia($this->saveFile($report->getMediaFile(), 'media'));
                $report->setOfficer($officer);

                $em->persist($report);
                $officer->addReport($report);
                $em->persist($officer);
                $em->flush();

                return $this->render('leaveReportForm.html.twig', array(
                    'form' => $this->createForm(new ReportFormType())->createView(),
                ));
            } else {
                return $this->render('leaveReportForm.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('leaveReportForm.html.twig', array(
            'form' => $this->createForm(new ReportFormType())->createView(),
        ));
    }

    private function saveFile($file, $type)
    {
        $mimeTypeArray = ['image/jpg', 'image/png', 'image/jpeg'];

        if ($file) {
            if (in_array($file->getMimeType(), $mimeTypeArray) || $type == 'media') {

                $content = file_get_contents($file);
                $name = '/uploads/' . time() . '/' . $file->getClientOriginalName();
                $fs = new Filesystem();

                $fs->dumpFile($_SERVER['DOCUMENT_ROOT'] . $name, $content);

                return $name;
            }
        }

        return null;
    }
}
