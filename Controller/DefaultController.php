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
        $dimensions = $this->get('service_container')->getParameter('ri.filemanager.dimensions');

        return $this->render('RIFileManagerBundle:Default:index.html.twig', array('image_edit_dimensions' => $dimensions));
    }

    public function pageAction()
    {
        return $this->render('RIFileManagerBundle:Default:page.html.twig');
    }

    /**
     * @return array
     */
    public function tinyMCEAction()
    {
        return $this->render('RIFileManagerBundle:Default:tinyMCE.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function moveSelectionAction(Request $request)
    {
        $data = array('success' => true);

        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException("Non ajax request");
        }

        $this->getDoctrine()->getConnection()->beginTransaction();
        try
        {
            $destDirId = $request->get('destDirId', 0);
            $dirs = $request->get('dirs', array());
            $files = $request->get('files', array());

            $modelSelectionModel = $this->get('ri.filemanager.model.move_selection_model');
            $data['success'] = $modelSelectionModel->move($destDirId, $files, $dirs);

            $this->getDoctrine()->getManager()->flush();
            $this->getDoctrine()->getConnection()->commit();


        } catch (\Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();
            $data['success'] = false;
            $data['msg'] = $exception->getMessage();
        }

        return new JsonResponse($data);
    }


    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function copySelectionAction(Request $request)
    {
        $data = array('success' => true);

        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException("Non ajax request");
        }

        $this->getDoctrine()->getConnection()->beginTransaction();
        try
        {
            $destDirId = $request->get('destDirId', 0);
            $dirs = $request->get('dirs', array());
            $files = $request->get('files', array());

            $copySelectionModel = $this->get('ri.filemanager.model.copy_selection_model');
            $data['success'] = $copySelectionModel->copy($destDirId, $files, $dirs);

            $this->getDoctrine()->getManager()->flush();
            $this->getDoctrine()->getConnection()->commit();


        } catch (\Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();
            $data['success'] = false;
            $data['msg'] = $exception->getMessage();
        }

        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteSelectionAction(Request $request)
    {
        $data = array('success' => true);

        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException("Non ajax request");
        }

        $this->getDoctrine()->getConnection()->beginTransaction();
        try
        {
            $dirs = $request->get('dirs', array());
            $files = $request->get('files', array());

            $deleteSelectionModel = $this->get('ri.filemanager.model.delete_selection_model');
            $data['success'] = $deleteSelectionModel->delete($files, $dirs);

            $this->getDoctrine()->getManager()->flush();
            $this->getDoctrine()->getConnection()->commit();

            $files = $deleteSelectionModel->getRemovedFiles();
            $uploadedDirectoryManager = $this->get('ri.filemanager.manager.upload_directory_manager');
            $absolutePath = $uploadedDirectoryManager->getAbsoluteUploadDir();
            foreach ($files as $filePath) {
                unlink($absolutePath . $filePath);
            }

        } catch (\Exception $exception) {
            $this->getDoctrine()->getConnection()->rollBack();
            $data['success'] = false;
            $data['msg'] = $exception->getMessage();
        }

        return new JsonResponse($data);
    }
}
