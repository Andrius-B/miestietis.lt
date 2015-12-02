<?php
namespace Miestietis\MainBundle\Services;

use Miestietis\MainBundle\Entity\Initiative;
use Miestietis\MainBundle\Entity\Problema;

class Type{

    public function itemType($p, $user, $checker)
    {
        foreach($p as $problem)
        {
            if($problem->getUpvotedBy()->contains($user))
            {
                $problem->status = 'disabled';
                $problem->tooltip = 'Pritarti galite tik vieną kartą!';
            } else {
                $problem->status = '';
                $problem->tooltip = 'Pritariu problemai';

                if (!$checker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $problem->tooltip = 'Norėdami pritarti turite prisijungti';
                }
            }
        }
    }

}