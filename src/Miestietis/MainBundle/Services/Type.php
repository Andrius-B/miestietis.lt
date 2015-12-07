<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Initiative;
use Miestietis\MainBundle\Entity\Problema;

class Type {

    public function itemType($p, $i, $tp, $user, $checker)
    {
        foreach ($p as $problem)
        {
            if ($problem->getUpvotedBy()->contains($user))
            {
                $problem->status = 'disabled';
                $problem->tooltip = 'Pritarti galite tik vieną kartą!';
            } else {
                $problem->status = '';
                $problem->tooltip = 'Pritariu problemai';


                if (!$checker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $problem->status = 'disabled';
                    $problem->tooltip = 'Reikalingas prisijungimas';
                }
            }
            if (!$checker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $problem->statusAddInitiative = 'disabled';
                $problem->tooltipAddInitiative = 'Reikalingas prisijungimas';
            } else {
                $problem->statusAddInitiative = '';
                $problem->tooltipAddInitiative = 'Skelbti iniciatyvą';
            }
        }
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
        if($tp != null) {
            foreach ($tp as $problem) {
                if ($problem->getUpvotedBy()->contains($user)) {
                    $problem->status = 'disabled';
                    $problem->tooltip = 'Pritarti galite tik vieną kartą!';
                } else {
                    $problem->status = '';
                    $problem->tooltip = 'Pritariu problemai';


                    if (!$checker->isGranted('IS_AUTHENTICATED_FULLY')) {
                        $problem->status = 'disabled';
                        $problem->tooltip = 'Norėdami pritarti turite prisijungti';
                    }
                }

            }
        }
    }

}