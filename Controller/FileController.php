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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function removeAction($id)
    {
        $response = array();

        try {
            $webDir = $this->get('service_container')->getParameter('kernel.root_dir') . '/../web';
            $file = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->find($id);
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
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function cropImageAction($id, Request $request)
    {
        $response = array('success' => false);
        $data = json_decode($request->getContent());

        try {
            $file = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->find($id);
            $cropModel = $this->get('ri.filemanager.model.crop_image_model');
            $cropModel->setFile($file);
            $cropModel->crop($data->x, $data->y, $data->width, $data->height);

            $response['success'] = true;

        } catch (\Exception $exception) {
            $response['msg'] = $exception->getMessage();
        }

        return new JsonResponse($response);
    }


    /**
     * @param string $path
     *
     * @return JsonResponse
     */
    public function searchAction($path)
    {
        $response = array('success' => false);
        $path = base64_decode($path);

        try {
            $checksum = $this->get('ri.filemanager.model.file_model')->getChecksum($path);
            $file = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->findFileByChecksum($checksum, $path);

            if (!$file) {
                throw new \Exception('File not found');
            }

            $response['file'] = $this->get('ri.filemanager.data_provider.file_data_provider')->convertFileEntityToArray($file);
            $response['success'] = true;

        } catch (\Exception $exception) {
            $response['msg'] = $exception->getMessage();
        }

        return new JsonResponse($response);
    }
}
