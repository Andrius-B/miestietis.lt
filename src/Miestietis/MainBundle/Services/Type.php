<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Initiative;
use Miestietis\MainBundle\Entity\Problema;

class Type {

    public function itemType($i, $user, $checker)
    {
        foreach ($i as $initiative) {
            if ($initiative->getParticipants()->contains($user)) {
                $initiative->status = 'disabled';
                $initiative->tooltip = 'Jūs jau dalyvaujate';
            } else {
                $initiative->status = '';
                $initiative->tooltip = 'Dalyvauti iniciatyvoje';

                if (!$checker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $initiative->status = 'disabled';
                    $initiative->tooltip = 'Reikalingas prisijungimas';
                }
            }
        }
    }
}
