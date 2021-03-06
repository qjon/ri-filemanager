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
     * Used as standalone version
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->render('RIFileManagerBundle:Default:index.html.twig', array('filemanager_configuration' => $this->get('service_container')->getParameter('ri.filemanager.js_config')));
    }


    /**
     * Used as tinymce plugin
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction()
    {
        $config = $this->get('service_container')->getParameter('ri.filemanager.js_config');
        $config['standAlone'] = false;
        return $this->render('RIFileManagerBundle:Default:page.html.twig', array('filemanager_configuration' => $config));
    }

    /**
     * @return array
     */
    public function exampleTinyMCEAction()
    {
        return $this->render('RIFileManagerBundle:Default:exampleTinyMCE.html.twig', array('filemanager_configuration' => $this->get('service_container')->getParameter('ri.filemanager.js_config')));
    }
}