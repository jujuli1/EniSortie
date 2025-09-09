<?php

namespace App\Form\Model;

use App\Entity\Campus;

class OutingSearch
{
    private ?Campus $campus = null;
    private ?string $nameContentWord = null;
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;

    private bool $isOrganizer = false;
    private bool $isParticipant = false;
    private bool $isNotParticipant = false;
    private bool $isPassed = false;

    // --- Campus ---
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    // --- Name ---
    public function getNameContentWord(): ?string
    {
        return $this->nameContentWord;
    }

    public function setNameContentWord(?string $word): void
    {
        $this->nameContentWord = $word;
    }

    // --- Dates ---
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $date): void
    {
        $this->startDate = $date;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $date): void
    {
        $this->endDate = $date;
    }

    // --- Checkboxes ---
    public function isOrganizer(): bool
    {
        return $this->isOrganizer;
    }

    public function setIsOrganizer(bool $isOrganizer): void
    {
        $this->isOrganizer = $isOrganizer;
    }

    public function isParticipant(): bool
    {
        return $this->isParticipant;
    }

    public function setIsParticipant(bool $isParticipant): void
    {
        $this->isParticipant = $isParticipant;
    }

    public function isNotParticipant(): bool
    {
        return $this->isNotParticipant;
    }

    public function setIsNotParticipant(bool $isNotParticipant): void
    {
        $this->isNotParticipant = $isNotParticipant;
    }

    public function isPassed(): bool
    {
        return $this->isPassed;
    }

    public function setIsPassed(bool $isPassed): void
    {
        $this->isPassed = $isPassed;
    }

}