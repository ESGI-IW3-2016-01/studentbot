<?php

namespace AppBundle\Command;


use AppBundle\Entity\Calendar\Calendar;
use AppBundle\Entity\Calendar\Event;
use AppBundle\Repository\Calendar\CalendarRepository;
use Aws\Result;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use ICal\ICal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use DateTime, DateTimeZone;

class ProcessCalendarCommand extends ContainerAwareCommand
{
    /**
     * @var Filesystem $fs
     */
    private $fs;

    /**
     * @var Finder $finder
     */
    private $finder;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var CalendarRepository $calendarReposity
     */
    private $calendarReposity;

    /**
     * @var string $directory
     */
    private $directory;

    /**
     * Allowed file extensions
     */
    const FIlE_EXTESIONS = ['ics', 'ical'];

    /**
     * @var string $successDirectory
     */
    private $successDirectory;

    const FS_AWS = 's3';
    const FS_LOCAL = 'local';

    protected function configure()
    {
        // TODO : add arguments to process a specific file or entry ?
        $this
            ->setName('bot:calendar:process')
            ->setDescription("Process and save calendars to database as events");
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->fs = new Filesystem();
        $this->directory = $this->getContainer()->getParameter('ical_upload_directory');
        $this->successDirectory = $this->getContainer()->getParameter('ical_success_directory');

        if (!$this->fs->exists($this->successDirectory)) {
            $this->fs->mkdir($this->successDirectory, 0755);
            // TODO : stop execution. No directory implies no files
        }

        if (!$this->fs->exists($this->directory)) {
            $this->fs->mkdir($this->directory, 0755);
            // TODO : stop execution. No directory implies no files
        }

        $this->finder = new Finder();
        $this->finder->in($this->directory);

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->calendarReposity = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $calendars = $this->calendarReposity->findBy(['processed' => false]);

        if ($calendars) {
            $output->writeln('<info>' . count($calendars) . ' calendar(s) found to parse.</info>');
            /** @var Calendar $calendar */
            foreach ($calendars as $calendar) {

                $fs = $this->getContainer()->getParameter('file_storage');
                $fs = 's3';

                switch ($fs) {
                    case self::FS_AWS:

                        $s3 = S3Client::factory([
                            'region' => 'eu-west-2',
                            'version' => '2006-03-01',
                            'credentials' => [
                                'key' => $this->getContainer()->getParameter('amazon.access_key'),
                                'secret' => $this->getContainer()->getParameter('amazon.secret_key')
                            ]
                        ]);

                        $bucket = $this->getContainer()->getParameter('amazon.bucket_name');
                        /** @var Result $result */
                        $result = $s3->getObject(array(
                            'Bucket' => $bucket,
                            'Key' => $calendar->getFilename(),
                            'SaveAs' => '/tmp/' . $calendar->getFilename()
                        ));
                        $absoluteFilePath = '/tmp/' . $calendar->getFilename();
                        break;
                    case self::FS_LOCAL:
                        $absoluteFilePath = $this->directory . '/' . $calendar->getFilename();


                        break;
                }

                if ($this->fs->exists($absoluteFilePath)) {
                    $output->writeln('<info>Importing ' . $calendar->getFilename() . ' events</info>');

                    $ical = new ICal($absoluteFilePath);


                    /** @var \Ical\Event $event */
                    foreach ($ical->events() as $event) {

                        dump($event->created ?: 'now');
                        dump($event->lastmodified ?: 'now');
                        dump($event->dtstart);
                        dump($event->dtend);

                        $calendarEvent = new Event(
                            $event->uid,
                            $event->description,
                            $event->summary,
                            new DateTime($event->created ?: 'now'),
                            new DateTime($event->lastmodified ?: 'now'),
                            new DateTime($event->dtstart ?: 'now'),
                            new DateTime($event->dtend ?: 'now'),
                            $event->dtstamp
                        );

                        $calendarEvent->setCalendar($calendar);
                        $this->em->persist($calendarEvent);

                        $calendar->addEvent($calendarEvent);
                    }

                    $calendar->setProcessedAt(
                        new DateTime('now', New DateTimeZone('Europe/Paris'))
                    );
                    $calendar->setProcessed(true);

                    $this->em->persist($calendar);
                    $this->em->flush();

                    // TODO Move file to success or error directory or do something ?
                }
            }
        }
    }
}