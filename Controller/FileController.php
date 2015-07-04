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

use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Model\UploadedFileParametersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DirectoryController
 *
 * @package RI\FileManagerBundle\Controller
 */
class FileController extends Controller
{
    const HOME_DIRECTORY_NAME = 'Home';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $directoryId = $request->request->get('dirId', null);
        $directory = ($directoryId) ? $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($directoryId) : null;
        $uploadedFile = $request->files->get('file');
        $fileName = $request->request->get('flowFilename');

        $file = $this->get('ri.filemanager.model.file_model')->save($fileName, $uploadedFile, $directory);

        return new JsonResponse($this->get('ri.filemanager.data_provider.file_data_provider')->convertFileEntityToArray($file));
    }


    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeAction(Request $request)
    {
        $fileId = $request->request->get('file_id', 0);
        $response = array();

        try {
            $webDir = $this->get('service_container')->getParameter('kernel.root_dir') . '/../web';
            $file = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->find($fileId);
            unlink($webDir . $file->getPath());
            $this->getDoctrine()->getManager()->remove($file);
            $this->getDoctrine()->getManager()->flush();
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['error'] = array(
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            );
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function cropImageAction(Request $request)
    {
        $response = array('success' => false);

        $fileId = $request->get('id');
        $posX = $request->get('x');
        $posY = $request->get('y');
        $width = $request->get('width');
        $height = $request->get('height');

        try {
            $file = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->find($fileId);
            $cropModel = $this->get('ri.filemanager.model.crop_image_model');
            $cropModel->setFile($file);
            $cropModel->crop($posX, $posY, $width, $height);

            $response['success'] = true;

        } catch (\Exception $exception) {
            $response['msg'] = $exception->getMessage();
        }

        return new JsonResponse($response);
    }
}
