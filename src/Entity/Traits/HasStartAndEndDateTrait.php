<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasStartAndEndDateTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Date(message: 'booking.startDate_date')]
    #[Assert\GreaterThan('today', message: 'booking.startDate_greater_than', groups: ['front'])]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Date(message: 'booking.endDate_date')]
    #[Assert\GreaterThan(propertyPath: 'startDate', message: 'booking.endDate_greater_than')]
    private ?\DateTime $endDate = null;

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Allows you to know if the reserved dates are available or not.
     *
     * @return bool
     */
    public function isBookableDates(): bool
    {
        // 1) It is necessary to know the dates which are impossible for the announcement
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // 2) It is necessary to compare the chosen dates with the impossible dates
        $bookingDays = $this->getDays();

        $formatDay = function ($day) {
            return $day->format('Y-m-d');
        };

        // Table of the strings of my days
        $days = array_map($formatDay, $bookingDays);
        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day) {
            if (false !== array_search($day, $notAvailable, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Allows you to retrieve a table of days that correspond to my reservation.
     *
     * @return array An array of DateTime objects representing the days of the reservation
     */
    public function getDays(): array
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);

        return $diff->days;
    }
}
