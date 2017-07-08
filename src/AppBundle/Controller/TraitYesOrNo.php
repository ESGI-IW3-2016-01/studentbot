<?php
namespace AppBundle\Controller;

use AppBundle\Service\YesOrNo;

trait TraitYesOrNo
{
    private function yesOrNo()
    {
        /** @var YesOrNo $yesOrNo */
        $yesOrNo = $this->container->get('app.yesorno_service');
        $json_data = $yesOrNo->yesOrNo();
        $data = json_decode($json_data);
        $res = $data->image;

        return $res;
    }
}
