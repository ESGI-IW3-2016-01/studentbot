<?php
/**
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\Facebook\QuickReply;
use AppBundle\Repository\StudentGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class StudentGroupService
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Service
 */
class StudentGroupService
{
    /**
     * @var StudentGroupRepository $repository
     */
    private $repository;

    /**
     * SchoolService constructor.
     * @param StudentGroupRepository $schoolRepository
     */
    public function __construct(StudentGroupRepository $schoolRepository)
    {
        $this->repository = $schoolRepository;
    }

    /**
     * @param $schoolId
     * @return ArrayCollection
     */
    public function getQuickRepliesForGroups($schoolId): ArrayCollection
    {
        $groups = $this->repository->findBy(['$school' => $schoolId]);
        $replies = new ArrayCollection();

        if ($groups && count($groups) > 0) {

            foreach ($groups as $group) {
                $replies->add(QuickReply::createFromStudentGroup($group));
            }

            return $replies;
        } else {
            return new ArrayCollection();
        }
    }
}