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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function addAction(Request $request)
    {
        $name = $request->request->get('name');
        $parentId = $request->request->get('dir_id');
        $parentDirectory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($parentId);

        $directory = new Directory();
        $directory->setName($name);
        $directory->setParent($parentDirectory);

        $this->getDoctrine()->getManager()->persist($directory);
        $this->getDoctrine()->getManager()->flush();

        return new Response(json_encode($this->get('ri.filemanager.data_provider.directory_data_provider')->convertDirectoryEntityToArray($directory)));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $directoryDataProvider = $this->get('ri.filemanager.data_provider.directory_data_provider');
        $filesDataProvider = $this->get('ri.filemanager.data_provider.file_data_provider');
        $directoryId = (integer) $request->request->get('dir_id', 0);

        if ($directoryId === 0) {
            $responseData = array(
                'id' => $directoryId,
                'dir_id' => (integer) $directoryId,
                'name' => self::HOME_DIRECTORY_NAME,
                'dirs' => $directoryDataProvider->getRootSubDirectories(),
                'files' => $filesDataProvider->getRootDirectoryFiles(),
                'parentsList' => array()
            );
        } else {
            $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($directoryId);
            $responseData = $directoryDataProvider->convertDirectoryEntityToArray($directory);
            $responseData['dirs'] = $directoryDataProvider->getDirectorySubDirectories($directoryId);
            $responseData['parentsList'] = $directoryDataProvider->getDirectoryParentsList($directory);
            $responseData['files'] = $filesDataProvider->getFilesFromDirectory($directoryId);
        }

        return new Response(json_encode($responseData));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $name = $request->request->get('name');
        $directoryId = $request->request->get('dir_id');
        $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($directoryId);

        $directory->setName($name);

        $this->getDoctrine()->getManager()->flush();

        return new Response(json_encode($this->get('ri.filemanager.data_provider.directory_data_provider')->convertDirectoryEntityToArray($directory)));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function removeAction(Request $request)
    {
        $response = array();
        $directoryId = $request->request->get('dir_id');
        $directory = $this->getDoctrine()->getRepository('RIFileManagerBundle:Directory')->find($directoryId);

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

        return new Response(json_encode($response));
    }
}