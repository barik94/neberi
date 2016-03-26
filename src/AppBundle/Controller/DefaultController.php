<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Officer;
use AppBundle\Form\Type\OfficerFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/handle", name="handle_officer_info")
     */
    public function handleOfficerInfoAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $officer = new Officer();
        $form = $this->createForm(new OfficerFormType(), $officer);

        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            if ($form->isValid()) {
                $officer->setPhoto($this->saveFile($officer->getPhotoFile(), 'photo'));
                $officer->setMedia($this->saveFile($officer->getMediaFile(), 'media'));

                $em->persist($officer);
                $em->flush();

                return $this->render('addOfficerInfoForm.html.twig', array(
                    'form' => $this->createForm(new OfficerFormType())->createView(),
                ));
            } else {
                    return $this->render('addOfficerInfoForm.html.twig', array(
                        'form' => $form->createView(),
                    ));
            }
        }

        return $this->render('addOfficerInfoForm.html.twig', array(
            'form' => $this->createForm(new OfficerFormType())->createView(),
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
