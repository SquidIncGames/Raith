<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require __DIR__.'/../../vendor/autoload.php';

new Raith\MyApp();

$models = [ //Order for create (foreign integrity)
    Raith\Model\SettingModel::class,
    Raith\Model\User\UserRoleModel::class,
    Raith\Model\Character\CharacterRaceModel::class,
    Raith\Model\Character\CharacterAlignmentModel::class,
    Raith\Model\World\StatModel::class,
    Raith\Model\World\JobModel::class,
    Raith\Model\World\WeatherTypeModel::class,
    Raith\Model\World\WeatherChangeModel::class,
    Raith\Model\World\RegionModel::class,
    Raith\Model\World\PlaceModel::class,
    Raith\Model\World\RoadModel::class,
    Raith\Model\World\WeaponTypeModel::class,
    Raith\Model\World\ElementModel::class,
    Raith\Model\User\UserModel::class,
    Raith\Model\Character\CharacterModel::class,
    Raith\Model\User\UserCharacterRightModel::class,
    Raith\Model\World\WeaponModel::class,
    Raith\Model\Action\ActionModel::class,
    Raith\Model\Action\MessageModel::class,
    Raith\Model\Action\RollModel::class,
    Raith\Model\Action\RollDiceModel::class,
    Raith\Model\Action\CustomRollModel::class,
    Raith\Model\Action\DamageRollModel::class,
    Raith\Model\Action\SuccessRollModel::class,
    Raith\Model\Action\StatModificationModel::class,
    Raith\Model\Action\StatModificationLineModel::class
];

$options = getopt("dci", ["drop", "create", "insert"]);

function tryRun($request){
    try{
        return $request->run() ? 'OK' : 'Error';
    }catch(\PDOException $e){
        return $e->getMessage();
    }
}

function tryInsert($model){
    try{
        $model->runInsert();
        if($model::ID != null)
            return $model->{$model::ID};
    }catch(\PDOException $e){
        echo $e->getMessage();
    }
}

