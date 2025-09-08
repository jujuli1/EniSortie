<?php

namespace App\Service;

use App\Entity\Outing;
use App\Entity\Utilisateur;

class OutingPermissionService
{
    public function canShow(Outing $outing): bool
    {
        return $outing->getStatus() && $outing->getStatus()->getLabel() !== 'Créée';
    }

    public function canUnsubscribe(Outing $outing, Utilisateur $user): bool
    {
        return $outing->getParticipants()->contains($user)
            && $outing->getOrganizer() === $user;
    }

    public function canSubscribe(Outing $outing, Utilisateur $user): bool
    {
        return $outing->getStatus()
            && $outing->getStatus()->getLabel() === 'Ouverte'
            && !$outing->getParticipants()->contains($user)
            && $outing->getOrganizer() !== $user;
    }

    public function canEditAndPublish(Outing $outing, Utilisateur $user): bool
    {
        return $outing->getOrganizer() === $user
            && $outing->getStatus()
            && $outing->getStatus()->getLabel() === 'Créée';
    }

    public function canCancel(Outing $outing, Utilisateur $user): bool
    {
        return $outing->getOrganizer() === $user
            && $outing->getStatus()
            && $outing->getStatus()->getLabel() === 'Ouverte';
    }

    public function canDesist(Outing $outing, Utilisateur $user): bool
    {
        return $outing->getParticipants()->contains($user)
            && $outing->getOrganizer() !== $user
            && $outing->getStatus()->getLabel() === 'Ouverte';
    }


}