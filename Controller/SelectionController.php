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
class SelectionController extends Controller
{
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
            $data = json_decode($request->getContent(), true);

            $modelSelectionModel = $this->get('ri.filemanager.model.move_selection_model');
            $data['success'] = $modelSelectionModel->move($data['destDirId'], $data['files'], $data['dirs']);

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
            $data = json_decode($request->getContent(), true);

            $copySelectionModel = $this->get('ri.filemanager.model.copy_selection_model');
            $data['success'] = $copySelectionModel->copy($data['destDirId'], $data['files'], $data['dirs']);

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
            $data = json_decode($request->getContent(), true);

            $deleteSelectionModel = $this->get('ri.filemanager.model.delete_selection_model');
            $data['success'] = $deleteSelectionModel->delete($data['files'], $data['dirs']);

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
