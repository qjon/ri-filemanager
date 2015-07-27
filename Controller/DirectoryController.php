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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DirectoryController
 *
 * @package RI\FileManagerBundle\Controller
 */
class DirectoryController extends Controller
{
    const HOME_DIRECTORY_NAME = 'Home';


    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $parentDirectory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($data->dir_id);

        $directory = new Directory();
        $directory->setName($data->name);
        $directory->setParent($parentDirectory);

        $this->getDoctrine()->getManager()->persist($directory);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('ri.filemanager.data_provider.directory_data_provider')->convertDirectoryEntityToArray($directory));
    }

    /**
     * Return list of directories
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function listAction($id)
    {
        $id = (integer) $id;
        $directoryDataProvider = $this->get('ri.filemanager.data_provider.directory_data_provider');
        $filesDataProvider = $this->get('ri.filemanager.data_provider.file_data_provider');

        if ($id === 0) {
            $responseData = array(
                'id' => $id,
                'dir_id' => $id,
                'name' => self::HOME_DIRECTORY_NAME,
                'dirs' => $directoryDataProvider->getRootSubDirectories(),
                'files' => $filesDataProvider->getRootDirectoryFiles(),
                'parentsList' => array()
            );
        } else {
            $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($id);
            $responseData = $directoryDataProvider->convertDirectoryEntityToArray($directory);
            $responseData['dirs'] = $directoryDataProvider->getDirectorySubDirectories($id);
            $responseData['parentsList'] = $directoryDataProvider->getDirectoryParentsList($directory);
            $responseData['files'] = $filesDataProvider->getFilesFromDirectory($id);
        }

        return new JsonResponse($responseData);
    }


    /**
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function saveAction($id, Request $request)
    {
        $data = json_decode($request->getContent());
        $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($id);

        $directory->setName($data->name);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('ri.filemanager.data_provider.directory_data_provider')->convertDirectoryEntityToArray($directory));
    }


    /**
     * Remove directory
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function removeAction($id)
    {
        $response = array();
        $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($id);

        try {
            if (count($directory->getChildren()) > 0) {
                throw new \Exception($this->get('translator')->trans('directory.is.not.empty.subdirs'));
            }

            $files = $this->getDoctrine()->getRepository('RIFileManagerBundle:File')->findBy(array('directory' => $directory));
            if (count($files) > 0) {
                throw new \Exception($this->get('translator')->trans('directory.is.not.empty.files'));
            }

            $this->getDoctrine()->getManager()->remove($directory);
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
}