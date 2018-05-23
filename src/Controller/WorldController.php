<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Raith\Model\World\RegionModel;

class WorldController extends MyController{
    public function index(){
        (new Html('World/Index'))->run();
    }

    public function list(){
        $regions = RegionModel::load(RegionModel::all(), 'places');
        (new Html('World/List'))
            ->set('regions', $regions)
            ->run();
    }

    public function weather(){
        $regions = RegionModel::updateWeathers(RegionModel::all());
        (new Html('World/Weather'))
            ->set('regions', $regions)
            ->run();
    }
}