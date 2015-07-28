<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RI\FileManagerBundle\Controller;

use DoctrineExtensions\Versionable\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class DefaultController
 *
 * @package RI\FileManagerBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $configuration = array(
            'availableDimensions' => $this->get('service_container')->getParameter('ri.filemanager.dimensions')
        );

        return $this->render('RIFileManagerBundle:Default:index.html.twig', array('filemanager_configuration' => $configuration));
    }

    public function pageAction()
    {
        $configuration = array(
            'availableDimensions' => $this->get('service_container')->getParameter('ri.filemanager.dimensions')
        );

        return $this->render('RIFileManagerBundle:Default:page.html.twig', array('filemanager_configuration' => $configuration));
    }

    /**
     * @return array
     */
    public function tinyMCEAction()
    {
        return $this->render('RIFileManagerBundle:Default:tinyMCE.html.twig');
    }

    /**
     * @return array
     */
    public function exampleTinyMCEAction()
    {
        $configuration = array(
            'availableDimensions' => $this->get('service_container')->getParameter('ri.filemanager.dimensions')
        );

        return $this->render('RIFileManagerBundle:Default:exampleTinyMCE.html.twig', array('filemanager_configuration' => $configuration));
    }
}