if(isset($options['d']) || isset($options['drop'])){
    echo "drop...".PHP_EOL;
    foreach (array_reverse($models) as $model) {
        echo '  '.$model::TABLE.': '.tryRun($model::drop()).PHP_EOL;
    }
}
if(isset($options['c']) || isset($options['create'])){
    echo "create...".PHP_EOL;
    foreach ($models as $model) {
        echo '  '.$model::TABLE.': '.tryRun($model::create()).PHP_EOL;
    }
}
if(isset($options['i']) || isset($options['insert'])){
    echo "insert...".PHP_EOL;

    //Roles
    echo 'user roles'.PHP_EOL;
    $role['admin'] = tryInsert(new Raith\Model\User\UserRoleModel([
        'name' => 'Administrateur',
        'canConnect' => true,
        'isAdmin' => true
    ]));
    $role['new'] = tryInsert(new Raith\Model\User\UserRoleModel([
        'name' => 'Nouveau',
        'canConnect' => false,
        'isAdmin' => false
    ]));
    $role['user'] = tryInsert(new Raith\Model\User\UserRoleModel([
        'name' => 'Utilisateur',
        'canConnect' => true,
        'isAdmin' => false
    ]));
    print_r($role);

    //Races
    echo 'character races'.PHP_EOL;
    $race['elfe'] = tryInsert(new Raith\Model\Character\CharacterRaceModel([
        'name' => 'elfe',
        'lifetime' => '500-800 (600)',
        'appearance' => 'Souvent grands, de forme assez fine, la peau claire sauf pour quelques familles, les oreilles pointues ; la plupart ont possèdent des yeux clairs en amande ; les cheveux en général longs, majoritairement de couleur claire (blanc, gris, blond…) ; leurs vêtements sont faits de matériaux issus de végétaux généralement, pratiquement seuls les soldats ont des armures en cuir, ce qui vaut aussi pour les chaussures.',
        'character' => "Les Elfes sont des êtres fiers. Bien qu’ils accueillent volontiers les étrangers, ils ne montrent que peu de respect voire méprisent en général les représentants des autres races, qui ne vivent pas dans le respect de leur environnement. Pour les Hybrides, ce mépris est poussé à un degré extrême se rapprochant plus de la haine.\nBien que ce trait soit le plus déterminant pour cerner la personnalité d’un Elfe, ils ressentent néanmoins tout un panel d’émotions comparables à celles des Humains.\nCela dit, ils les laissent bien moins paraître que ces derniers, ce qui, bien que cela les fasse paraître plus forts à l’extérieur, constitue une faiblesse à l’intérieur.",
        'socity' => "Les Elfes, bien qu’éparpillés au sein d’Arborea, sont tous sous la même juridiction, dirigée selon un système gérontocratique, c’est-à-dire que c’est un Conseil formé des douze Elfes les plus âgés de la forêt qui dirige.\nPour ce qui est de l’économie, les Elfes n’ont pas de monnaie. Un service est rendu contre un autre service, et le commerce est fait sur une base de troc. Un Elfe a une plus grande mémoire qu’un Humain, et il n’oubliera en aucun cas qui lui doit un service et pourquoi. Les plus étourdis le note dans de grands livres précieusement gardés.\nLa production est faite strictement en fonction des besoins de chacun, et dans le plus grand respect des ressources de la forêt. Chaque Elfe produit lui-même ce dont il a besoin, dans la mesure du possible. Des relations naissent souvent entre des familles lorsque chacune fournit à l’autre une ressource dont elle a besoin.\nLa viande ne fait pas partie de l’alimentation d’un Elfe, excepté en de rares occasions exceptionnelles, lors de grands banquets, pour lesquels quelques bêtes sont chassées. Cela dit, ce n’est pas une interdiction fondamentale, et il arrive souvent que les quelques Elfes qui quittent leur forêt natale pour parcourir le monde se nourrissent régulièrement de viande.\nLa natalité chez les Elfes est faible, car les relations sont souvent platoniques. Étant un peuple aux valeurs spirituelles, les Elfes sont peu intéressés par les plaisirs matériels, et la procréation est souvent dans le simple objectif d’avoir un héritier, même si cela a tendance à changer chez les jeunes générations.",
        'conflicts' => "Les Elfes ne sont pas en guerre ouverte, bien que tout Hybride repéré dans leur territoire soit impitoyablement traqué et tué.\nLes dissentions sont souvent politiques, et les plus grandes familles se livrent un perpétuel jeu de pouvoir consistant à rendre différents services aux Conseillers (ou à des familles plus influentes pour les moins puissantes) afin de s’attirer leurs faveurs et ainsi d’avoir un certain impact au Conseil.",
        'faith' => "Les Elfes n’ont pas de religion à proprement parler et n’adorent pas de dieu, mais ils croient néanmoins en la nature et la force de l’esprit. Pour eux, toute créature vivante a une « âme », un esprit qu’il faut respecter et honorer. Toutes les plantes de la forêt sont reliées par l’Esprit, et les Elfes ont un accord ancestral avec la forêt qui les autorise à consommer ces végétaux tant qu’ils se contentent de ce dont ils ont besoin. Les animaux, quant à eux, ont un esprit plus fort, et bien que les Elfes s’estiment supérieurs à eux, ils se doivent tout de même de les respecter. Ce respect mutuel entre les habitants de la forêt fait d’Arborea un lieu paisible que les membres des autres races le sachant ne peuvent s’empêcher d’envier.\nCependant, ces croyances sont mises en péril par une partie des nouvelles générations. Beaucoup de jeunes Elfes ne montrent que peu d’intérêt pour leur environnement, et certains poussent même le vice jusqu’à l’irrespect. Certains Elfes parmi les plus âgés et les plus sages s’en inquiètent mais le reste de la race semble aveugle à ce problème nouveau.",
        'culture' => "Les Elfes n’ont pas d’école comme on en trouverait chez les Humains. L’éducation se fait entre les membres d’une même famille, qui échangent et se transmettent leur savoir-faire en même temps que leurs valeurs et leur culture. Si un Elfe tient vraiment à apprendre un ouvrage ou un art dont sa famille n’a pas la connaissance, il ira l’apprendre auprès d’une autre famille, en échange d’un service à la mesure de ce qu’il recherche.\nLes Elfes sont de grands artistes, et un nombre assez réduit d’individus s’intéressent à la science. Pour les Elfes, rien n’est immuable, et il serait futile d’essayer de comprendre les rouages inconstants du monde. Cela dit, un certain nombre d’Elfes plus expérimentés étudient la magie, principalement pour analyser ses perturbations afin de prévoir les catastrophes.",
        'magic' => "Les Elfes sont tous reliés aux flux magiques qui parcourent le monde. Ils ressentent tous ses variations, plus ou moins fortement suivant les individus. Lorsqu’une catastrophe importante se produit qui influe sur ces courants, tous les Elfes en sont alors affectés.\nPour utiliser la magie, les Elfes puisent simplement dans ces flux. Seulement, le lien entre la magie et les sorts qu’ils lancent se fait par eux-mêmes, ce qui consomment donc leur propre énergie en même temps que celle circulant dans les courants magiques. Cela dit, les plus grands mages elfes s’entraînent à réduire toujours plus l’énergie perdue par ce biais, ce qui fait des Elfes la race avec le plus fort potentiel magique."
    ]));
    $race['humain'] = tryInsert(new Raith\Model\Character\CharacterRaceModel([
        'name' => 'humain',
        'lifetime' => '40-80 (50, 65 pour les riches)',
        'appearance' => 'Les Humains sont la plus diversifiée des races de Raith. Petits, grands, gros, minces, de couleurs de cheveux et d’yeux variées. Les habitants les plus au Sud ont souvent la peau plus foncée. Bien que cela soit rare, il subsiste quelques familles dans certaines villes avec un teint asiatique, même si personne ne sait vraiment d’où vient cette couleur de peau. Leurs vêtements varient beaucoup suivant leur environnement et surtout leur classe sociale. Dans la plupart des villes, on peut deviner le métier d’un Humain à sa tenue.',
        'character' => "Là encore, les Humains sont très différents les uns des autres. Cela dit, on retrouve quand même des disparités géographiques.\nLes Humains vivant à l’Ouest, près des montagnes des Nains, sont plutôt fermés. Ils sont de nature assez méfiante, car la vie dans un environnement plutôt hostile fait que la confiance est assez difficile à gagner.\nLes Humains vivant à proximité d’Arborea sont à l’inverse les plus ouvertes. Même si à leur arrivée il y a des siècles les colons qui avaient tenté de se servir des ressources de la forêt l’avaient chèrement payé, les Humains de la région ont su outrepasser cette première impression, et commercent à présent régulièrement avec les Elfes.\nLes Humains vivant au Sud sont les plus belliqueux et méfiants. Cela est dû à la proximité des territoires Hybrides. Les villes les plus proches de la côte mènent d’ailleurs des expéditions régulières pour s’assurer qu’aucun Hybride ne puisse pénétrer en territoire humain, les éliminant impitoyablement.",
        'socity' => "Les Humains de Raith vivent dans une société d’organisation féodale. Un Roi les dirige depuis la capitale, entouré d’un nombre de conseillers et ministres, tandis que des nobles de grande lignée dirige les villes importantes et les territoires alentours. Plusieurs familles nobles moins puissantes sont également présentes dans chaque ville et participe à la vie et aux intrigues politiques.\nLa majorité des terres humaines étant constituées de plaines et de collines, ils peuvent donc nourrir sans problème tout le royaume grâce à la culture et l’élevage – enfin, si tous les habitants avaient les mêmes moyens. Les régions rocheuses à l’Ouest, dans lesquelles il est difficile de faire pousser quoi que ce soit, sont très dépendantes de l’importation. Heureusement, grâce à leurs ressources minières importantes, les Humains qui y vivent peuvent s’assurer une source de revenu propre.",
        'conflicts' => "Bien qu’il n’y ait aucune guerre massive, il arrive souvent que les seigneurs de deux villes ou plus aient un différent. Dans ce cas, il n’est pas rare qu’un conflit ouvert éclate, raison pour laquelle chaque ville entretient sa propre armée, et que le métier de mercenaire est encore présent.",
        'faith' => "Les Humains croient en une religion monothéiste, bien que leur culte n’ait pas été nommé car c’est le seul existant et incontestable aux yeux de la majorité. Les membres du clergé sont financés par différentes taxes payées par le peuple. L’athéisme est très mal vu dans le Royaume, et les non-croyants se cachent souvent, voire participent aux rites religieux, afin de ne pas attirer l’attention. Pour plus d’informations, voir la fiche « Religions et croyances ».",
        'culture' => "Les Humains ne sont pas tous égaux devant l’éducation. En effet, seuls ceux qui ont la chance d’habiter en ville ont la chance de pouvoir aller à l’école tous les jours – et encore, seulement ceux dont la famille est assez riche pour cela. Bien sûr, de nombreux villages ont leur propre école, mais bien souvent, les enfants issus d’un milieu social défavorisé ont besoin d’assister leurs parents dans leur travail afin de ne pas être qu’une bouche de plus à nourrir. Cela est particulièrement vrai pour les paysans, surtout dans les régions où la culture est moins efficace (comme l’Ouest et ses terrains rocheux peu fertiles). Les enfants nobles, en revanche, vont rarement à l’école parce qu’ils ont pour la plupart des professeurs particuliers.\nPour ce qui est des enseignements prodigués, les Humains sont assez généralistes : pour eux, toute connaissance est bonne à avoir et est susceptible de servir. Cela dit, les plus pauvres se contenteront des basiques (lire, écrire, compter), tandis que les nobles iront jusqu’à apprendre l’art sous ses nombreuses formes. La poésie, la peinture, la pratique d’au moins un instrument de musique seront des activités nécessaires à un noble pour être bien vu en société. Les bourgeois, quant à eux, reconnaissent souvent l’importance des mathématiques, mais ne négligeront pas les arts, dans l’espoir d’être un jour acceptés à la cour, voire même anoblis, fait rare mais qui arrive parfois lorsqu’une famille a gagné les faveurs du Roi – ou graissé quelques pattes des instances gouvernementales.",
        'magic' => "Les Humains ne sont pas une race naturellement proche de la magie. Pourtant, certains individus ont des affinités particulières pour elle. Bien qu’ils ne soient pas reliés aux flux magiques comme le sont les Elfes, chaque individu humain peut, grâce à un entraînement strict et régulier (plus ou moins en fonction de ses talents naturels), parvenir à utiliser la magie. Cela dit, l’utilisation en est plus limitée que pour les Elfes, car leur énergie naturelle est moins importante. Pour devenir puissant, un mage humain doit donc s’entrainer afin de développer son lien à la magie et d’accroître son énergie spirituelle, mais aussi maintenir un mode de vie sain afin de ne pas réduire l’efficacité de celui-ci.\nC’est pourquoi la plupart des mages humains les plus talentueux sont déjà d’un âge avancé. Les privilèges naturels ne laissent pas toujours le temps nécessaire à leur surpassement."
    ]));
    $race['nain'] = tryInsert(new Raith\Model\Character\CharacterRaceModel([
        'name' => 'nain',
        'lifetime' => '40-80 (50)',
        'appearance' => "Petits, trapus, les Nains sont des êtres à l’air bourru et renfrogné. Les hommes portent tous la barbe, trait qui fait la fierté et l’honneur d’un Nain, et la perdre serait un sort pire que la mort pour eux. Les femmes ont en général un visage joufflu, affublé souvent d’un sourire jovial.\nPetits – ils dépassent très rarement le mètre cinquante –, leurs cheveux sont en général bruns ou noirs, parfois roux, quasiment jamais blonds ou châtains. Il en est de même pour leur barbe.\nLes hommes nains portent souvent une tenue guerrière, sauf lorsque leur activité exige le contraire. Les vêtements des femmes sont au contraire plus variés et se rapprochent parfois de ce que pourraient porter les Humaines",
        'character' => "Les Nains sont la race la plus isolée de Raith. Reclus dans leurs montagnes, il est très rare qu’un Nain en sorte, et la plupart des Humains passeront leur vie entière sans jamais voir un Nain, même en habitant à proximité desdites montagnes. Les Nains sont attachés à leurs villes et leurs souterrains comme les Elfes le sont à leur forêt.\nLes Nains sont des êtres fiers et se montreront assez antipathiques voire hostiles devant un étranger, quel que soit sa race – même s’il s’agit d’un Nain inconnu d’une ville assez éloignée. Cela dit, même si gagner leur confiance peut s’avérer une tâche qui relève de la corvée, les Nains se montrent très amicaux et chaleureux avec quiconque leur prouvant sa valeur, et ce, même s’il s’agit d’un Hybride ou d’un Elfe – même si, dans ce dernier cas, il risque d’être difficile de gagner la confiance des Nains. Ce trait est souvent ce qui surprend le plus les membres des autres races résidants chez les Nains.",
        'socity' => "Chaque cité naine est indépendante dans son fonctionnement mais commerce avec les cités alentours. Celles qui sont en partie à l’air libre font profiter de leurs cultures et leurs élevages, celles qui possèdent des minerais et pierres précieuses rares les vendent, etc. Les villes n’ont pas de dirigeant unique mais chaque maison a son mot à dire dans l’organisation de la cité.\nLe transport d’une marchandise d’une ville à une autre est un métier des plus importants, car parmi les milliers de souterrains creusés par les Nains au fil des millénaires, certains sont désaffectés et parfois impraticables. Il est très facile de s’y perdre, et on dit que s’égarer dans un tunnel des Nains est synonyme de mort certaine, d’autant plus qu’il n’en existe aucune carte complète. En effet, les portions de réseau souterrain inutilisées ou qui étaient dédiés à l’extraction minière sont rapidement habitées par des créatures des ténèbres qui, à défaut d’être aussi puissantes qu’un Nain, sont nombreuses et fourbes.\nLes forgerons nains sont évidemment les plus réputés de tout le continent, d’autant plus qu’il est très difficile voire impossible de se procurer une pièce sortie des forges naines – de nombreuses contrefaçons circulent d’ailleurs chez les Humains. La différence est que les Nains apportent le plus grand soin à chacun de leurs ouvrages, et il arrive que certains forgerons passent plusieurs mois sur la même création, ce que les Humains ne peuvent se permettre en raison de leur durée de vie et surtout pour pouvoir gagner leur pain.",
        'conflicts' => "Les Nains ne sont pas belliqueux, mais ils sont très, très rancuniers. Il n’est pas rare qu’une querelle se transmette sur plus de dix générations (c’est-à-dire plusieurs millénaires), et qu’on oublie même la cause de cette dissension. Chaque maison naine possède traditionnellement un grand livre où le chef de famille inscrit les grandes querelles avec d’autres familles. Le livre est associé à différentes encres, chacune mettant un temps différent à s’effacer, et il est dit qu’une rancune n’est pardonnée que lorsque son entrée dans le grand livre devient illisible. C’est la seule utilité de ce livre, sachant que les Nains font preuve d’une mémoire infaillible quand il s’agit de ressentiment.\nBien que ce ne soit pas arrivé depuis des siècles, en temps de guerre, les Nains choisissent un chef de guerre, qui est couronné Roi des Nains sous son commandement. Cela est valable que ce soit une guerre entre Nains, auquel cas chaque camp choisit un Roi, ou lorsque l’ensemble des Nains s’unit contre une autre race.",
        'faith' => "Les Nains, bien qu’ils n’adorent pas de dieux, rendent régulièrement honneur à leurs héros, ceux dont la mort a été honorable, ainsi qu’à leurs ancêtres. Pour plus d’informations, voir la fiche « Religions et croyances ».",
        'culture' => "Les Nains n’ont pas d’école. La plupart des Nains apprennent le métier d’un de leurs parents, souvent le père pour les garçons et la mère pour les filles, et ce, depuis leur plus jeune âge. Pour les connaissances de base – lire, écrire, compter – ils les apprennent avec leurs parents également. Si un enfant nain se montre intéressé par le travail exercé par un Nain d’une autre famille, et que ses parents lui donnent leur accord, il pourra demander au Nain concerné de lui apprendre son métier. Cette particularité est une des raisons pour laquelle le savoir-faire nain est réputé.\nLes formes d’art privilégiées par les Nains sont les peintures murales ainsi que la gravure et la sculpture, même si pour eux, la métallurgie est également un art. De nombreuses salles communes des Nains sont tapissées d’immenses fresques représentant les exploits de tel héros, et beaucoup de lieux publics sont décorés de statues bien plus grandes que raison.\nLes Nains exercent également des professions théoriques, et les scientifiques nains ont peu à envier aux Humains. Beaucoup de villes naines possèdent une énorme bibliothèque, bien qu’il existe assez peu de livres écrits pour le divertissement.",
        'magic' => "Bien qu’ils soient une race magique au même titre que les Elfes, les Nains sont inconscients de leur potentiel magique, et en sont au mieux effrayés. Les quelques Nains révélant des aptitudes magiques particulières au point de ne plus pouvoir les contenir ont souvent été rejetés par leurs pairs, même s’ils ne les laissent pas mourir ni ne les tuent."
    ]));
    $race['hybride'] = tryInsert(new Raith\Model\Character\CharacterRaceModel([
        'name' => 'hybride',
        'lifetime' => '45-80 (60)',
        'appearance' => "De loin, une personne non avertie aurait presque pu dire qu’un Hybride était humain. « Presque » était le mot. Les Hybrides sont des êtres mi-humains, mi-animaux. En tout cas au niveau du physique, car en réalité, les différences sont bien plus profondes que cela.\nGénéralement, un Hybride ressemblera parfaitement à un Humain, excepté qu’il possédera une caractéristique d’un animal quelconque.  Cela peut aller du plus simple, une queue ou des oreilles, au plus complexe, comme un appareil respiratoire de poisson, faisant de l’Hybride le possédant un être amphibie.\nLeurs vêtements sont adaptés à la vie dans les pays chauds.",
        'character' => "Les Hybrides ont de fortes disparités en termes de caractère. La plupart sont souvent proches des extrêmes : une grande partie des Hybrides vivent avec la haine des Humains, et ne rêvent que du jour où ils pourront venger leurs ancêtres, s’entraînant quotidiennement et avec hargne dans ce but ; tandis qu’une autre n’aspire qu’à vivre en paix, cachés et loin de tout, pour ne plus vivre dans l’horreur et la peur. Une minorité souhaite la réunion et la paix avec les Humains, et les divergences quant aux moyens de cette réunion font que ce camp n’est pas près de réaliser son objectif.",
        'socity' => "Les Hybrides sont la race la moins nombreuse de Raith, et aussi la plus jeune, étant apparue il y a environ 400 ans. Peut-être est-ce la raison pour laquelle ils n’ont pas d’organisation commune propre. La plupart des Hybrides vivent en petites communautés séparées sur l’archipel au Sud de Raith. Certains parmi les plus courageux ont tenté d’explorer plus loin dans l’espoir de trouver une terre bien à eux, et vivent désormais dans les oasis du désert à l’extrême Sud-Est du continent.\nLes Hybrides se cantonnent à un mode de vie simple, sans vraiment en avoir le choix. La plupart des petits groupes s’organisent en tribus sous la direction d’un chef, qui choisit lui-même son successeur. Leurs édifices sont rarement imposants, ne dépassant jamais la taille d’une grande maison. Ils se nourrissent en majorité de fruits et de poissons, ressources les plus abondantes à leur portée (exception faite des habitants du désert), même si la viande fait partie de leur alimentation courante.\nLes Hybrides peuplant le désert sont des nomades, voyageant régulièrement afin de ne pas épuiser les ressources des différentes oasis. Plusieurs tribus se partagent la région, et même si aucune relation n’est officielle, des accords tacites régissent les territoires de chacune, et un respect mutuel existe entre elles.",
        'conflicts' => "Les Hybrides entre rarement en conflits internes, sauf dans les cas où plusieurs individus d’une même tribu convoitent la place de chef.\nSeules les tribus qui vouent une profonde haine aux Humains et, parfois, même aux Elfes, se préparent réellement à un conflit, même si pour l’heure, aucune guerre ouverte n’est d’actualité.",
        'faith' => "Même si dans certaines tribus, des réminiscences des croyances humaines subsistent, la plupart des Hybrides ne croient plus en rien depuis leur exil.",
        'culture' => "Les Hybrides, du fait de leur mode de vie plus simple en communauté plus réduite, souffrent beaucoup moins d’inégalités sociales que les Humains. De ce fait, tous les enfants des tribus vont à l’école quotidiennement, à l’exception des nomades du désert qui n’ont pas d’école et apprennent par l’expérience. Cela dit, leurs enseignements sont beaucoup moins théoriques. Les seuls sujets qui n’ont pas très à quelque chose de pratique et utile à la vie de tous les jours, sont artistiques.\nLa culture des Hybrides est beaucoup plus portée sur le corps que sur l’esprit, et leurs formes d’art favorites en sont ainsi tout ce qui peut être figuratif, du dessin à la sculpture, mais aussi, dans la plupart des tribus de l’archipel, la danse. Celle-ci peut être festive, et pratiquée par tous lors des évènements particuliers, ou même guerrière, dans les tribus les plus belliqueuses, où la force est synonyme de beauté.\nMais dans tous les cas, les Hybrides ont une culture du beau, connue même des autres races. Chez les Humains du Sud, on raconte que les femelles Hybrides sont des démones à la beauté enchanteresse.",
        'magic' => "Du fait des origines de leur race, les Hybrides sont plus proches de la magie que les Humains. Cela dit, ils n’en ont aucune conscience, et l’utilisent parfois sans même le savoir. C’est la raison pour laquelle les belles danseuses Hybrides sont aussi charmeuses, et les puissants guerriers Hybrides aussi furieusement enthousiastes. Les Hybrides utilisent tous la magie inconsciemment lorsqu’ils mettent du cœur à l’ouvrage, quel qu’il soit. C’est d’ailleurs un trait que l’on retrouve chez les Humains les plus doués.\nLeur énergie spirituelle est cependant beaucoup plus importante que celle des Humains, et même si elle n’est pas au niveau de celle des Elfes, elle a la particularité de se régénérer beaucoup plus rapidement au détriment de leur environnement – ce dont les Elfes sont également capables, mais en toute conscience, ce qui leur permet de contrôler ce phénomène (dans les faits, ils ne le font quasiment jamais)."
    ]));
    print_r($race);

    //Alignement
    echo 'character alignments'.PHP_EOL;
    $alignment['loyal_bon'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'loyal bon',
        'description' => " Un personnage Loyal Bon se comporte comme on l’attend d’un défenseur de l’ordre et de la Loi. Déterminé à lutter contre le Mal, il est suffisamment discipliné pour ne jamais cesser le combat. Il dit toujours la vérité, reste fidèle à la parole donnée, aide ceux qui sont dans le besoin et se dresse contre l’injustice. Il déteste voir les coupables impunis et s’élève contre l’injustice.\nL’alignement Loyal Bon mêle honneur et compassion."
    ]));
    $alignment['neutre_bon'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'neutre bon',
        'description' => " Un personnage Neutre Bon fait de son mieux pour faire le bien. Il fait son possible pour aider les autres. Il travaille main dans la main avec les rois et les juges mais il ne se sent pas tenu de leur obéir.\nÊtre Neutre Bon permet de faire le Bien sans être bloqué par le carcan de la Loi."
    ]));
    $alignment['chaotique_bon'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'chaotique bon',
        'description' => "Un personnage Chaotique Bon agit selon sa conscience, sans se soucier de ce que les autres pensent de lui. Il se comporte comme il l’entend, mais cela ne l’empêche pas d’être gentil et bienveillant. Il croit à la bonté et au bon droit mais se moque des lois et des règles. Il déteste les gens qui intimident les autres et leur disent comment se comporter. Il suit sa propre morale qui, bien que bienveillante, ne s’accorde pas forcément avec celle de la société.\nL’alignement Chaotique Bon conjugue bonté et esprit libre."
    ]));
    $alignment['loyal_neutre'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'loyal neutre',
        'description' => "Un personnage Loyal Neutre agit selon la loi, la tradition ou son code de conduite personnel. L’ordre et l’organisation représentent tout pour lui. Il se peut qu’il croit en l’ordre individuel et vive selon un code ou une règle ou qu’il croit en l’ordre général et privilégie un gouvernement fort et organisé.\nUn individu Loyal Neutre est fiable et honorable sans pour autant être fanatique."
    ]));
    $alignment['neutre'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'neutre',
        'description' => "Un personnage neutre fait ce qui lui semble une bonne idée. Il n’a pas vraiment de préférence lorsqu’il s’agit de choisir entre le Bien et le Mal ou entre la Loi et le Chaos (c’est ainsi que le personnage Neutre est parfois qualifié de « Neutre absolu »). Dans la plupart des cas, la neutralité représente une absence de convictions plutôt qu’un véritable dévouement envers la neutralité. Le personnage aurait ainsi plutôt tendance à penser que le Bien vaut mieux que le Mal, car il préfère que ses voisins et ses dirigeants politiques se montrent bienveillants plutôt que malveillants. Cela étant, il ne se sent nullement obligé de défendre la cause du Bien, ni en pratique ni en théorie.\nEn revanche, chez certains, la neutralité est un choix philosophique. Pour eux, le Bien, le Mal, la Loi et le Chaos sont partiaux et représentent un danger, comme tous les extrêmes. Ils prônent donc l’équilibre, qui leur paraît être le meilleur choix à long terme.\nÊtre Neutre permet d’agir naturellement en toute situation, sans se laisser guider par ses préjugés ou ses obligations."
    ]));
    $alignment['chaotique_neutre'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'chaotique neutre',
        'description' => "Un personnage Chaotique Neutre agit comme bon lui semble. C’est avant tout un individualiste. Il accorde une immense valeur à sa liberté mais il ne cherche pas à protéger celle des autres. Il évite l’autorité, déteste les restrictions et remet toujours la tradition en question. Sa lutte contre la société organisée n’est pas motivée par un désir d’anarchie car cette volonté devrait s’accompagner d’idées nobles (libérer les opprimés du joug de l’autorité) ou mauvaises (faire souffrir ceux qui sont différents de lui). Le personnage est parfois imprévisible mais son comportement n’est pas complètement aléatoire. Il est nettement plus probable qu’il traverse un pont plutôt qu’il en saute.\nÊtre Chaotique Neutre permet de profiter de la véritable liberté, celle qui ne suit pas les restrictions imposées par la société, et n’oblige pas à faire le bien à tout prix."
    ]));
    $alignment['loyal_mauvais'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'loyal mauvais',
        'description' => "Un individu Loyal Mauvais prend tout ce qu’il désire, dans les limites de son code de conduite sans se soucier de ceux à qui il peut faire du mal. Pour lui, les traditions, la loyauté et l’obéissance ont de l’importance, mais pas la liberté ni la dignité ou la vie. Il suit les règles existantes, mais ne montre ni pitié ni compassion. Il accepte la hiérarchie, et, même s’il préfère diriger, il est prêt à obéir. Il condamne les autres, non pas en fonction de leurs actes, mais en fonction de leur race, de leur religion, de leur nationalité ou de leur rang social. Il répugne à violer la Loi ou à trahir sa parole.\nCette répugnance lui vient en partie de sa nature et en partie de sa dépendance vis à vis l’ordre établi pour se protéger de ceux qui s’opposent à lui sur des questions d’ordre moral. Certains Loyaux Mauvais se fixent eux-mêmes des limites, telles que ne jamais tuer de sang-froid (ils chargent leurs sbires de le faire à leur place) ou ne pas maltraiter les enfants (sauf lorsqu’il est impossible de faire autrement). Ils pensent que ces règles de conduite les placent au-dessus des scélérats sans scrupules.\nIl arrive que des individus ou des créatures se dévouent au mal avec le même zèle que les croisés des forces du Bien. En plus de nuire aux autres par intérêt, ils prennent plaisir à promouvoir la cause du Mal. Il arrive qu’ils fassent le mal pour servir leur dieu ou leur maître.\nL’alignement Loyal Mauvais représente un Mal méthodique, intentionnel et organisé."
    ]));
    $alignment['neutre_mauvais'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'neutre mauvais',
        'description' => "Un individu Neutre Mauvais fait tout ce qu’il veut tant qu’il peut s’en tirer. Il ne pense tout simplement qu’à lui. Il se moque de tuer des gens par profit, pour le plaisir, ou parce que cela l’arrange. Il n’apprécie pas particulièrement l’ordre et pense que le respect de la Loi, d’un code de conduite ou des traditions ne le rendra pas meilleur ou plus noble. Il ne montre pas une nature agitée et n'est pas pour la recherche de conflits caractéristique des êtres Chaotiques Mauvais.\nCertains individus Neutres Mauvais érigent le Mal en idéal et s’y dévouent corps et âme. La plupart du temps, ils se consacrent à un dieu ou à une société secrète maléfique.\nL’alignement Neutre Mauvais représente le Mal à l’état brut, sans honneur ni nuance."
    ]));
    $alignment['chaotique_mauvais'] = tryInsert(new Raith\Model\Character\CharacterAlignmentModel([
        'name' => 'chaotique mauvais',
        'description' => "Un individu Chaotique Mauvais suit sa cupidité, sa haine et sa soif de destruction. Il s’énerve facilement, il est sadique, violent et complètement imprévisible. S’il veut quelque chose pour lui, il se montre simplement brutal et impitoyable mais s’il s’est donné pour objectif de répandre le Mal et le Chaos, c’est encore pire. Fort heureusement, ses plans sont désorganisés et les groupes qu’il constitue ou auxquels il se joint sont très mal structurés. La plupart du temps, les êtres Chaotiques Mauvais ne coopèrent que sous la menace et leur chef reste place uniquement tant qu’il survit aux tentatives visant à le renverser ou l’assassiner.\nLes êtres Chaotiques Mauvais représentent la destruction, non seulement de la beauté et de la vie, mais aussi de l’ordre sur lequel cette beauté et cette vie s’appuient."
    ]));
    print_r($alignment);

    //Metier
    echo 'jobs'.PHP_EOL;
    $job['alchimiste'] = Raith\Model\World\JobModel::insertJob('alchimiste')->id;
    $job['forgeron'] = Raith\Model\World\JobModel::insertJob('forgeron')->id;
    print_r($job);

    //Meteo
    echo 'weather_types'.PHP_EOL;
    $weather['sunny'] = tryInsert(new Raith\Model\World\WeatherTypeModel(['name' => 'ensoleillé']));
    $weather['cloudy'] = tryInsert(new Raith\Model\World\WeatherTypeModel(['name' => 'nuageux']));
    $weather['raining'] = tryInsert(new Raith\Model\World\WeatherTypeModel(['name' => 'pluvieux']));
    $weather['storm'] = tryInsert(new Raith\Model\World\WeatherTypeModel(['name' => 'tempête']));
    print_r($weather);

    //Changement de meteo
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['sunny'], 'to' => $weather['sunny'], 'probability' => 3]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['sunny'], 'to' => $weather['cloudy'], 'probability' => 1]));

    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['cloudy'], 'to' => $weather['sunny'], 'probability' => 2]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['cloudy'], 'to' => $weather['cloudy'], 'probability' => 1]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['cloudy'], 'to' => $weather['raining'], 'probability' => 1]));

    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['raining'], 'to' => $weather['cloudy'], 'probability' => 11]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['raining'], 'to' => $weather['raining'], 'probability' => 9]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['raining'], 'to' => $weather['storm'], 'probability' => 1]));

    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['storm'], 'to' => $weather['storm'], 'probability' => 3]));
    tryInsert(new Raith\Model\World\WeatherChangeModel(['current' => $weather['storm'], 'to' => $weather['raining'], 'probability' => 1]));

    //Regions
    echo 'regions'.PHP_EOL;
    $region['region'] = tryInsert(new Raith\Model\World\RegionModel([
        'name' => 'la region',
        'weather' => $weather['sunny'],
        'weather_update' => new DateTime()
    ]));
    print_r($region);

    //Emplacements
    echo 'places'.PHP_EOL;
    $place['place'] = tryInsert(new Raith\Model\World\PlaceModel([
        'name' => 'la place',
        'discord' => '1234567890',
        'region' => $region['region']
    ]));
    $place['lac'] = tryInsert(new Raith\Model\World\PlaceModel([
        'name' => 'le lac',
        'discord' => '0987654321',
        'region' => $region['region']
    ]));
    print_r($place);

    //Routes
    tryInsert(new Raith\Model\World\RoadModel(['place_from' => $place['place'], 'place_to' => $place['lac']]));

    //Type d'arme
    echo 'weapon_types'.PHP_EOL;
    $weapon_type['epee'] = Raith\Model\World\WeaponTypeModel::insertWeaponType('epée')->id;
    $weapon_type['arc'] = Raith\Model\World\WeaponTypeModel::insertWeaponType('arc')->id;
    $weapon_type['dague'] = Raith\Model\World\WeaponTypeModel::insertWeaponType('dague')->id;
    $weapon_type['magie_elementaire'] = Raith\Model\World\WeaponTypeModel::insertWeaponType('magie élémentaire')->id;
    print_r($weapon_type);

    //Statistiques
    echo 'elements'.PHP_EOL;
    $element['force'] = Raith\Model\World\ElementModel::insertElement('force')->id;
    $element['dext'] = Raith\Model\World\ElementModel::insertElement('dextérité')->id;
    $element['int'] = Raith\Model\World\ElementModel::insertElement('intelligence')->id;
    $element['sag'] = Raith\Model\World\ElementModel::insertElement('sagesse')->id;
    $element['char'] = Raith\Model\World\ElementModel::insertElement('charisme')->id;
    $element['const'] = Raith\Model\World\ElementModel::insertElement('constitution')->id;
    print_r($element);

    //Users
    echo 'users'.PHP_EOL;
    $user['admin'] = tryInsert(new Raith\Model\User\UserModel([
        'name' => 'Admin',
        'password' =>  password_hash('P@ssw0rd', PASSWORD_DEFAULT),
        'mail' => 'root@test.fr',
        'discord'=> '195607134858248192',
        'role' => $role['admin']
    ]));
    $user['user1'] = tryInsert(new Raith\Model\User\UserModel([
        'name' => 'User1',
        'password' =>  password_hash('P@ssw0rd1', PASSWORD_DEFAULT),
        'mail' => 'user1@test.fr',
        'discord'=> '172029861370527744',
        'role' => $role['user']
    ]));
    $user['user2'] = tryInsert(new Raith\Model\User\UserModel([
        'name' => 'User2',
        'password' =>  password_hash('P@ssw0rd2', PASSWORD_DEFAULT),
        'mail' => 'user2@test.fr',
        'discord'=> '194393853938106368',
        'role' => $role['new']
    ]));
    print_r($user);

    //Characters
    $charater['pierre'] = tryInsert(new Raith\Model\Character\CharacterModel([
        'firstname' => 'Pierre',
        'surname' => 'Caillou',
        'race' => 'Rock',
        'size' => '20',
        'weight' => '2',
        'birthday' => '1001-01-02',
        'alignment' => $alignment['loyal_bon'],
        'personality' => 'hyperactif ... vraiment',
        'history' => 'Avant il était. Maintenant il sera.',
        'description' => 'Il est rond et mignon.',
        'place' => $place['place'],
        'owner' => $user['user1'],
        'valid' => true
    ]));
    //TODO: add valid => false character
    print_r($charater);

    //Droits
    tryInsert(new Raith\Model\User\UserCharacterRightModel([
        'user' => $user['user2'],
        'character' => $charater['pierre'],
        'canPlay' => true,
        'canEdit' => false,
        'canManage' => false
    ]));

    //Settings
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'role_visitor',
        'type' => 'int',
        'value' => $role['new'],
    ]));
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'role_default',
        'type' => 'int',
        'value' => $role['user'],
    ]));
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'character_create_max_stat_points',
        'type' => 'int',
        'value' => 45,
    ]));
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'character_create_max_job_points',
        'type' => 'int',
        'value' => 30,
    ]));
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'character_create_max_weapon_points',
        'type' => 'int',
        'value' => 30,
    ]));
    tryInsert(new Raith\Model\SettingModel([
        'key' => 'character_create_place',
        'type' => 'int',
        'value' => $place['place'],
    ]));


    //StatModification NOTE: WIP
    Raith\Model\Action\StatModificationModel::insertModification($user['user1'], $charater['pierre'], $place['place'], new \DateTime(), true, "création des maitrises", [
        $weapon_type['epee'] => 24,
        $weapon_type['magie_elementaire'] => 6,
        $job['forgeron'] => 15
    ]);
    Raith\Model\Action\StatModificationModel::insertModification($user['admin'], $charater['pierre'], $place['place'], new \DateTime(), true, "création des stats", [
        $element['force'] => 54,
        $element['dext'] => 12,
        $element['int'] => 5,
        $element['sag'] => 42,
        $element['char'] => 23,
        $element['const'] => 67
    ]);


    //FIXME: Temporary model
    $weapon['excalibur'] = tryInsert(new Raith\Model\World\WeaponModel([
        'type' => $weapon_type['epee']
    ]));

    //Actions NOTE: WIP
    Raith\Model\Action\CustomRollModel::makeCustomRoll($user['user1'], $charater['pierre'], $place['place'], 'jet personalisé d\'exemple', 42, 5)->validate();
    Raith\Model\Action\SuccessRollModel::makeSuccessRoll($user['user1'], $charater['pierre'], $place['place'], 'jet de reussite d\'exemple', true, $element['force'], 50, 5, null, 1);
    Raith\Model\Action\DamageRollModel::makeDamageRoll($user['user1'], $charater['pierre'], $place['place'], 'jet de degat d\'exemple', true, 6, 2, $weapon['excalibur'], 2);
}
echo 'end'.PHP_EOL;
?>