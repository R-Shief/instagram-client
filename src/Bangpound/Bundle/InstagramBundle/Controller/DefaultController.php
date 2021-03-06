<?php

namespace Bangpound\Bundle\InstagramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // some default data for when the form is displayed the first time
        $data = array(
            'lat' => '31.7833',
            'lng' => '35.2167',
            'distance' => '5000',
            'count' => '100',
        );

        $form = $this->createFormBuilder($data)
            ->add('lat')
            ->add('lng')
            ->add('distance')
            ->add('count')
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $url = $this->generateUrl('bangpound_instagram_location', $data);

            // redirect somewhere
            return $this->redirect($url);
        }

        // display the form
        return $this->render('BangpoundInstagramBundle:Default:index.html.twig', array(
            'fluid' => true,
            'form' => $form->createView()
        ));
    }

    public function locationAction(Request $request, $lat, $lng)
    {
        $params = $request->query->all();
        /** @var \HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken $token */
        $token = $this->get('security.context')->getToken();

        $instagram = $this->get('bangpound_instagram.instagram');
        $instagram->setAccessToken($token->getAccessToken());

        $media = $instagram->searchMedia( $lat, $lng, $params);

        return $this->render('BangpoundInstagramBundle:Default:location.html.twig', array('media' => $media));
    }
}
