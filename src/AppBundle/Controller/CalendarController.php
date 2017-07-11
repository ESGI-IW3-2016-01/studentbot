<?php
/**
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Calendar\Calendar;
use Aws\S3\S3Client;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as AdminBaseController;
use DateTime, DateTimeZone;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CalendarController
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Controller
 */
class CalendarController extends AdminBaseController
{
    const FS_AWS = 's3';
    const FS_LOCAL = 'local';

    private $directory;

    protected function createNewEntity()
    {
        return new Calendar();
    }

    /**
     * Before NEW
     * @param $calendar
     */
    protected function prePersistEntity($calendar)
    {
        if (method_exists($calendar, 'setUpdatedAt')) {
            $calendar->setUpdatedAt(
                new DateTime('now', New DateTimeZone('Europe/Paris'))
            );
        }

        if(!empty($calendar->getFile())) {
            $savedFileName = $this->writeCalendarFile($calendar);
            $calendar->setFilename($savedFileName);
        }
        $calendar->setProcessed(false);
    }

    /**
     * Before EDIT
     * @param $calendar
     */
    protected function preUpdateEntity($calendar)
    {
        if (method_exists($calendar, 'setUpdatedAt')) {
            $calendar->setUpdatedAt(
                new DateTime('now', New DateTimeZone('Europe/Paris'))
            );
        }
    }

    /**
     * @param Calendar $calendar
     * @return string
     */
    private function writeCalendarFile(Calendar $calendar)
    {
        $fs = $this->container->getParameter('file_storage');
        $fs = 's3';
        $this->directory = $this->container->getParameter('ical_upload_directory');
        $file = $calendar->getFile();

        switch ($fs) {
            case self::FS_AWS:
                return $this->writeCalendarFileToAWS($file);
                break;
            case self::FS_LOCAL:
                return $this->writeCalendarFileToLocal($file);
                break;
        }
    }

    /**
     * Save file to Amazon S3
     * @param UploadedFile $file
     * @internal param $calendar
     * @return string
     */
    private function writeCalendarFileToAWS(UploadedFile $file)
    {
        // TODO Replace with new non deprecated method
        $s3 = S3Client::factory([
            'region' => 'eu-west-2',
            'version' => '2006-03-01',
            'credentials' => [
                'key' => $this->container->getParameter('amazon.access_key'),
                'secret' => $this->container->getParameter('amazon.secret_key')
            ]
        ]);

        $this->directory = $this->container->getParameter('ical_upload_directory');
        $bucket = $this->container->getParameter('amazon.bucket_name');

        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $fileName,
            'SourceFile' => $file->getRealPath(),
            'ContentType' => 'text/calendar',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => [
                'fileName' => $fileName
            ]
        ]);

        return $fileName;
    }

    /**
     * Save file localy
     * @param UploadedFile $file
     * @internal param $calendar
     * @return string
     */
    private function writeCalendarFileToLocal(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->directory, $fileName);

        return $fileName;
    }
}