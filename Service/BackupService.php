<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 1:33 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Service;

use Xiranst\Bundle\DataBackupBundle\Export\Export;
use Xiranst\Bundle\DataBackupBundle\Import\Import;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BackupService
{
    private $kernel;
    private $container;
    private $sqlPath;

    public function __construct(KernelInterface $kernel, ContainerInterface $container)
    {
        $this->container = $container;
        $this->sqlPath = $kernel->getRootDir();
    }

    public function exportLocal($file_prefix = null, $parameters = array())
    {
        $exportOptions = array(
            'host' => $this->container->getParameter('database_host'),
            'username' => $this->container->getParameter('database_user'),
            'password' => $this->container->getParameter('database_password'),
            'db_name' => $this->container->getParameter('database_name')
        );
        if (array_key_exists('include_tables', $parameters))
        {
            $exportOptions['include_tables'] = $parameters['include_tables'];
        }
        if (array_key_exists('exclude_tables', $parameters))
        {
            $exportOptions['exclude_tables'] = $parameters['exclude_tables'];
        }
        $exportService = Export::create($exportOptions);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $userID = 0;
        if($user && $user != 'anon.')
            $userID = $user->getId();
        $exportSQLName = '';
        if ($file_prefix)
        {
            $exportSQLName = $file_prefix . '-';
        }
        $exportSQLName .= 'export-' . $userID . '-' . date('Y-m-d-H-i-s') . '.sql';
        return $exportService->dump($this->sqlPath . '/../' . $exportSQLName);
    }

    public function importLocal($file)
    {
        if (!file_exists($file))
            return 'Error Message: this sql file is not exist, please check it again.';
        // $import = new Import($this->remoteHost, $this->remoteUsername, $this->remotePassword, $this->remoteDBName);
        // return $import->import($this->sqlFile);
    }
}