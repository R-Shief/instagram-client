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

            $url = $this->generateUrl('location', $data);

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
        $instagram = $this->get('bangpound_instagram.instagram');

        $media = $instagram->searchMedia( $lat, $lng, $params);

        return $this->render('BangpoundInstagramBundle:Default:location.html.twig', array('media' => $media));
    }

    public function logoutAction(Request $request)
    {
        $request->getSession()->invalidate();

        return $this->redirect('/');
    }

    public function authAction()
    {
        $auth = $this->get('bangpound_instagram.auth');
        $auth->authorize();
    }

    public function tokenAction(Request $request)
    {
        $auth = $this->get('bangpound_instagram.auth');
        $session = $this->get('session');
        $code = $auth->getAccessToken($request->get('code'));
        $session->set('code', $code);
        $instagram = $this->get('bangpound_instagram.instagram');
        $current_user = $instagram->getCurrentUser();
        $session->set('username', $current_user->getUserName());

        return $this->redirect('/');
    }
}
