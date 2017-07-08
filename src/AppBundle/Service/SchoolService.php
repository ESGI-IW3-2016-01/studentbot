<?php
/**
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\Facebook\QuickReply;
use AppBundle\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Class SchoolService
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Service
 */
class SchoolService
{
    /**
     * @var SchoolRepository $repository
     */
    private $repository;

    /**
     * SchoolService constructor.
     * @param SchoolRepository $schoolRepository
     */
    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->repository = $schoolRepository;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuickRepliesForSchools(): ArrayCollection
    {
        $schools = $this->repository->findAll();
        $replies = new ArrayCollection();

        if ($schools && count($schools) > 0) {

            foreach ($schools as $school) {
                $replies->add(QuickReply::createFromSchool($school));
            }

            return $replies;
        } else {
            return new ArrayCollection();
        }
    }

}