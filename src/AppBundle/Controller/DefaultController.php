<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Officer;
use AppBundle\Entity\Report;
use AppBundle\Form\Type\ReportFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

                $html = $this->renderView('template.html.twig', array(
                    'name'  => $officer->getFio(),
                    'text' => $report->getMessage()
                ));

                return new Response($this->getInfoGraphics($html), 200,
                    array(
                        'Content-Type'     => 'image/jpg',
                        'Content-Disposition' => 'inline; filename="image.jpg"')
                );

//                return $this->render('leaveReportForm.html.twig', array(
//                    'form' => $this->createForm(new ReportFormType())->createView(),
//                ));
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

    private function getInfoGraphics($html)
    {
        // The URL to get your HTML
        $url = "out.html";

// Name of your output image
        $name = "example" . rand(100, 10000) . ".jpg";

// Command to execute
        $command = "/usr/bin/wkhtmltoimage-i386 --width 400 --load-error-handling ignore";

// Directory for the image to be saved
        $image_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/";

// Putting together the command for `shell_exec()`
        $ex = "$command $url " . $image_dir . $name;

        file_put_contents ("out.html", $html);

// The full command is: "/usr/bin/wkhtmltoimage-i386 --load-error-handling ignore http://www.google.com/ /var/www/images/example.jpg"
// If we were to run this command via SSH, it would take a picture of google.com, and save it to /vaw/www/images/example.jpg

// Generate the image
// NOTE: Don't forget to `escapeshellarg()` any user input!
        shell_exec($ex);

        $file = $image_dir.$name;

        return file_get_contents($file);
    }
}
