<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\HttpException;
use Krutush\Template\Html;
use Raith\Model\World\RegionModel;
use Raith\Model\World\PlaceModel;
use Raith\Model\World\RoadModel;

class WorldController extends MyController{
    public function index(){
        $this->getHtml('World/Index')->run();
    }

    public function list(){
        $regions = RegionModel::load(RegionModel::all(), 'places');
        $this->getHtml('World/List')
            ->set('regions', $regions)
            ->run();
    }

    public function weather(){
        $regions = RegionModel::updateWeathers(RegionModel::all());
        $this->getHtml('World/Weather')
            ->set('regions', $regions)
            ->run();
    }

    public function place(int $id){
        $place = PlaceModel::find($id);
        if($place == null)
            throw new HttpException(404);

        $this->getHtml('World/Place')
            ->set('place', $place)
            ->run();
    }

    public function region(int $id){
        $region = RegionModel::find($id);
        if($region == null)
            throw new HttpException(404);

        RegionModel::updateWeathers([$region]);

        $this->getHtml('World/Region')
            ->set('region', $region)
            ->run();
    }
}