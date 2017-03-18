<?php
namespace AppBundle\Controller;

trait TraitYesOrNo
{
    private function yesOrNo()
    {
        $yesOrNo = new \AppBundle\Service\YesOrNo();
        $json_data = $yesOrNo->yesOrNo();
        $data = json_decode($json_data);
        $res = $data->image;

        return $res;
    }
}
